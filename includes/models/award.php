<?

/*

{INSTALL:SQL{
create table awards(
	Id int not null auto_increment,
	Type tinyint not null,
	Name varchar(250) not null,

	Filename varchar(200) not null,
	IsFile tinyint not null,

	PostedAt int not null,
	Position int not null,

	primary key (Id),
	index (PostedAt),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Award model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Award extends Object
{
	
	const CERTIFICATE	= 0;
	const PATENT		= 1;
	const SUPPORT		= 2;
	
	public $Id;
	public $Type;
	public $Name;
	public $Filename;
	public $IsFile;
	public $PostedAt;
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
		return 'awards';
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

			'sizes'			=> array('132x105', '1200x900'),
			'quality'		=> array(100, 80),
			'timeAffix'		=> 'UpdatedAt',
			'dropOrig'		=> true,
		);
	}
	
	/**
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		if ( !$this->Type )
		{
			$this->Type = self::CERTIFICATE;
		}
		$this->Position = self::getLast( $this, 'Position', array( 'Type = '.$this->Type ) ) + 1;
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
	 * The function returns Award types.
	 * 
	 * @static
	 * @access public
	 * @return array The award types.
	 */
	public static function getTypes()
	{
		return array(
			self::CERTIFICATE	=> 'Сертификат',
			self::PATENT		=> 'Патент',
			self::SUPPORT		=> 'Поддержка',
		);
	}
	
}
