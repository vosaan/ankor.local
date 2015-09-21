<?

/**
 * The Contact controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Contact extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Контакты';
	}
	
	/**
	 * The function sends Contact email to manager.
	 * 
	 * @access protected
	 * @return string The JSON response.
	 */
	public function send()
	{
		$response = array( 'result' => 0 );
		if ( Request::get('Name') && !Request::get('Message') )
		{
			$Email = new Email_Contact();
			if ( $Email->send() )
			{
				$response['result'] = 1;
			}
			else
			{
				$response['msg'] = 'Ошибка при отправлении запроса';
			}
		}
		else
		{
			$response['msg'] = 'Заполните все обязательные поля';
		}
		return $this->outputJSON( $response );
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Contact = new Contact();
		$Dealer = new Dealer();
		$this->getView()->set( 'Contacts', $Contact->findList( array(), 'Position asc' ) );
		$this->getView()->set( 'Dealers', $Dealer->findList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
	public function callback()
	{
		$response = array( 'result' => 0 );
		if ( Request::get('Name') && Request::get('Phone') )
		{
			$spam = Request::get('Confirm')? true: false;
			$Email = new Email_Callback($spam);
			if ( $Email->send() )
			{
				$response['result'] = 1;
				$response['msg'] = 'Наш оператор вскоре Вам перезвонит';
				$response['timeout'] = 3000;
				$response['callback'] = 'close';
			}
			else
			{
				$response['msg'] = 'Ошибка при отправлении запроса';
			}
		}
		else
		{
			$response['msg'] = 'Заполните все обязательные поля';
		}
		return $this->outputJSON( $response );
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
	
}
