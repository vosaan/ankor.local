<?

/**
 * The About company controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_About extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Компания';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Development = new Controller_Frontend_Development();
		$Development->setView( new View_Frontend_Development() );
		$History = new Controller_Frontend_History();
		$History->setView( new View_Frontend_History() );
		$Clients = new Controller_Frontend_Clients();
		$Clients->setView( new View_Frontend_Clients() );
		$Papers = new Controller_Frontend_Certificates();
		$Papers->setView( new View_Frontend_Certificates() );
		
		$this->getView()->set( 'Development', $Development );
		$this->getView()->set( 'History', $History );
		$this->getView()->set( 'Clients', $Clients );
		$this->getView()->set( 'Papers', $Papers );
		return $this->getView()->render();
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
}
