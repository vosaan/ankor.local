<?

/**
 * The Subscribers controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Subscribers extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Subscription';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Подписчики';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Subscription = new Subscription();
		$this->getView()->set( 'Subscriptions', $Subscription->findList( array(), 'Email asc' ) );
		return $this->getView()->render();
	}
	
}
