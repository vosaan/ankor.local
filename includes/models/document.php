<?

/*

{INSTALL:SQL{
create table documents(
	Id int not null auto_increment,
	Name varchar(250) not null,
	Link varchar(150) not null,

	Filename varchar(200) not null,
	Extension char(5) not null,
	IsFile tinyint not null,
	Filesize int not null,

	PostedAt int not null,
	Position int not null,

	primary key (Id),
	index (PostedAt),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Document model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Document extends Object
{
	
	public $Id;
	public $Name;
	public $Link;
	public $Filename;
	public $Extension;
	public $IsFile;
	public $Filesize;
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
		return 'documents';
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
			'allow'		=> array('pdf', 'doc', 'docx', 'rtf', 'txt', 'jpg', 'jpeg', 'gif', 'rar', 'zip', 'eps', 'ai', 'png', 'tif', 'tiff'),
			'urlFormat'	=> true,
		);
	}
	
	/**
	 * @see parent::getFileUrl()
	 */
	public function getFileUrl( $class, $folder, $index, $ext )
	{
		$arr = array( '', $this->Filename );
		preg_match( '/(.+)\.(.+)$/', $this->Filename, $arr );
		return '/downloads/file/'.$this->Id.'/'.preg_replace( '/\s/', '-', trim( $arr[1] ) ).'.'.$ext;
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
	 * @see File::getFilesize()
	 */
	public function getFilesize()
	{
		return File::getFilesize( $this->Filesize );
	}

}
