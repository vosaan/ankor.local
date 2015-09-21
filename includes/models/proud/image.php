<?

/*

{INSTALL:SQL{
create table products_image(
	Id int not null auto_increment,
	ProudId int not null,

	Filename varchar(200) not null,
	IsFile tinyint not null,

	UpdatedAt int not null,
	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Proud Image model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Proud_Image extends Object
{
	
	public $Id;
	public $ProudId;
	public $Filename;
	public $IsFile;
	public $UpdatedAt;
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
		return 'prouds_image';
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

			'sizes'			=> array('100x75', '1000x600'),
			'quality'		=> array(100, 90),
			'timeAffix'		=> 'UpdatedAt',
			'dropOrig'		=> true,
		);
	}
	
	/**
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		$this->Position = self::getLast( $this, 'Position', array( 'ProudId = '.$this->ProudId ) ) + 1;
		return parent::saveNew();
	}
	
	/**
	 * @see parent::save()
	 */
	public function save()
	{
		$this->UpdatedAt = time();
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
	 * The function returns name of current image.
	 * 
	 * @access public
	 * @return string The name.
	 */
	public function getName()
	{
		$arr = explode( '.', $this->Filename );
		array_pop( $arr );
		return implode( '.', $arr );
	}
	
}
