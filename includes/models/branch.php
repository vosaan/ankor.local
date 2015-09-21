<?

/*

{INSTALL:SQL{
create table branches(
	Id int not null auto_increment,
	Name varchar(100) not null,
 
  	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Branch model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Branch extends Object
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
		return 'branches';
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
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		$this->Position = intval( self::getLast( $this, 'Position' ) ) + 1;
		return parent::saveNew();
	}
	
	/**
	 * The function returns all branches.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array.
	 * @return array The Brands.
	 */
	public static function getBranches( $assoc = false )
	{
		$Branch = new self();
		$arr = $Branch->findList( array(), 'Position asc' );
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray( $arr, 'Id', 'Name' );
	}
	
}
