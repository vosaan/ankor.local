<?

/*

{INSTALL:SQL{
create table products_material(
	Id int not null auto_increment,
	Name varchar(100) not null,

	primary key (Id),
	index (Name)
) engine = MyISAM;

}}
*/

/**
 * The Product Material model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Product_Material extends Object
{

	public $Id;
	public $Name;

	/**
	 * @see parent::getPrimary()
	 */
	protected function getPrimary()
	{
		return array('Id');
	}
	
	/**
	 * @see parent::getTableName()
	 */
	protected function getTableName()
	{
		return 'products_material';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'			=> '/\S{2,}/',
		);
	}
	
	/**
	 * The function returns all materials.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array.
	 * @param mixed $populatedOnly If TRUE returns only populated 
	 * @return array The Materials.
	 */
	public static function getMaterials( $assoc = false, $populatedOnly = false )
	{
		$Material = new self();
		$result = array();
		$params = array();
		if ( $populatedOnly === true )
		{
			$params[] = '* Id in (select distinct MaterialId from products)';
		}
		else if ( $populatedOnly instanceof Product_Category )
		{
			$params[] = '* Id in (select distinct MaterialId from products where CategoryId = '.$populatedOnly->Id.')';
		}
		$arr = $Material->findList( $params, 'Name asc' );
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray( $arr, 'Id', 'Name' );
	}
	
}
