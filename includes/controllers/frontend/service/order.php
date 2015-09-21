<?

/**
 * The Order service controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Service_Order extends Controller_Frontend_Service
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Услуги - Комплектация заказа';
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
