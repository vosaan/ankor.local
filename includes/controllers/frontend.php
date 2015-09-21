<?

/**
 * The Frontend controller.
 * 
 * @author Yarick.
 * @version 0.1 
 */
class Controller_Frontend extends Controller_Base
{

	protected $defaultAccess = Access::FULL;

	/**
	 * @see parent::isHidden()
	 */
	public function isHidden()
	{
		return true;
	}

	/**
	 * The function returns TRUE if current content page is shown in menu, otherwise FALSE.
	 * 
	 * @acess public
	 * @param object $Page The current content page.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function isPageInMenu( Content_Page $Page )
	{
		return true;
	}

	/**
	 * @see parent::getUser()
	 */
	public function getUser()
	{
		return Runtime::get('SECURITY_CUSTOMER');
	}

	private function showPage( Content_Page $Page )
	{
		$this->getView()->setMethod('showPage');
		return $this->getView()->render();
	}

	/**
	 * The function returns name of controller.
	 * 
	 * @access public
	 * @return string The name.
	 */
	public function getName()
	{
		return get_class($this);
	}

	/**
	 * The function returns Content Page for current URL.
	 * 
	 * @access public
	 * @param bool $except404 If TRUE do not fetch the ErrorDocument page, otherwise fetch.
	 * @return object The Content Page.
	 */
	
	public function getContentPage( $except404 = false )
	{

		if ( isset($this->contentPage) )
		{
			return $this->contentPage;
		}
		if ( !Runtime::get('ROUTING_PAGE') )
		{
			$Page = new Content_Page();
			$arr = explode('/', Runtime::get('REQUEST_URI'));

			for ( $i = count($arr); $i > 0; $i-- )
			{
				$url = implode('/', array_slice($arr, 0, $i));
				if ( $url !== '' )
					$Page = $Page->findItem(array('Link = ' . $url));
				if ( $Page->Id )
				{
					break;
				}
                                
			}
                        
                        

			if ( !$except404 && !$Page->Id )
			{
				$Page = $Page->findItem(array('module = Controller_Frontend_404'));
			}
			Runtime::set('ROUTING_PAGE', $Page);
		}
		return Runtime::get('ROUTING_PAGE');
	}


	public function getSitemapNode()
	{
		return array(URL::abs($this->getContentPage()->Link));
	}

	public function setContentPage( $Object )
	{
		if ( $Object instanceof Content_Page )
		{
			$this->contentPage = $Object;
			return true;
		}
		$Page = new Content_Page();
		if ( $Object instanceof Controller_Frontend )
		{
			$Page = $Page->findItem( array( 'Module = '.get_class( $Object ) ) );
		}
		else if ( $Object instanceof Product )
		{
			$Page = $this->getCategoryPage( $Object->getCategory() );
			$Page->SeoTitle = $Object->getName();
			$Page->SeoKeywords = $Object->SeoKeywords;
			$Page->SeoDescription = $Object->SeoDescription;
		}
		else if ( $Object instanceof Product_Category )
		{
			$Page = $this->getCategoryPage( $Object );
		}
		Runtime::set( 'ROUTING_PAGE', $Page );
	}
	
	private function getCategoryPage( Product_Category $Category )
	{		
		$Page = new Content_Page();
		$Page = $Page->findItem( array( 'Module = Controller_Frontend_Catalog' ) );
		$params = array();
		$params[] = 'ParentId = '.$Page->Id;
		$params[] = 'Link = /'.$Category->Slug;
		$Current = $Page->findItem( $params );
		if ( $Current->Id )
		{
			$Page = $Current;
		}
		return $Page;
	}

	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Article = new Article();
		$Client = new Client();
		$Button = new Button();
                $Proud = new Proud();
		$Banner = new Banner();

		$a = array();
		$a[] = 'PostedAt < ' . time();
		$a[] = 'Type = ' . Article::ARTICLE;
		$b = array();
		$b[] = 'PostedAt < ' . time();
		$b[] = 'Type = ' . Article::NEWS;

		foreach ( $Banner->findList(array(), 'rand()', 0, 1) as $Banner )
			;

