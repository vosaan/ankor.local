<?

/**
 * The Shopping cart controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Cart extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Корзина';
	}
	
	/**
	 * The shopping cart index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		switch ( Request::get('action') )
		{
			case 'put':
				return $this->put();
				
			case 'update':
				return $this->update();

			case 'shipping':
				return $this->shipping();
				
			case 'order':
				return $this->order();
				
			case 'custom':
				return $this->custom();
		}
		
		$Cart = Cart::getCart();
		if ( !$Cart->hasItems() )
		{
			return $this->halt( ':'._L('Controller_Frontend') );
		}
		
		$this->getView()->set('Cart', $Cart);
		return $this->getView()->render();
	}
	
	/**
	 * The function puts Item in Cart.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function put()
	{
		$Cart = Cart::getCart();
		$Item = new Cart_Item( Request::get('product'), Request::get('quantity') );
		$Cart->addItem( $Item );
		$Cart->save();
		$response = array();
		$response['html'] = $this->getView()->htmlCartStatus();
		$Unit = $Item->getProduct();
		$response['notify'] = sprintf( 'Товар %s (%s - %s), %dшт. на общую сумму %d грн. добавлен в корзину.', 
			$Unit->getProduct()->Name, $Unit->Name, $Unit->Unit, $Item->Quantity, Price::show( $Item->getAmount() ) );
		$response['result'] = 1;
		return $this->outputJSON( $response );
	}
	
	/**
	 * The function updates Item in Cart.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function update()
	{
		$Cart = Cart::getCart();
		$Product = new Product();
		$response = array( 'result' => 0 );

		$Item = $Cart->getItem( Request::get('item') );
		if ( $Item )
		{
			$quantity = intval( Request::get('quantity') );
			if ( $quantity )
			{
				$Item->Quantity = $quantity;
				$response['quantity'] = $Item->Quantity;
			}
			else
			{
				if ( $Cart->dropItem( $Item ) )
				{
					$response['quantity'] = 0;
				}
				else
				{
					$response['quantity'] = $Item->Quantity;
				}
			}
			$Cart->save();
			$response['html']		= $this->getView()->htmlCartStatus();
			$response['count']		= $Cart->getItemsAmount();
			$response['amount']		= Price::show( $Item->getAmount() );
			$response['total']		= Price::show( $Cart->getTotal() );
			$response['grand']		= Price::show( $Cart->getGrandTotal() );
			$response['result']		= 1;
		}
		else
		{
			$response['msg'] = 'Товар не найден';
		}
		return $this->outputJSON( $response );
	}
	
	/**
	 * The function updates shipping in Cart.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function shipping()
	{
		$Cart = Cart::getCart();
		$Shipping = new Shipping();
		$Shipping = $Shipping->findItem( array( 'Id = '.Request::get('id') ) );
		$Cart->setShipping( $Shipping );
		$Cart->save();
		$response['grand']		= Price::show( $Cart->getGrandTotal() );
		$response['result']		= 1;
		return $this->outputJSON( $response );
	}
	
	/**
	 * The function posts Order.
	 * 
	 * @access private
	 * @param object $Order The Order to save.
	 * @param object $Cart The Cart object to clear items.
	 * @return string The JSON response.
	 */
	private function postOrder( Order $Order, Cart $Cart = null )
	{
		$error = array();

		$Order->setPost( $_POST );
		$Custom = $Order->getCustom();
		$Address = $Order->getAddress();
		if ( $Order->Type == Order::STANDARD && ( !$Address->Name || !$Address->Phone ) 
			|| $Order->Type == Order::CUSTOM && ( !$Custom->Name || !$Custom->Email )
			|| $Order->Type == Order::PRODUCT && ( !$Address->Name || !$Address->Phone ) )
		{
			$error[] = 'Заполните все обязательные поля';
		}
		else if ( $Order->Type != Order::CUSTOM && !Error::check( $Address->Email, 'email' ) )
		{
			$error[] = 'Введите корректный E-mail';
		}
		else if ( $Order->save() )
		{
			if ( !empty( $_FILES['file']['tmp_name'] ) )
			{
				if ( File::upload( $Order, $_FILES['file'] ) )
				{
					$Order->save();
				}
			}
			if ( $Cart )
			{
				$Cart->clear();
				$Cart->save();
			}

			$Email = new Email_Order( $Order );
			if ( !$Email->send() )
			{
				$error[] = 'Ошибка отправки сообщения';
			}
		}
		else
		{
			$error[] = 'Ошибка записи данных';
		}
		
		$response = array('result' => count( $error ) ? 0 : 1 );
		$response['posted'] = 1;
		$response['msg'] = implode( "\n", $error );
		return $this->outputJSON( $response );
	}
	
	
	/**
	 * The function posts order.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function order()
	{
		$Cart = Cart::getCart();
		$Order = $Cart->getOrder();
		
		$Order->Type = Order::STANDARD;
		return $this->postOrder( $Order, $Cart );
	}
	
	/**
	 * The function posts custom order.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function custom()
	{
		$Order = new Order();
		$Order->Type = Request::get('custom') ? Order::CUSTOM : Order::PRODUCT;
		$Order->setPost( $_POST );
		return $this->postOrder( $Order );
	}
	
	/**
	 * The function posts orders.
	 * 
	 * @access public
	 * @return string The  JSON response.
	 */
	public function app()
	{
		if ( !Request::get('Message') )
		{
			switch ( Request::get('action') )
			{
				case 'order':
					return $this->order();
					
				case 'custom':
					return $this->custom();
			}
		}
		return $this->outputJSON( array('result' => 0) );
	}
	
	
}
