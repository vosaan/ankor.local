<?

/*

{INSTALL:SQL{
create table products_type(
	Id int not null auto_increment,
	Name varchar(100) not null,

  	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Product Type model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Product_Type extends Object
{

	public $Id;
	public $Name;
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
		return 'products_type';
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
	 * The function returns all types.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array.
	 * @param mixed $populatedOnly If TRUE returns only populated 
	 * @return array The Types.
	 */
	public static function getTypes( $assoc = false, $populatedOnly = false )
	{
		$Type = new self();
		$result = array();
		$params = array();
		if ( $populatedOnly === true )
		{
			$params[] = '* Id in (select distinct TypeId from products)';
		}
		else if ( $populatedOnly instanceof Product_Category )
		{
			$params[] = '* Id in (select distinct TypeId from products where CategoryId = '.$populatedOnly->Id.')';
		}
		$arr = $Type->findList( $params, 'Position asc' );
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray( $arr, 'Id', 'Name' );
	}
	
}
