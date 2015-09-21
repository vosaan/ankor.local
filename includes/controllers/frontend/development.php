<?

/**
 * The Development controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Development extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Производство';
	}
	
	/**
	 * The development index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Gallery = new Gallery();
		$Award = new Award();
		
		$this->getView()->set( 'Gallery', $Gallery->findItem( array( 'Type = '.Gallery::DEVELOPMENT ) ) );
		$this->getView()->set( 'Patents', $Award->findList( array( 'Type = '.Award::PATENT ), 'Position asc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * The function returns short HTML block for current page.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function htmlShort()
	{
		$Page = new Content_Page();
		$Page = $Page->findItem( array( 'Module = Controller_Frontend_Development' ) );
		$Gallery = new Gallery();
		$Gallery = $Gallery->findItem( array( 'Type = '.Gallery::DEVELOPMENT ) );
		return $this->getView()->htmlShort( $Page, $Gallery );
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
	
}
