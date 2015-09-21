<?

/*

{INSTALL:SQL{
create table products_models(
	Id int not null auto_increment,
	ProductId int not null,
	Name varchar(100) not null,
	Width smallint not null,
	Height smallint not null,
	HeightMax smallint not null,
	HeightMin smallint not null,
	SquareX smallint not null,
	SquareY smallint not null,
	Position int not null,

	primary key (Id),
	index (ProductId),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Product Model model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Product_Model extends Object
{

	public $Id;
	public $ProductId;
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
		return 'products_models';
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
			$this->Position = self::getLast( $this, 'Position', array( 'ProductId = '.$this->ProductId ) ) + 1;
		}
		return parent::saveNew();
	}
	
	/**
	 * The function returns Product for current Brand.
	 * 
	 * @access public
	 * @return object The Product.
	 */
	public function getProduct()
	{
		$Product = new Product();
		return $Product->findItem( array( 'Id = '.$this->ProductId ) );
	}
	
}
