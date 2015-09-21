<?

/*

{INSTALL:SQL{
create table dealers(
	Id int not null auto_increment,
	City varchar(100) not null,
	Label varchar(100) not null,
	Description varchar(200) not null,
	

	Position int not null,

	primary key (Id),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Contact model class.
 * 
 * @author Slava.
 * @version 0.1
 */
class Dealer extends Object
{

	public $Id;
	public $City;
	public $Label;
	public $Description ;
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
		return 'dealers';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'City'			=> '/\S{2,}/',
			'Label'			=> '/\S{2,}/',
			'Description'	=> '/\S{2,}/',
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
	 * The function returns contact departments.
	 * 
	 * @static
	 * @access public
	 * @param string $field If field is set to Name or Email returns associated array with this field.
	 * @return array The departments.
	 */
	public static function getDepartments( $field = null )
	{
		$arr = Config::get('contact/departs', array());
		if ( $field && isset( $arr[ $field ] ) )
		{
			return $arr[ $field ];
		}
		$result = array();
		foreach ( $arr['Name'] as $i => $value )
		{
			$result[ $i ] = array(
				'Name'	=> $value,
				'Email'	=> $arr['Email'][ $i ],
			);
		}
		return $result;
	}
	
}
