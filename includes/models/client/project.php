<?

/**
 * The Client Project model.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Client_Project
{
	
	public $Name;
	public $URL;
	
	public function __construct( $name = null, $url = null )
	{
		$this->Name = $name;
		$this->URL = $url;
	}
	
	/**
	 * The function returns correct URL with http:// protocol.
	 * 
	 * @access public
	 * @return string The URL.
	 */
	public function getURL()
	{
		if ( !preg_match( '/^(http|https|ssl|ftp):\/\//', $this->URL ) )
		{
			return 'http://'.$this->URL;
		}
		return $this->URL;
	}
	
}
