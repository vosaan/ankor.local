<?

/**
 * The Documents controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Documents extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Файлы и документы';
	}
	
	/**
	 * The documents index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Document = new Document();
		$this->getView()->set( 'Documents', $Document->findList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
}
