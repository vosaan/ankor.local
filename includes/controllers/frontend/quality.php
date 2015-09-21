<?

/**
 * The Quality controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Quality extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Качество';
	}
	
	/**
	 * The quality index handler.
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
