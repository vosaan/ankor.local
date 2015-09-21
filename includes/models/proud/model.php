<?

/*

{INSTALL:SQL{
create table products_models(
	Id int not null auto_increment,
	ProudId int not null,
	Name varchar(100) not null,
	Width smallint not null,
	Height smallint not null,
	HeightMax smallint not null,
	HeightMin smallint not null,
	SquareX smallint not null,
	SquareY smallint not null,
	Position int not null,

	primary key (Id),
	index (ProudId),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Proud Model model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Proud_Model extends Object
{

	public $Id;
	public $ProudId;
	public $Name;
	public $Width;
	public $Height;
	public $HeightMax;
	public $HeightMin;
	public $SquareX;
	public $SquareY;
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
		return 'prouds_models';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'			=> '/\S{2,}/',
			'Unit'			=> '/\S{1,}/',
		);
	}
	
	/**
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		if ( !$this->Position )
		{
			$this->Position = self::getLast( $this, 'Position', array( 'ProudId = '.$this->ProudId ) ) + 1;
		}
		return parent::saveNew();
	}
	
	/**
	 * The function returns Proud for current Brand.
	 * 
	 * @access public
	 * @return object The Proud.
	 */
	public function getProud()
	{
		$Proud = new Proud();
		return $Proud->findItem( array( 'Id = '.$this->ProudId ) );
	}
	
}
