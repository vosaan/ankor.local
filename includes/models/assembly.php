<?

/*

{INSTALL:SQL{
create table assemblies(
	Id int not null auto_increment,
	Name varchar(200) not null,
	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;
}}
*/

/**
 * The Assembly model.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Assembly extends Object
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
		return 'assemblies';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'		=> '/\S{2,}/',
		);
	}
	
	/**
	 * @see parent::save()
	 */
	public function saveNew()
	{
		$this->Position = intval( self::getLast( $this, 'Position' ) ) + 1;
		return parent::saveNew();
	}
	
	/**
	 * The function returns all assemblies.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array.
	 * @return array The assemblies.
	 */
	public static function getAssemblies( $assoc = false )
	{
		$Assembly = new self();
		$result = array();
		$arr = $Assembly->findList( array(), 'Position asc' );
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray( $arr, 'Id', 'Name' );
	}
	
}
