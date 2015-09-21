<?

/*

{INSTALL:SQL{
create table products_brand(
	Id int not null auto_increment,
	Name varchar(100) not null,
	Title varchar(100) not null,
	Description text not null,
	Content text not null,
 
  	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Product Brand model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Product_Brand extends Object
{

	public $Id;
	public $Name;
	public $Title;
	public $Description;
	public $Content;
	public $Position;

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
		return 'products_brand';
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
	 * @see parent::save()
	 */
	public function save()
	{
		if ( !$this->Position )
		{
			$this->Position = intval( self::getLast( $this, 'Position' ) ) + 1;
		}
		return parent::save();
	}
	
	/**
	 * @see parent::findList()
	 */
	public function findShortList( array $params = array(), $order = null, $offset = null, $limit = null )
	{
		return $this->findResult( 'Id, Name, Title, Description, Position', $params, $order, $offset, $limit );
	}
	
	/**
	 * The function returns all brands.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array.
	 * @param mixed $populatedOnly If TRUE returns only populated 
	 * @return array The Brands.
	 */
	public static function getBrands( $assoc = false, $populatedOnly = false )
	{
		$Brand = new self();
		$params = array();
		if ( $populatedOnly === true )
		{
			$params[] = '* Id in (select distinct BrandId from products)';
		}
		else if ( $populatedOnly instanceof Product_Category )
		{
			$params[] = '* Id in (select distinct BrandId from products where CategoryId = '.$populatedOnly->Id.')';
		}
		$arr = $Brand->findList( $params, 'Position asc' );
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray( $arr, 'Id', 'Name' );
	}
	
}
