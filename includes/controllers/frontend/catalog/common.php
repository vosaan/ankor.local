<?

/**
 * The Product catalog controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Catalog_Common extends Controller_Frontend_Catalog
{
	/**
	 * @see parent::getModel()
	 */
	public function getModel()
	{
		return new Product_Layout_Common();
	}
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Каталог - Разное';
	}
	
}
