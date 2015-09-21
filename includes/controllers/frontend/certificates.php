<?

/**
 * The Certificates controller class.
 * 
 * @author Slava
 * @version 0.1
 */
class Controller_Frontend_Certificates extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Сертификаты';
	}
	

	/**
	 * The Certificates index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Awards = new Award();
		$params = array();
		$params[] = 'Type = '.Award::CERTIFICATE;

		$Awards = $Awards->findList( $params, 'Position asc' );
		$this->getView()->set( 'Awards', $Awards );
		return $this->getView()->render();
	}

	/**
	 * The function returns short HTML block for current page.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function htmlShort()
	{
		$Page = new Content_Page();
		$Page = $Page->findItem( array( 'Module = Controller_Frontend_Certificates' ) );
		$Award = new Award();
		$Papers = $Award->findList( array( 'Type = '.Award::CERTIFICATE ), 'Position asc', 0, 4 );
		return $this->getView()->htmlShort( $Page, $Papers );
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
	
}
