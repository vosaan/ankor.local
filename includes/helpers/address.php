<?

/**
 * The Address helper class.
 * 
 * @author Yarick.
 * @version 0.2
 */
class Address
{
	
	public $Street;
	public $Zip;
	public $City;
	public $Province;
	public $Country;
	
	public $Name;
	public $Email;
	public $Phone;
	public $Fax;
	public $Comment;
	
	public $Company;
	
	/**
	 * The class constructor.
	 * 
	 * @access public
	 * @param array $data The address data.
	 */
	public function __construct( array $data = array() )
	{
		foreach ( $data as $key => $value )
		{
			if ( property_exists( $this, $key ) )
			{
				$this->$key = $value;
			}
		}
	}
	
}
