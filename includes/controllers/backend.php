<?

/**
 * The Backend controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend extends Controller_Base
{
	
	/**
	 * @see parent::getUser()
	 */
	public function getUser()
	{
		return Runtime::get('SECURITY_USER');
	}
	
	/**
	 * @see parent::isAccess()
	 */
	public function isAccess( $method = null )
	{
		if ( $method == 'login' )
		{
			return true;
		}
		return parent::isAccess( $method );
	}
	
	/**
	 * The function returns controller title.
	 * 
	 * @access public
	 * @return string The title.
	 */
	public function getTitle()
	{
		return 'Анкор - Панель управления сайтом';
	}
	
	/**
	 * @see parent::isAccess()
	 */
	public function noAccess()
	{
		$this->halt('login');
	}
	
	/**
	 * @see parent::isAccess()
	 */
	public function noMethod()
	{
		echo "Backend::nomethod\n";
	}
	
	/**
	 * The backend index hanlder.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Item = new Gallery_Item();
		$Product = new Product();
		$Article = new Article();
		
		$this->getView()->set( array(
			'Articles'	=> $Article->findShortList( array('Type = '.Article::ARTICLE), 'PostedAt desc', 0, 5 ),
			'News'		=> $Article->findShortList( array('Type = '.Article::NEWS), 'PostedAt desc', 0, 5 ),
			'Gallery'	=> $Item->findList( array(), 'PostedAt desc, Id desc', 0, 10 ),
			'Products'	=> $Product->findShortList( array(), 'Id desc', 0, 10 ),
			'Updated'	=> $Product->findShortList( array(), 'UpdatedAt desc', 0, 10 ),
		) );
		return $this->getView()->render();
	}

	/**
	 * The login page handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function login()
	{
		$this->getView()->set( 'Error', null );
		if ( Request::get('login') && Request::get('password') )
		{
			$Admin = new Admin();
			if ( $Admin->login( Request::get('login'), Request::get('password') ) !== false )
			{
				$this->halt();
			}
			else
			{
				$this->getView()->set( 'Error', 'Wrong password' );
			}
		}
		return $this->getView()->render();
	}
	
	/**
	 * The authorization ping handler.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function auth()
	{
		$response = array( 'result' => 1 );
		return $this->outputJSON( $response );
	}
	
	public function logout()
	{
		$Admin = $this->getUser();
		if ( $Admin && $Admin->Id )
		{
			$Admin->logout();
		}
		$this->halt('login');
	}

	
}
