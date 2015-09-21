<?

/**
 * The Hardware product layout.
 * 
 * @author Yarick
 * @version 0.1
 */
class Product_Layout_Hardware extends Product_Layout
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Комплектующие';
	}
	
	/**
	 * @see parent::getCssName()
	 */
	public function getCssName()
	{
		return 'complect';
	}
	
}