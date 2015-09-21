<?

/**
 * The Clients controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Clients extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Client';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Клиенты';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Client = new Client();
		$this->getView()->set( 'Clients', $Client->findList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
}
