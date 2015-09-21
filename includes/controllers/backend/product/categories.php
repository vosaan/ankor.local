<?

/**
 * The Product Categories controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Product_Categories extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Product_Category';
	}
	
	/**
	 * @see parent::getAliasName()
	 */
	protected function getAliasName()
	{
		return 'Category';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Категории';
	}
	
	protected function initForm( Object $Object, $method = 'edit' )
	{
		$error = array();
		if ( isset( $_POST['submit'] ) )
		{
			$Object->setPost( $_POST );
			$fields = Locale::translate( Error::test( $Object ) );
			if ( count( $fields ) )
			{
				$error[] = 'Неверно заполнены поля: '.implode( ', ', Locale::translate( $fields ) );
			} 
			else if ( $Object->save() )
			{				
				return $this->haltForm( $Object, $method );
			}
			else
			{
				$error[] = 'Ошибка базы данных: '.$Object->getError();
			}
		}
		$name = $this->getAliasName( $method ) ? $this->getAliasName( $method ) : $this->getModelName( $method );
		$Document = new Document();
		$this->getView()->set( 'Documents', $Document->findList( array(), 'Position asc' ) );
		$this->getView()->set( $name, $Object );
		$this->getView()->set( 'Error', $error );
		return $this->getView()->render();
	}
	
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Category = new Product_Category();
		$this->getView()->set( 'Categories', $Category->findList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
}
