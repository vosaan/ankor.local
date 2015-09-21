<?

/**
 * The Announces controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Announces extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Announce';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Вакансии';
	}
	
	/**
	 * The function returns Announce type for current controller.
	 * 
	 * @access public
	 * @return int The Announce type.
	 */
	public function getAnnounceType()
	{
		return Announce::VACANCY;
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Announce = new Announce();
		$this->getView()->set( 'Announces', $Announce->findList( array( 'Type = '.$this->getAnnounceType() ), 'PostedAt desc, Id desc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * @see parent::add()
	 */
	public function add()
	{
		$Announce = $this->getModel();
		$Announce->Type = $this->getAnnounceType();
		return $this->initForm( $Announce );
	}

	/**
	 * @see parent::pos()
	 */
	public function pos()
	{
		return null;
	}
	
}
