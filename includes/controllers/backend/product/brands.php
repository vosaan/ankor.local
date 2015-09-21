<?

/**
 * The Product Brands controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Product_Brands extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Product_Brand';
	}
	
	/**
	 * @see parent::getAliasName()
	 */
	protected function getAliasName()
	{
		return 'Brand';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Бренды';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Brand = new Product_Brand();
		$this->getView()->set( 'Brands', $Brand->findShortList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
}
