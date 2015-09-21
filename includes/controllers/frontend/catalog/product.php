<?

/**
 * The Product catalog controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Catalog_Product extends Controller_Frontend_Catalog
{

	/**
	 * @see parent::getModel()
	 */
	public function getModel()
	{
		return new Product_Layout_Product();
	}
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Каталог - Изделия';
	}
	
}