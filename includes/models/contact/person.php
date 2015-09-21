<?

/*

{INSTALL:SQL{
create table contact_persons(
	Id int not null auto_increment,
	Name varchar(100) not null,
	Post varchar(100) not null,
	Phone varchar(100) not null,
	Email varchar(100) not null,

	Filename varchar(200) not null,
	IsFile tinyint not null,

	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Contact_Person model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Contact_Person extends Object
{

	public $Id;
	public $Name;
	public $Post;
	public $Phone;
	public $Email;
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
		return 'contact_persons';
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
			'allow'			=> array( 'gif', 'jpg', 'jpeg', 'png' ),
			'extension'		=> 'jpg',

			'sizes'			=> array( '80x80' ),
			'quality'		=> array( 100 ),
			'crop'			=> array( 'Center' ),
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
	
}
