<?

/**
 * The Order Email class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Email_Order extends Email
{
	
	/**
	 * @see parent::getFrom()
	 */
	protected function getFrom()
	{
		if ( Error::check( $this->getObject()->getAddress()->Email, 'email' ) )
		{
			return $this->getObject()->getAddress()->Email;
		}
		return Config::get('email@from');
	}
	
	/**
	 * @see parent::getTo()
	 */
	protected function getTo()
	{
		return Config::get('email@order/to');
	}
	
	/**
	 * @see parent::getTo()
	 */
	protected function getSubject()
	{
		return 'Заказ с сайта #'.$this->getObject()->id();
	}
	
	/**
	 * @see parent::getMessage()
	 */
	protected function getMessage()
	{
		URL::absolute(true);
		$Order = $this->getObject();
		$layout = 'standard';
		if ( $Order->Type == Order::CUSTOM )
		{
			$layout = 'custom';
		}
		else if ( $Order->Type == Order::PRODUCT )
		{
			$layout = 'product';
		}
		$html = $this->includeLayout('order.'.$layout.'.html');
		URL::absolute(false);
		return $html;
	}
	
	/**
	 * @see parent::send()
	 */
	public function send()
	{
		if ( !empty( $_FILES['file']['tmp_name'] ) )
		{
			$this->attach( $_FILES['file']['tmp_name'], $_FILES['file']['name'] );
		}
		return parent::send();
	}
	
}
