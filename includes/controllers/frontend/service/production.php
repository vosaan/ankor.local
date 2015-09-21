<?

/**
 * The Production service controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Service_Production extends Controller_Frontend_Service
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Услуги - Производство';
	}
	
	/**
	 * @see parent::getImages()
	 */
	protected function getImages()
	{
		$Item = new Gallery_Item();
		$params = array();
		$params[] = $Item->getParam('type', Gallery::GALLERY);
		return $Item->findList( $params, 'PostedAt desc', 0, 10 );
	}
	
	/**
	 * The service index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$this->getView()->setMethod('page');
		return $this->getView()->render();
	}
	
}
