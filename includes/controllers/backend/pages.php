<?

/**
 * The Pages controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Pages extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Страницы';
	}
	
	/**
	 * @see parent::getModelName()
	 */
	public function getModelName()
	{
		return 'Content_Page';
	}
	
	/**
	 * @see parent::getAliasName()
	 */
	public function getAliasName()
	{
		return 'Page';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Page = new Content_Page();
		if ( !Request::get('t') )
		{
			$_GET['t'] = 'root';
		}
		$this->getView()->set( 'Pages', $Page->findList( array( 'ParentId = 0' ), 'Position asc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * The function shows edit form and saves data on submit.
	 * 
	 * @access private
	 * @param object $Page The Content Page object.
	 * @return string The HTML code.
	 */
	protected function initForm( Content_Page $Page )
	{
		if ( isset( $_POST['submit'] ) )
		{
			$Page->setPost( $_POST );
			$fields = Error::test( $Page );
			if ( count( $fields ) )
			{
				$this->getView()->set( 'Error', 'Неверно заполены поля: '.implode( ', ', $fields ) );
			} 
			else if ( $Page->save() )
			{
				if ( $Page->Module && $Page->Link )
				{
					Router::attachPage( $Page );
				}
				else
				{
					Router::detachPage( $Page );
				}
				return $this->halt('', true);
			}
			else
			{
				$this->getView()->set( 'Error', 'Ошибка записи данных: '.$Page->getError()."\n".Database::getInstance()->getLastQuery() );
			}
		}
		$Document = new Document();
		$this->getView()->set( 'Documents', $Document->findList( array(), 'Position asc' ) );
		$this->getView()->set( 'Page', $Page );
		return $this->getView()->render();
	}
	
	/**
	 * The function shows block edit from and saves data on submit
	 * 
	 * @access private
	 * @param object $Page The Content Page object.
	 * @param string $action The action.
	 * @param int $id The block id.
	 * @return string The HTML code.
	 */
	private function initBlock( Content_Page $Page, $action, $id = null )
	{
		$Block = new Content_Page_Block();
		if ( $id )
		{
			$Block = $Block->findItem( array( 'Id = '.$id ) );
		}
		if ( $action == 'delete' )
		{
			$response = array( 'result' => 0 );
			if ( $Block->Id )
			{
				if ( $Block->drop() )
				{
					$response['result'] = 1;
				}
				else
				{
					$response['msg'] = 'Не могу удалить данные';
				}
			}
			else
			{
				$response['msg'] = 'Блок текста не найден';
			}
			return $this->outputJSON( $response );
		}
		if ( isset( $_POST['submit'] ) )
		{
			$Block->PageId = $Page->Id;
			$Block->setPost( $_POST );
			$fields = Error::test( $Page );
			if ( !$Block->Position )
			{
				foreach ( $Block->findList( array(), 'Position desc', 0, 1 ) as $Item )
				{
					$Block->Position = $Item->Position + 1;
				}
				if ( !$Block->Position )
				{
					$Block->Position = 1;
				}
			}
			if ( count( $fields ) )
			{
				$this->getView()->set( 'Error', 'Неверно заполены поля: '.implode( ', ', $fields ) );
			}
			else if ( $Block->save() )
			{
				if ( !empty( $_FILES['file']['tmp_name'] ) )
				{
					if ( File::upload( $Block, $_FILES['file'] ) )
					{
						$Block->save();
					}
				}
				if ( !empty( $_POST['detach'] ) )
				{
					if ( File::detach( $Block ) )
					{
						$Block->save();
					}
				}
				$this->halt( 'edit/'.$Page->Id );
			}
			else
			{
				$this->getView()->set( 'Error', 'Ошибка записи данных' );
			}
		}
		$this->getView()->set( 'Page', $Page );
		$this->getView()->set( 'Block', $Block );
		$this->getView()->setMethod('block');
		return $this->getView()->render();
	}
	
	/**
	 * The edit handler.
	 * 
	 * @access public
	 * @param int $id The Page id.
	 * @return string The HTML code.
	 */
	public function edit( $id = null, $action = null, $block = null )
	{
		$Page = new Content_Page();
		$Page = $Page->findItem( array( 'Id = '.$id ) );
		if ( !$Page->Id )
		{
			$this->halt();
		}
		if ( $action )
		{
			return $this->initBlock( $Page, $action, $block );
		}
		return $this->initForm( $Page );
	}
	
	/**
	 * The delete handler.
	 * 
	 * @access public
	 * @param int $id The Page id.
	 * @return string The JSON response.
	 */
	public function delete( $id = null )
	{
		$response = array( 'result' => 0 );
		$Page = new Content_Page();
		$Page = $Page->findItem( array( 'Id = '.$id ) );
		if ( $Page->Id )
		{
			if ( $Page->drop() )
			{
				Router::detachPage( $Page );
				$response['result'] = 1;
			}
			else
			{
				$response['msg'] = 'Не могу удалить данные';
			}
		}
		else
		{
			$response['msg'] = 'Страница не найдена';
		}
		return $this->outputJSON( $response );
	}
	
	/**
	 * The position handler.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function posb()
	{
		$response = array( 'result' => 0 );
		if ( isset( $_POST['items'] ) && is_array( $_POST['items'] ) )
		{
			$Block = new Content_Page_Block();
			foreach ( $_POST['items'] as $pos => $id )
			{
				$Block = $Block->findItem( array( 'Id = '.$id ) );
				if ( $Block->Id )
				{
					$Block->Position = $pos + 1;
					$Block->save();
				}
			}
			$response['result'] = 1;
		}
		return $this->outputJSON( $response );
	}
	
	/**
	 * The function enables/disabled Page in menu.
	 *
	 * @access public
	 * @return string The JSON response.
	 */
	public function status()
	{
		$response = array( 'result' => 0 );
		$Page = new Content_Page();
		$Page = $Page->findItem( array( 'Id = '.Request::get('id') ) );
		if ( $Page->Id )
		{
			switch (Request::get('action'))
			{
				case "enabled":
					$Page->IsEnabled = intval( Request::get('state') );
					break;
				case "menu":
					$Page->InMenu = intval( Request::get('state') );
					break;
			}			
			if ( $Page->save() )
			{
				$response['result'] = 1;
			}
			else
			{
				$response['msg'] = 'Ошибка записи';
			}
		}
		else
		{
			$response['msg'] = 'Элемент не найден';
		}
		return $this->outputJSON( $response );
	}	
	
}
