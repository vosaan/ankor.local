<?

/**
 * The Polls controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Polls extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Poll';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Голосования';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Poll = new Poll();
		$this->getView()->set( 'Polls', $Poll->findList( array(), 'Id desc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * The status handler.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function status()
	{
		$response = array( 'result' => 0 );
		$Poll = new Poll();
		$Poll = $Poll->findItem( array( 'Id = '.Request::get('id') ) );
		if ( $Poll->Id )
		{
			if ( !$Poll->IsActive )
			{
				foreach ( $Poll->findList( array( 'IsActive = 1' ) ) as $Item )
				{
					$Item->IsActive = 0;
					$Item->save();
				}
			}
			$Poll->IsActive = 1 - $Poll->IsActive;
			if ( $Poll->save() )
			{
				$response['result'] = 1;
				$response['IsActive'] = (bool)$Poll->IsActive;
			}
			else
			{
				$response['msg'] = 'Не могу сохранить данные';
			}
		}
		else
		{
			$response['msg'] = 'Элемент не найден';
		}
		return $this->outputJSON( $response );
	}
	
}
