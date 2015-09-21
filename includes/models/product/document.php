<?

/*

{INSTALL:SQL{
create table products_document(
	Id int not null auto_increment,
	ProductId int not null,
	Name varchar(100) not null,
 
 	Filename varchar(100) not null,
	Filesize int not null,
	IsFile tinyint not null,
  	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Product Document model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Product_Document extends Object
{

	public $Id;
	public $ProductId;
	public $Name;
	public $Filesize;
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
		return 'products_document';
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
			'allow'			=> array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'),
			'folderFormat'	=> '%05d',
			'folderLimit'	=> 1000,
			'urlFormat'		=> true,
		);
	}
	
	/**
	 * @see parent::getFileUrl()
	 */
	public function getFileUrl( $class, $folder, $index, $ext )
	{
		$arr = array( '', $this->Filename );
		preg_match( '/(.+)\.(.+)$/', $this->Filename, $arr );
		return '/downloads/docp/'.$folder.'/'.$index.'/'.$this->Id.'/'.preg_replace( '/\s/', '-', trim( $arr[1] ) ).'.'.$ext;
	}
	
	/**
	 * @see parent::save()
	 */
	public function save()
	{
		if ( !$this->Position )
		{
			$this->Position = intval( self::getLast( $this, 'Position', array( 'ProductId = '.$this->ProductId ) ) ) + 1;
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
