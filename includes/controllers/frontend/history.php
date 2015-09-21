<?

/**
 * The History controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_History extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'История';
	}
	
	/**
	 * The history index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
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
		$Page = $Page->findItem( array( 'Module = Controller_Frontend_History' ) );
		return $this->getView()->htmlShort( $Page );
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
}
