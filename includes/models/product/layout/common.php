<?

/**
 * The Custom product layout.
 * 
 * @author Yarick
 * @version 0.1
 */
class Product_Layout_Common extends Product_Layout
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Разное';
	}
	
	/**
	 * @see parent::getCssName()
	 */
	public function getCssName()
	{
		return 'common';
	}
	
}