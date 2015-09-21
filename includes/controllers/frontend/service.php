<?

/**
 * The Service controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Service extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Услуги';
	}
	
	/**
	 * @see parent::__construct()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setView( new View_Frontend_Service() );
	}
	
	/**
	 * The function returns Images for current controller for short view.
	 * 
	 * @access protected
	 * @return array The Images.
	 */
	protected function getImages()
	{
		return array();
	}
	
	/**
	 * The function returns short overview for current controller.
	 * 
	 * @access public
	 * @param object $Page The Content Page.
	 * @return string The HTML code.
	 */
	public function htmlShort( Content_Page $Page )
	{
		if ( get_class( $this ) == get_class() )
		{
			return null;
		}
		$Article = new Article();
		$params = array();
		$params[] = 'Type = '.Article::ARTICLE;
		$params[] = 'PostedAt < '.time();
		$params[] = $Article->getParam( 'reference', $Page );
		$Articles = $Article->findShortList( $params, 'PostedAt desc, Id desc' );
		return $this->getView()->htmlShort( $Page, $Articles, $this->getImages() );
	}
	
	/**
	 * The service index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		return $this->getView()->render();
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
	
}
