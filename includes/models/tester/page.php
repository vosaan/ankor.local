<?

/**
 * The Page tester class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Tester_Page
{

	public $Id;
	public $Name;
	public $Link;
	public $Conflict;
	
	private $Type;
	
	const PRODUCT = 1;
	const PAGE = 2;
	
	
	public function __construct( Object $Object = null )
	{
		if ( $Object !== null )
		{
			$this->set( $Object );
			$this->check();
		}
	}

	public function set( Object $Object )
	{
		$this->Id = $Object->Id;
		$this->Name = $Object->Name;
		if ( $Object instanceof Product )
		{
			$this->Link = $Object->Slug;
			$this->Type = self::PRODUCT;
		}
		else if ( $Object instanceof Content_Page )
		{
			$this->Link = $Object->Link;
			$this->Type = self::PAGE;
		}
	}
	
	public function check()
	{
		if ( $this->Type == self::PRODUCT )
		{
			$Page = new Content_Page();
			$this->Conflict = $Page->findSize( array( 'Link = /'.$this->Link ) ) > 0;
		}
		else if ( $this->Type == self::PAGE )
		{
			$Product = new Product();
			$this->Conflict = $Product->findSize( array( 'Slug = '.ltrim( $this->Link, '/' ) ) ) > 0;
		}
	}
	
	public function getKey()
	{
		return sprintf( '%s-%d-%d', $this->getLink(), $this->Type, $this->Id );
	}
	
	public function getLink()
	{
		return $this->Type == self::PRODUCT ? ('/'.$this->Link) : $this->Link;
	}
	
	public function isProduct()
	{
		return $this->Type == self::PRODUCT;
	}
	
}
