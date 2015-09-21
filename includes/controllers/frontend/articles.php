<?

/**
 * The Articles controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Articles extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Статьи';
	}
	
	/**
	 * @see parent::getArticleType()
	 */
	public function getArticleType()
	{
		return Article::ARTICLE;
	}
	
	/**
	 * @see parent::getLimit()
	 */
	public function getLimit()
	{
		return 10;
	}
	
	/**
	 * The articles index handler.
	 * 
	 * @access public
	 * @param int $year The year or meta id.
	 * @return string The HTML code.
	 */
	
	public function getSitemapNode()
	{
		$result = $params = array();
		$Article = new Article();
		$params = array();
		$params[] = 'Type = '.$this->getArticleType();
		foreach ( $Article->findShortList( $params, 'Id desc' ) as $Article )
		{
			$result[] = URL::get( $Article );
		}
		return $result;
	}
	
	public function index( $year = null )
	{
		$Article = new Article();
		$offset = $limit = null;
		$params = array();
		$params[] = 'Type = '.$this->getArticleType();
		if ( $year && is_numeric( $year ) )
		{
			$params[] = $Article->getParam( 'year', $year );
		}
		else
		{
			$offset = 0;
			$limit = 10;
		}
		$Tag = null;
		if ( Request::get('tag') )
		{
			$Tag = new Tag();
			$Tag = $Tag->findItem( array( 'Name = '.Request::get('tag') ) );
			$params[] = $Article->getParam( 'tag', Request::get('tag') );
		}
		$Paginator = new Paginator( $Article->findSize( $params ), $this->getLimit(), $this->getPage() );
		$this->getView()->set( 'Articles', $Article->findShortList( $params, 'PostedAt desc, Id desc', $this->getOffset(), $this->getLimit() ) );
		$this->getView()->set( 'Paginator', $Paginator );
		$this->getView()->set( 'Current', $year );
		$this->getView()->set( 'Tag', $Tag );
		return $this->getView()->render();
	}
	
	/**
	 * The articles index and view handler.
	 * 
	 * @access public
	 * @param int $id The Article id.
	 * @return string The HTML code.
	 */
	public function view( $id = null )
	{
		$Article = new Article();
		$Article = $Article->findItem( array( 'Id = '.$id ) );
		if ( !$Article->Id )
		{
			$this->halt();
		}
		
		$Page = $this->getContentPage();
		$Page->SeoTitle = $Article->Title;
		
		$params = array();
		$params[] = 'Id <> '.$Article->Id;
		$params[] = 'Type = '.$Article->Type;
		$this->getView()->set( 'Last', $Article->findShortList( $params, 'PostedAt desc', 0, 10 ) );
		$this->getView()->set( 'Article', $Article );
		return $this->getView()->render();
	}
	
	public function json( $method = null )
	{
		$response = array('result' => 0);
		switch ( $method )
		{
			case 'subscribe':
				if ( Request::get('Email') )
				{
					$Subscription = new Subscription();
					if ( $Subscription->findSize( array( 'Email = '.Request::get('Email') ) ) )
					{
						$response['result'] = 1;
						$response['msg'] = 'Данный E-mail уже есть в базе подписчиков';
					}
					else
					{
						$Subscription->Email = Request::get('Email');
						if ( $Subscription->save() )
						{
							$Email = new Email_Subscription( $Subscription );
							$Email->send();
							$response['result'] = 1;
							$response['msg'] = 'Вы успешно подписались на новости';
						}
						else
						{
							$response['msg'] = 'Ошибка записи данных';
						}
					}
				}
				break;
		}
		return $this->outputJSON( $response );
	}
	
	/**
	 * The function unsubscribes e-mail by subscription code.
	 * 
	 * @access public
	 * @param string $code The code.
	 * @return string The HTML code.
	 */
	public function unsubscribe( $code = null )
	{
		$Subscription = new Subscription();
		$Subscription = $Subscription->findCode( $code );
		$Subscription->drop();
		$this->getView()->set( 'Subscription', $Subscription );
		return $this->getView()->render();
	}
	
	/**
	 * @see parent::noMethod()
	 */
	public function noMethod()
	{
		$this->getView()->setMethod('index');
		return $this->index( func_get_arg(0) );
	}
	
	
}
