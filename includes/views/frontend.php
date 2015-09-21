<?

/**
 * The Frontend View class.
 * 
 * @version 0.1
 */
class View_Frontend extends View_Base
{

	private static $abilities = null;
	private static $trends = null;
	private static $clients = null;
	private static $articles = null;
	private static $about = null;
	public $activeMenu = '';

	/**
	 * @see parent::includeLayout()
	 */
	protected function includeLayout( $layout, $data = null )
	{
		$this->set('Page', $this->getController()->getContentPage());
		return parent::includeLayout($layout, $data);
	}

	/**
	 * @see parent::getTitle()
	 */
	protected function getTitle()
	{
		return $this->getController()->getContentPage()->Title;
	}

	/**
	 * @see parent::getSeoTitle()
	 */
	protected function getSeoTitle()
	{
		return $this->getController()->getContentPage()->SeoTitle ? $this->getController()->getContentPage()->SeoTitle : Config::get('seotitle');
	}

	/**
	 * @see parent::getSeoKeywords()
	 */
	protected function getSeoKeywords()
	{
		return $this->getController()->getContentPage()->SeoKeywords;
	}

	/**
	 * @see parent::getSeoDescription()
	 */
	protected function getSeoDescription()
	{
		return $this->getController()->getContentPage()->SeoDescription;
	}

	/**
	 * @see parent::getLayoutDir()
	 */
	protected function getLayoutDir()
	{
		return parent::getLayoutDir() . '/frontend';
	}

	/**
	 * The function shows current Controller Page.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function showPage()
	{
		return $this->includeLayout('view/page.html', array('Page' => $this->getController()->getContentPage()));
	}

	/**
	 * The function returns menu HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlMenu()
	{
		return $this->includeLayout('block/menu.html');
	}

	/**
	 * The function returns header HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlHeader()
	{
		return $this->includeLayout('block/header.html');
	}

	/**
	 * The function returns footer HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlFooter()
	{
		return $this->includeLayout('block/footer.html');
	}

	/**
	 * The function returns Page blocks.
	 * 
	 * @access protected
	 * @param object $Page The Content Page.
	 * @return string The HTML code.
	 */
	protected function htmlPageBlocks( Content_Page $Page )
	{
		return $this->includeLayout('block/blocks.html', array('Page' => $Page));
	}

	/**
	 * The function returns paging HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlPaginator()
	{
		return $this->includeLayout('block/paginator.html');
	}

	/**
	 * The function returns Shopping cart status info.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function htmlCartStatus()
	{
		return $this->includeLayout('view/cart/status.html', array('Cart' => Cart::getCart()));
	}

	/**
	 * The function returns address (contact) block for order.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function htmlCartAddress()
	{
		return $this->includeLayout('view/cart/contact.html', array('Cart' => Cart::getCart()));
	}

	/**
	 * The function returns page content.
	 *
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlLayout()
	{
		return $this->includeLayout('layout/' . $this->getController()->getContentPage()->getLayout());
	}

	/**
	 * The function returns top block on index page.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlIndexTop()
	{
		$blocks = array();
		$Page = $this->getController()->getContentPage();
		foreach ( $Page->getBlocks(4) as $Block )
		{
			$Block->Link = _L('Controller_Frontend_Service');
			$blocks[] = $Block;
		}
		if ( count($blocks) < 4 )
		{
			$count = count($blocks);
			for ( $i = 0; $i < 4 - $count; $i++ )
			{
				$Block = new Content_Page_Block();
				$Block->Link = _L('Controller_Frontend_Service');
				$blocks[] = $Block;
			}
		}
		$Item = new Gallery_Item();
		$blocks[0]->Images = $Item->findList(array($Item->getParam('type', Gallery::CAPABILITY)), 'Position asc', 0, 6);
		$blocks[0]->Link = _L('Controller_Frontend_Service_Capability');
		$blocks[1]->Link = _L('Controller_Frontend_Service_Design');
		$blocks[2]->Link = _L('Controller_Frontend_Service_Order');
		$blocks[3]->Images = $Item->findList(array($Item->getParam('type', Gallery::GALLERY)), 'PostedAt desc', 0, 6);
		$blocks[3]->Link = _L('Controller_Frontend_Service_Production');
		return $this->includeLayout('view/index/top.html', array('blocks' => $blocks));
	}

	/**
	 * The function returns announce application.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlAnnounceApp()
	{
		return $this->includeLayout('view/announces/app.html');
	}

	/**
	 * The function returns counter HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlCounter()
	{
		return $this->includeLayout('block/counter.html');
	}

	protected function htmlCurrencyBox()
	{
		return $this->includeLayout('block/currency-box.html');
	}

	protected function htmlCallback()
	{
		return $this->includeLayout('block/callback.html');
	}

	/**
	 * The function returns TRUE if current page (controller) is same to passed controller.
	 * 
	 * @access protected
	 * @param string $controller The controller name.
	 * @param bool $exact If TRUE checks only for current page, otherwise for parent as well.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	protected function on( $Object, $exact = false )
	{
		if ( $Object instanceof Content_Page )
		{
			$Page = $this->getController()->getContentPage();			
			return $Page->Id == $Object->Id || ( ( $Page->Id == $Object->ParentId || $Page->ParentId == $Object->Id ) && !$exact );
		}
		elseif ( $Object instanceof Product_Category )
		{
			return $Object == $this->activeMenu;
		}
		return $Object == get_class($this->getController());
	}

	/**
	 * The error 404 document.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function notFound()
	{
		return $this->includeLayout('view/page.html');
	}

	/**
	 * The function returns poll box HTML code.
	 * 
	 * @access public
	 * @param object $Poll The Poll
	 * @return string The HTML code.
	 */
	public function htmlPoll( Poll $Poll )
	{
		return $this->includeLayout('block/poll.html', array('Poll' => $Poll));
	}

	/**
	 * The homepage handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		return $this->includeLayout('view/index/index.html');
	}

}
