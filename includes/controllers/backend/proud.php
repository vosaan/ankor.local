<?

/**
 * The Proud controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Proud extends Controller_Backend
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Proud';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Продукция';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		return $this->getView()->render();
	}
	
}
