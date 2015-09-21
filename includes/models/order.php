<?

/*
{INSTALL:SQL{
create table orders(
	Id int not null auto_increment,
	Ip int not null,
	Type tinyint not null,
	Address text not null,
	Shipping text not null,
	BranchId int not null,
	Pickup tinyint not null,

	CustomData text not null,
	
	Status tinyint not null,
	Total float(15,2) not null,
	PostedAt int not null,

	Filename varchar(100) not null,
	IsFile tinyint not null,

	primary key (Id),
	index (Status),
	index (Type),
	index (Total),
	index (PostedAt)
) engine = MyISAM;
}}
*/

/**
 * The Order model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Order extends Object
{
	
	const POSTED	= 0;
	const PENDING	= 1;
	const PROCESS	= 2;
	const SHIPPED	= 10;
	
	const STANDARD	= 0;
	const PRODUCT	= 1;
	const CUSTOM	= 2;
	
	private $rows = array();
	private $rowsChanged = false;
	
	
	public $Id;
	protected $Ip;
	protected $Address;
	public $Shipping;
	public $BranchId;
	public $Pickup;
	public $Status;
	public $Total;
	public $Type;
	protected $CustomData;
	public $Filename;
	public $IsFile;
	
	public $PostedAt;
	
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
		return 'orders';
	}
	
	/**
	 * @see parent::__construct()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->Ip = ip2long( Request::get( 'REMOTE_ADDR', null, 'SERVER' ) );
		$this->Status = self::POSTED;
	}
	
	/**
	 * @see parent::getUploadFileInfo()
	 */
	public function getUploadFileInfo()
	{
		return array(
			'allow'		=> array('pdf', 'doc', 'docx', 'rtf', 'txt'),
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
		if ( parent::save() )
		{
			if ( $this->rowsChanged )
			{
				foreach ( $this->getRows() as $Row )
				{
					$Row->OrderId = $this->Id;
					$Row->save();
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * @see parent::drop()
	 */
	public function drop()
	{
		if ( parent::drop() )
		{
			$Row = new Order_Row( $this );
			$Row->dropList( array( 'OrderId = '.$this->Id ) );
			return true;
		}
		return false;
	}
	
	/**
	 * @see parent::setPost()
	 */
	public function setPost( array $data = array() )
	{
		parent::setPost( $data );
		if ( isset( $data['Address'] ) && is_array( $data['Address'] ) )
		{
			$this->setAddress( $data['Address'] );
		}
		$this->Pickup = empty( $data['Pickup'] ) ? 0 : 1;
		
		if ( $this->Type != self::STANDARD )
		{
			$Custom = new Order_Custom();
			$Custom->set( $data );
			$this->CustomData = $Custom;
		}
	}
	
	/**
	 * The function sets Order Address.
	 * 
	 * @access public
	 * @param mixed $data The Address object or address data array.
	 */
	public function setAddress( $data )
	{
		if ( !is_a( $data, 'Address' ) )
		{
			$data = new Address( $data );
		}
		$this->Address = serialize( $data );
	}
	
	/**
	 * The function returns Order Address object.
	 * 
	 * @access public
	 * @return object The Address.
	 */
	public function getAddress()
	{
		$Address = @unserialize( $this->Address );
		if ( !is_a( $Address, 'Address' ) )
		{
			$Address = new Address();
		}
		return $Address;
	}
	
	/**
	 * The function updates Order total value.
	 * 
	 * @access protected
	 * @return float The new total value.
	 */
	protected function refreshTotal()
	{
		$this->Total = 0;
		foreach ( $this->getRows() as $Row )
		{
			$this->Total += $Row->getAmount();
		}
		return $this->Total;
	}
	
	/**
	 * The function returns total amount includes shipping costs.
	 * 
	 * @access public
	 * @return float The total amount.
	 */
	public function getGrandTotal()
	{
		return $this->Total;
	}
	
	/**
	 * The function clears rows from Order.
	 * 
	 * @access public
	 */
	public function clearRows()
	{
		$this->rows = array();
		$this->rowsChanged = true;
	}
	
	/**
	 * The function adds Row to current Order.
	 * 
	 * @access public
	 * @param mixed $Item The Order Row object or Cart Item object.
	 * @param bool $silent If TRUE flag rowsChanged stays the same, otherwise flag changes to TRUE.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function addRow( $Item, $silent = false )
	{
		$Row = new Order_Row( $this );
		if ( is_a( $Item, 'Cart_Item' ) )
		{
			$Row->ProductId	= $Item->getProduct()->Id;
			$Row->Price		= $Item->getProduct()->Price;
			$Row->Quantity	= $Item->Quantity;
		}
		else if ( is_a( $Item, 'Order_Row' ) )
		{
			$Row = $Item;
		}
		else
		{
			return false;
		}
		$this->rows[] = $Row;
		if ( !$silent )
		{
			$this->rowsChanged = true;
			$this->refreshTotal();
		}
		return true;
	}
	
	/**
	 * The function returns Order Rows.
	 * 
	 * @access public
	 * @return array The array of Rows.
	 */
	public function getRows()
	{
		if ( !$this->rowsChanged && !count( $this->rows ) )
		{
			$Row = new Order_Row( $this );
			foreach ( $Row->findList( array( 'OrderId = '.$this->Id ), 'Id asc' ) as $Row )
			{
				$this->addRow( $Row, true );
			}
		}
		return $this->rows;
	}
	
	/**
	 * The function returns Order id string representation.
	 * 
	 * @access public
	 * @return string The Id.
	 */
	public function id()
	{
		return sprintf( '%05d', $this->Id );
	}
	
	/**
	 * The function returns Order posted date.
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
	 * The function returns IP address of customer.
	 * 
	 * @access public
	 * @return string The IP address.
	 */
	public function getIP()
	{
		return long2ip( $this->Ip );
	}
	
	/**
	 * The function returns months of all orders timelime.
	 * 
	 * @access public
	 * @return array The months.
	 */
	public function getMonths()
	{
		$result = array();
		$query = 'select from_unixtime(PostedAt, "%Y%m") as `date`, from_unixtime(PostedAt, "%M %Y") as `month`, '
			.' count(Id) as `count`, sum(Total) as `total` from '
			.$this->db()->map( $this->getTableName() ).' group by `date` order by `date` desc';
		$arr = $this->db()->query( $query );
		foreach ( $arr as $item )
		{
			$result[ $item['date'] ] = Date::formatMonth( $item['month'] );
		}
		return $result;
	}
	
	/**
	 * The function returns select Branch for current Order.
	 * 
	 * @access public
	 * @return object The Branch.
	 */
	public function getBranch()
	{
		$Branch = new Branch();
		return $Branch->findItem( array( 'Id = '.$this->BranchId ) );
	}
	
	/**
	 * The function returns Custom order.
	 * 
	 * @access public
	 * @return object The Custom order.
	 */
	public function getCustom()
	{
		if ( $this->CustomData instanceof Order_Custom )
		{
		}
		else if ( $this->CustomData )
		{
			$this->CustomData = @unserialize( $this->CustomData );
		}
		else
		{
			$this->CustomData = new Order_Custom();
		}
		return $this->CustomData;
	}
	
}
