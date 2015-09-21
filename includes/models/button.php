<?

/*

{INSTALL:SQL{
create table buttons(
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
 * The Button model.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Button extends Banner
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
		return 'buttons';
	}
	
}
