<?

/**
 * The Certificates controller class.
 * 
 * @author Slava
 * @version 0.1
 */
class Controller_Backend_Certificates extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Award';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Сертификаты';
	}
        
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Award = new Award();
		$params = array();
		$params[] = 'Type = '.intval( Request::get('t') );
		$this->getView()->set( 'Awards', $Award->findList( $params, 'Position asc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * @see parent::add()
	 */
	public function add()
	{
		$Award = $this->getModel();
		$Award->Type = intval( Request::get('t') );
		return $this->initForm( $Award );
	}
	
	/**
	 * The upload form handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function upload()
	{
		$error = array();
		if ( isset( $_POST['submit'] ) )
		{
			if ( isset( $_FILES['file'] ) )
			{
				foreach ( $_FILES['file']['name'] as $id => $value )
				{
					$file = array(
						'name'	=> $_FILES['file']['name'][ $id ],
						'type'	=> $_FILES['file']['type'][ $id ],
						'tmp_name'	=> $_FILES['file']['tmp_name'][ $id ],
						'error'	=> $_FILES['file']['error'][ $id ],
						'size'	=> $_FILES['file']['size'][ $id ],
					);
					if ( $file['tmp_name'] )
					{
						$Award = new Award();
						$Award->Type = intval( Request::get('t') );
						if ( $Award->save() )
						{
							if ( File::upload( $Award, $file ) )
							{
								$Award->save();
							}
						}
					}
				}
			}
			return $this->halt('', true);
		}
		$this->getView()->set( 'Error', $error );
		return $this->getView()->render();
	}
	
}