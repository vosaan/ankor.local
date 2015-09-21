<?

/*

{INSTALL:SQL{
create table products_color(
	Id int not null auto_increment,
	Name varchar(100) not null,
 
 	Filename varchar(100) not null,
	IsFile tinyint not null,
  	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Product Color model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Product_Color extends Object
{

	public $Id;
	public $Name;
	public $Filename;
	public $IsFile;
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
		return 'products_color';
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
	 * @see parent::getUploadFileInfo()
	 */
	public function getUploadFileInfo()
	{
		return array(
			'allow'			=> array('gif', 'jpg', 'jpeg', 'png'),
			'extension'		=> 'jpg',
			'folderFormat'	=> '%05d',
			'folderLimit'	=> 1000,

			'sizes'			=> array('35x50', '400x435'),
			'quality'		=> array(100, 90),
			'timeAffix'		=> 'UpdatedAt',
			'dropOrig'		=> true,
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
	 * @see parent::drop()
	 */
	public function drop()
	{
		if ( parent::drop() )
		{
			File::detach( $this );
			return true;
		}
		return false;
	}
	
	/**
	 * The function returns all colors.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array.
	 * @return array The Colors.
	 */
	public static function getColors( $assoc = false )
	{
		$Color = new self();
		$result = array();
		$arr = $Color->findList( array(), 'Position asc' );
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray( $arr, 'Id', 'Name' );
	}
	
}
