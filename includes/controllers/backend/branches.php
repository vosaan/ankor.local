<?

/**
 * The Branches controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Branches extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Branch';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Филиалы';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Branch = new Branch();
		$this->getView()->set( 'Branches', $Branch->findList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
}
