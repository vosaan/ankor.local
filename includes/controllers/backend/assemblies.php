<?

/**
 * The Assemblies controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Assemblies extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Assembly';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Монтаж';
	}
        
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Assembly = new Assembly();
		$params = array();
		$this->getView()->set( 'Assemblies', $Assembly->findList( $params, 'Position asc' ) );
		return $this->getView()->render();
	}
	
}