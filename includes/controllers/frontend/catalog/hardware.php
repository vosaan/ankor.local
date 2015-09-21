<?

/**
 * The Hardware catalog controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Catalog_Hardware extends Controller_Frontend_Catalog
{

	/**
	 * @see parent::getModel()
	 */
	public function getModel()
	{
		return new Product_Layout_Hardware();
	}
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Каталог - Комплектующие';
	}
	
}