		$this->getView()->set(array(
			'Articles' => $Article->findShortList($a, 'PostedAt desc', 0, 4),
			'News' => $Article->findShortList($b, 'PostedAt desc', 0, 2),
			'Clients' => $Client->findList(array(), 'Position asc', 0, 8),
			'Buttons' => $Button->findList(array(), 'Position asc', 0, 3),
                        'Prouds' => $Proud->getLastProuds(),
			'Banner' => $Banner,
		));
		return $this->getView()->render();
	}

	/**
	 * The poll handler.
	 * 
	 * @access public
	 * @return string The HTML code or JSON response.
	 */
	public function poll()
	{
		if ( Request::get('ajax') )
		{
			$Poll = new Poll();
			$Poll = $Poll->findItem(array('IsActive = 1'));
			$Poll->setPrint(Request::get('print'));
			$response = array('result' => 0);
			if ( $Poll->Id )
			{
				if ( Request::get('action') == 'post' )
				{
					$Poll->vote(Request::get('value'));
					$Poll = $Poll->findItem(array('Id = ' . $Poll->Id));
					$Poll->setPrint(Request::get('print'));
				}
				$response['result'] = 1;
				$response['html'] = $this->getView()->htmlPoll($Poll);
			}
			return $this->outputJSON($response);
		}
		return $this->getView()->render();
	}

	public function getCategory( $category = null )
	{
		$Category = new Product_Category();
		if ( $category )
		{
			$Category = $Category->findItem(array('Slug = /' . $category));
		}
		return $Category;
	}
	
	public function getProduct( $id = null )
	{
		$Product = new Product();
		if ( $id )
		{
			$Product = $Product->findItem(array('Id = ' . $id));
		}
		return $Product;
	}

	/**
	 * The noMethod hanlder.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function noMethod( $method = null, $tag = null, $id = null )
	{		
		$Controller = new Controller_Frontend_Catalog();
		$Page = $this->getContentPage(true);		
		if ( $method && !$tag )
		{
			if ( $this->getCategory($method)->Id )
			{
				return $Controller->category($this->getCategory($method));
			}
		}
		elseif ( $method && $tag && $id )
		{
			if( $tag == 'view') 
			{
				$Category = $this->getCategory($method);
				$Product = $this->getProduct($id);
				if ( $Product->Id && $Category->Id && $Product->CategoryId == $Category->Id )
				return $Controller->view($id);
			}
		}
		if ( $Page->Id )
		{
			return $this->showPage($Page);
		}
		
		return $this->notFound();
	}

	/**
	 * The error document page handler.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function notFound()
	{
		header("HTTP/1.0 404 Not Found");
		$Page = new Content_Page();
		$Page = $Page->findItem(array('module = Controller_Frontend_404'));
		Runtime::set('ROUTING_PAGE', $Page);

		$this->getView()->setMethod('notFound');
		return $this->getView()->render();
	}

	/**
	 * The noAccess handler
	 * 
	 * @access public
	 */
	public function noAccess()
	{
		
	}

	/**
	 * The function returns array of modules.
	 * 
	 * @static
	 * @access public
	 * @return array The array of modules.
	 */
	public static function getModules()
	{
		$result = array();
		$dir = dirname(__FILE__) . '/frontend';
		foreach ( File::readDir($dir, true) as $file )
		{
			$file = str_replace($dir . '/', '', $file);
			$name = 'Controller_Frontend_' . basename(str_replace('/', '_', $file), '.php');
			$class = new $name();
			$result[get_class($class)] = $class->getName();
		}
		asort($result);
		return $result;
	}

	/**
	 * TLhe function returns array of layouts.
	 * 
	 * @static
	 * @access public
	 * @return array The array of layouts.
	 */
	public static function getLayouts()
	{
		$result = array();
		foreach ( File::readDir(Runtime::get('TEMPLATE_DIR') . '/frontend/layout/') as $file )
		{
			$result[basename($file)] = basename($file, '.html');
		}
		asort($result);
		return $result;
	}

}
