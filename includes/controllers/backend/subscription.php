<?

/**
 * The Subscription controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Subscription extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Article_Mailer';
	}
	
	/**
	 * @see parent::getAliasName()
	 */
	protected function getAliasName()
	{
		return 'Mailer';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Рассылка';
	}
	
	public function haltForm( Object $Object, $method = 'edit' )
	{
		if ( $method == 'add' )
		{
			if ( $Object->Id )
			{
				$Object->send();
			}
		}
		return parent::haltForm( $Object, $method );
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Mailer = new Article_Mailer();
		$this->getView()->set( 'Mailers', $Mailer->findList( array(), 'SentAt desc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * The view mailer handler.
	 * 
	 * @access public
	 * @param int $id The Mailer id.
	 * @return string The HTML code.
	 */
	public function view( $id = null )
	{
		$Mailer = new Article_Mailer();
		$Mailer = $Mailer->findItem( array( 'Id = '.$id ) );
		$this->getView()->set( 'Mailer', $Mailer );
		return $this->getView()->render();
	}
	
}
