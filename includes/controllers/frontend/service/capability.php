<?

/**
 * The Capability service controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Service_Capability extends Controller_Frontend_Service
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Услуги - Возможности';
	}
	
	/**
	 * @see parent::getImages()
	 */
	protected function getImages( $limit = 10 )
	{
		$Item = new Gallery_Item();
		$params = array();
		$params[] = $Item->getParam('type', Gallery::CAPABILITY);
		return $Item->findList( $params, 'Position asc', 0, $limit );
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
		$this->getView()->set('Images', $this->getImages(null));
		return $this->getView()->render();
	}
	
}
