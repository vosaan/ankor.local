<?

/*

{INSTALL:SQL{
create table banners(
	Id int not null auto_increment,
	Name varchar(200) not null,
	Link varchar(150) not null,
	Filename varchar(200) not null,
	IsFile tinyint not null,
	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Banner model.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Banner extends Object
{
	
	public $Id;
	public $Name;
	public $Link;
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
		return 'banners';
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
	 * The function returns target value for html A attribute target.
	 * 
	 * @access public
	 * @return string The target value.
	 */
	public function getTarget()
	{
		return substr( $this->Link, 0, 1 ) == '/' ? '_self' : '_blank';
	}
	
	/**
	 * The function returns current banner URL.
	 * 
	 * @access public
	 * @return string The url.
	 */
	public function getURL()
	{
		if ( substr( $this->Link, 0, 1 ) == '/' )
		{
			return $this->Link;
		}
		if ( preg_match( '/^(http|https|ssl|ftp):\/\//', $this->Link ) )
		{
			return $this->Link;
		}
		return 'http://'.$this->Link;
	}
	
}
