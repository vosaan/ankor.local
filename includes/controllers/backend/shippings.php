<?

/**
 * The Shippings controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Shippings extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Shipping';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Доставка';
	}
        
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Shipping = new Shipping();
		$params = array();
		$this->getView()->set( 'Shippings', $Shipping->findList( $params, 'Position asc' ) );
		return $this->getView()->render();
	}
	
}