<?

/**
 * The Shopping Cart Item class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Cart_Item
{
	
	public $Product;
	public $Quantity;
	
	private $cachedProduct = null;
	
	/**
	 * The class constructor.
	 * 
	 * @access public
	 * @param mixed $Product The Product object or its ID.
	 * @param int $Quantity The quantity.
	 */
	public function __construct( $Product = null, $Quantity = 1 )
	{
		if ( $Product instanceof Product_Unit )
		{
			$this->cachedProduct = $Product;
			$this->Product = $Product->Id;
		}
		else
		{
			$this->Product = intval( $Product );
		}
		$this->Quantity = $Quantity;
	}
	
	/**
	 * The function returns TRUE if current Item equals to $Item.
	 * 
	 * @access public
	 * @return bool TRUE on equals, otherwise FALSE.
	 */
	public function equals( Cart_Item $Item )
	{
		return $this == $Item;
	}
	
	/**
	 * The function returns Product object for current Item.
	 * 
	 * @access public
	 * @return object The Product object.
	 */
	public function getProduct()
	{
		if ( $this->cachedProduct === null )
		{
			$Product = new Product_Unit();
			$this->cachedProduct = $Product->findItem( array( 'Id = '.$this->Product ) );
		}
		return $this->cachedProduct;
	}
	
	/**
	 * The function returns current Item Product price.
	 * 
	 * @access public
	 * @return float The price.
	 */
	public function getPrice()
	{
		return $this->getProduct()->Price;
	}
	
	/**
	 * The function returns price amount for current Item.
	 * 
	 * @access public
	 * @return float The price amount.
	 */
	public function getAmount()
	{
		return $this->Quantity * $this->getPrice();		
	}	
	
}
