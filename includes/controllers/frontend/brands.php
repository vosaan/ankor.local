<?

/**
 * The Brands controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Brands extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
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
		$this->getView()->set( 'Brands', $Brand->findShortList( array(), 'Name asc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * The view handler.
	 * 
	 * @access public
	 * @param int $id The Brand id.
	 * @return string The HTML code.
	 */
	public function view( $id = null )
	{
		$Brand = new Product_Brand();
		if ( $id instanceof Product_Brand )
		{
			$Brand = $id;
		}
		else
		{
			$Brand = $Brand->findItem( array( 'Id = '.$id ) );
		}
		if ( !$Brand->Id )
		{
			return $this->halt();
		}
		$this->getView()->set( 'Brand', $Brand );
		return $this->getView()->render();
	}
	
	/**
	 * @see parent::noMethod()
	 */
	public function noMethod( $method = null )
	{
		$Brand = new Product_Brand();
		$Brand = $Brand->findItem( array( 'Name = '.urldecode( String::toLinkCase( $method, ' ', '-' ) ) ) );
		if ( $Brand->Id )
		{
			$this->getView()->setMethod('view');
			return $this->view( $Brand );
		}
		return parent::noMethod( $method );
	}
	
}
