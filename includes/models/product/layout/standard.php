<?

/**
 * The Standard product layout.
 * 
 * @author Yarick
 * @version 0.1
 */
class Product_Layout_Standard extends Product_Layout
{

	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Поликарбонат';
	}
	
	/**
	 * @see parent::getCssName()
	 */
	public function getCssName()
	{
		return 'poly';
	}
	
}