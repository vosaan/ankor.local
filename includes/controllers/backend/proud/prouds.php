<?

/**
 * The Prouds controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Proud_Prouds extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName( $method = null )
	{
		if ( $method == 'deli' || $method == 'posi' )
		{
			return 'Proud_Image';
		}
		if ( $method == 'dele' || $method == 'pose' )
		{
			return 'Proud_Example';
		}
		/*if ( $method == 'deld' )
		{
			return 'Proud_Document';
		}*/
		return 'Proud';
	}
	
	/**
	 * @see parent::getAliasName()
	 */
	protected function getAliasName( $method = null )
	{
		if ( $method == 'deli' || $method == 'posi' )
		{
			return 'Image';
		}
		if ( $method == 'dele' || $method == 'pose' )
		{
			return 'Example';
		}
		/*if ( $method == 'deld' )
		{
			return 'Document';
		}*/
		return 'Proud';
	}
	
	/**
	 * @see parent::dropOnFailedUpload();
	 */
	protected function dropOnFailedUpload( Object $Object )
	{
		if ( $Object instanceof Proud_Document || $Object instanceof Proud_Image || $Object instanceof Proud_Example )
		{
			return true;
		}
		return parent::dropOnFailedUpload( $Object );
	}
	
	/**
	 * @see parent::haltForm()
	 */
	protected function haltForm( Object $Object, $method = 'edit' )
	{
		if ( $method == 'add' )
		{
			return $this->halt('edit/'.$Object->Id, true);
		}
		return parent::haltForm($Object, $method);
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Работы';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Proud = new Proud();
		/*$Category = new Client();
		foreach ( $Category->findList( array(), 'Position asc', 0, 1 ) as $Category );
		
		$params = array();
		$c = Request::get('c', $Category->Id);
		$params[] = 'CategoryId = '.$c;
		$_GET['c'] = $c;
 		$this->getView()->set( 'Prouds', $Proud->findList( $params, 'Position asc' ) );       */        
                

                $this->getView()->set( 'Prouds', $Proud->findList( '', 'Position desc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * @see parent::add()
	 */
	public function add()
	{
		$Proud = new Proud();
		$Proud->CategoryId = intval( Request::get('c') );
		return $this->initForm( $Proud, 'add' );
	}
	
	/**
	 * The upload handler.
	 * 
	 * @param int $id The Proud id.
	 * @return string The HTML code.
	 */
	public function upload( $id = null )
	{
		$Proud = new Proud();
		$Proud = $Proud->findItem( array( 'Id = '.$id ) );
		if ( isset( $_POST['upload'] ) )
		{
			$files = array(
				'image' => new Proud_Image(), 
				'example' => new Proud_Example() /* 
				'doc' => new Proud_Document() */
			);
			foreach ( $files as $id => $class )
			{
				if ( isset( $_FILES[ $id ] ) )
				{
					foreach ( $_FILES[ $id ]['name'] as $i => $value )
					{
						$file = File::convertMultiple( $_FILES[ $id ], $i );
						if ( empty( $file['tmp_name'] ) )
						{
							continue;
						}
						$class->Id = null;
						$class->ProudId = $Proud->Id;
						if ( $class->save() )
						{
							if ( File::upload( $class, $file ) )
							{
								$class->save();
							}
							else if ( $this->dropOnFailedUpload( $class ) )
							{
								$class->drop();
							}
						}
					}
				}
			}
		}
		return $this->getView()->htmlUpload( $Proud );
	}
	
	/**
	 * The delete image handler.
	 * 
	 * @access public
	 * @param int $id The Image id.
	 * @return string The JSON response.
	 */
	public function deli( $id = null )
	{
		return $this->rawDelete( 'deli', $id );
	}
	
	/**
	 * The delete example handler.
	 * 
	 * @access public
	 * @param int $id The Image id.
	 * @return string The JSON response.
	 */
	public function dele( $id = null )
	{
		return $this->rawDelete( 'dele', $id );
	}
	
	/**
	 * The delete document handler.
	 * 
	 * @access public
	 * @param int $id The Image id.
	 * @return string The JSON response.
	 */
	public function deld( $id = null )
	{
		return $this->rawDelete( 'deld', $id );
	}
	
	/**
	 * The order position handler for product images.
	 * 
	 * @access public
	 * @return string THe JSON response.
	 */
	public function posi()
	{
		return $this->rawPos('posi');
	}
	
	/**
	 * The order position handler for product examples.
	 * 
	 * @access public
	 * @return string THe JSON response.
	 */
	public function pose()
	{
		return $this->rawPos('pose');
	}
	
}
