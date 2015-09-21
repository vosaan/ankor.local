<?

/**
 * The Dealers controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Dealers extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Dealer';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Дилеры';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Dealer = new Dealer();
		$this->getView()->set( 'Dealers', $Dealer->findList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
}
