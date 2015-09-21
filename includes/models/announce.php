<?

/*

{INSTALL:SQL{
create table announces(
	Id int not null auto_increment,
	Type tinyint not null,
	Title varchar(150) not null,
	Content text not null,

	PostedAt int not null,

	primary key (Id),
	index (PostedAt)
) engine = MyISAM;

}}
*/

/**
 * The Announce model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Announce extends Object
{
	
	public $Id;
	public $Type;
	public $Title;
	public $Content;
	public $PostedAt;
	
	
	const VACANCY	= 1;
	const PURCHASE	= 2;

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
		return 'announces';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Title'				=> '/\S{2,}/',
			'Content'			=> '/\S{2,}/',
		);
	}
	
	/**
	 * @see parent::save()
	 */
	public function save()
	{
		if ( !$this->PostedAt )
		{
			$this->PostedAt = time();
		}
		return parent::save();
	}

	/**
	 * The function returns Article posted date.
	 * 
	 * @access public
	 * @param bool $short The short format.
	 * @return string The date.
	 */
	public function getDate( $short = false, $time = null )
	{
		if ( !$this->PostedAt )
		{
			$this->PostedAt = $time;
		}
		if ( $short )
		{
			return date( 'd.m.Y', $this->PostedAt );
		}
		return Date::formatMonth( date( 'j F Y', $this->PostedAt ), true );
	}
	
	/**
	 * The function returns count of announces by current type.
	 * 
	 * @static
	 * @access public
	 * @param int $type The announce type.
	 * @return int The count.
	 */
	public static function getCount( $type = null )
	{
		$Announce = new self();
		$params = array();
		if ( $type !== null )
		{
			$params[] = 'Type = '.$type;
		}
		return $Announce->findSize( $params );
	}
	
}
