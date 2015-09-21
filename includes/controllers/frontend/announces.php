<?

/**
 * The Announces controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Announces extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Вакансии';
	}
	
	/**
	 * The function returns Announce type.
	 * 
	 * @access public
	 * @return int The Announce type.
	 */
	public function getAnnounceType()
	{
		return Announce::VACANCY;
	}
	
	/**
	 * @see parent::isPageInMenu()
	 */
	public function isPageInMenu( Content_Page $Page )
	{
		$Announce = new Announce();
		return $Announce->findSize( array( 'Type = '.$this->getAnnounceType() ) ) > 0;
	}
	
	/**
	 * The announces index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Announce = new Announce();
		$this->getView()->set( 'Announces', $Announce->findList( array( 'Type = '.$this->getAnnounceType() ), 'PostedAt desc, Id desc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * The resume application handler.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function app()
	{
		$response = array('result' => 0, 'posted' => 0);
		if ( Request::get('Name') && Request::get('Email') && Request::get('Phone') && !Request::get('Message') )
		{
			$response['posted'] = 1;
			$Resume = new Resume();
			$Resume->setPost( $_POST );
			$fields = Error::test( $Resume );
			if ( count( $fields ) )
			{
				$response['msg'] = 'Неверно заполнены поля: '.implode( ', ', $fields );
			}
			else
			{
				$Resume->Id = rand(1, 1000000);
				if ( !empty( $_FILES['file']['tmp_name'] ) )
				{
					File::upload( $Resume , $_FILES['file'] );
				}
				$Email = new Email_Resume( $Resume );
				if ( $Email->send() )
				{
					$response['result'] = 1;
				}
				else
				{
					$response['msg'] = 'Ошибка отправки e-mail';
				}
				File::detach( $Resume );
			}
		}
		return $this->outputJSON( $response );
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
	
}
