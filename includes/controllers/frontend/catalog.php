<?

/**
 * The Catalog controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Catalog extends Controller_Frontend
{
	public function __construct()
	{
		parent::__construct();
		$this->setView(new View_Frontend_Catalog());
	}

	/**
	 * The function returns Product Layout.
	 * 
	 * @access public
	 * @return object The Product Layout.
	 */
	public function getModel()
	{
		return new Product_Layout_Standard();
	}

	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Каталог';
	}

	/**
	 * @see parent::getLimit()
	 */
	public function getLimit()
	{
		return Request::get('limit', 15);
	}

	/**
	 * The catalog index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Product = new Product();
		$params = array();
		$Layout = $this->getModel();
		$params[] = 'CategoryId = ' . $Layout->getCategory()->Id;
		if ( $arr = Request::get('b', array()) )
		{
			if ( count($arr) )
			{
				$params[] = '* BrandId in (' . implode(',', $arr) . ')';
			}
		}
		if ( $arr = Request::get('m', array()) )
		{
			if ( count($arr) )
			{
				$params[] = '* MaterialId in (' . implode(',', $arr) . ')';
			}
		}
		if ( $arr = Request::get('t', array()) )
		{
			if ( count($arr) )
			{
				$params[] = '* TypeId in (' . implode(',', $arr) . ')';
			}
		}
		$Paginator = new Paginator($Product->findSize($params), $this->getLimit(), $this->getPage());
		$Products = $Product->findList($params, 'Position asc', $this->getOffset(), $this->getLimit());
		if ( Request::get('ajax') )
		{
			unset($_GET['ajax']);
			return $this->getView()->htmlItems($Products, $Paginator);
		}
		$custom = array();
		$Model = new Product_Layout_Custom();
		$custom[] = 'CategoryId = ' . $Model->getCategory()->Id;
		$this->getView()->set('Custom', $Product->findList($custom, 'Position asc'));
		$this->getView()->set('Paginator', $Paginator);
		$this->getView()->set('Products', $Products);
		return $this->getView()->render();
	}

	public function category( $Category )
	{
		if ( $Category instanceof Product_Category && $Category->Id )
		{
			$Product = new Product();
			$params = array();
			$Layout = $this->getModel();
			$params[] = 'CategoryId = ' . $Category->Id;
			if ( $arr = Request::get('b', array()) )
			{
				if ( count($arr) )
				{
					$params[] = '* BrandId in (' . implode(',', $arr) . ')';
				}
			}
			if ( $arr = Request::get('m', array()) )
			{
				if ( count($arr) )
				{
					$params[] = '* MaterialId in (' . implode(',', $arr) . ')';
				}
			}
			if ( $arr = Request::get('t', array()) )
			{
				if ( count($arr) )
				{
					$params[] = '* TypeId in (' . implode(',', $arr) . ')';
				}
			}
			$Paginator = new Paginator($Product->findSize($params), $this->getLimit(), $this->getPage());
			$Products = $Product->findList($params, 'Position asc', $this->getOffset(), $this->getLimit());
			if ( Request::get('ajax') )
			{
				unset($_GET['ajax']);
				return $this->getView()->htmlItems($Products, $Paginator);
			}
			$custom = array();
			$custom[] = 'CategoryId = ' . $Category->Id;
			$this->getView()->set('Category', $Category);
			$this->getView()->set('Custom', $Product->findList($custom, 'Position asc'));
			$this->getView()->set('Paginator', $Paginator);
			$this->getView()->set('Products', $Products);

			$this->getView()->setMethod('index');
			$this->setContentPage($Category);

			$Page = $this->getContentPage();
			$Page->SeoTitle = $Category->SeoTitle ? $Category->SeoTitle : $Category->Name;
			$Page->SeoDescription = $Category->SeoDescription;
			$Page->SeoKeywords = $Category->SeoKeywords;
			$this->getView()->activeMenu = $Category;

			return $this->getView()->render();
		}

		$this->halt('');
	}

	/**
	 * The catalog view handler.
	 * 
	 * @param int $id The Product id.
	 * @return string The HTML code.
	 */
	public function view( $id = null )
	{
		$Product = new Product();
		$Product = $Product->findItem(array('Id = ' . $id));
		if ( !$Product->Id )
		{
			return $this->halt();
		}
		$Category = $Product->getCategory();
		$Article = new Article();
		$params = array();
		$params[] = 'Type = ' . Article::ARTICLE;
		$params[] = 'PostedAt < ' . time();
		$params[] = $Article->getParam('reference', $Product);
		$this->getView()->set('Product', $Product);
		$this->getView()->set('Articles', $Article->findList($params, 'PostedAt desc, Id desc'));
		$this->getView()->setMethod('view');

		if ( $Product->getCategory()->getLayout() instanceof Product_Layout_Custom )
		{
			$this->getView()->setMethod('custom');
		}
		elseif ( $Product->getCategory()->getLayout() instanceof Product_Layout_Common )
		{
			$this->getView()->setMethod('common');
		}
		$this->getView()->activeMenu = $Category;
		$this->setContentPage($Category);
		
		$Page = $this->getContentPage();
		$Page->SeoTitle = $Product->SeoTitle ? $Product->SeoTitle : $Product->Name;
		$Page->SeoDescription = $Product->SeoDescription;
		$Page->SeoKeywords = $Product->SeoKeywords;

		return $this->getView()->render();
	}

	/**
	 * The function returns part of HTML code depends on method.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function json()
	{
		switch ( Request::get('method') )
		{
			case 'colors':
				$Layout = new Product_Layout_Standard();
				$Product = new Product();
				$Params = array();
				$params[] = 'CategoryId = ' . $Layout->getCategory()->Id;
				if ( Request::get('brand') )
				{
					$params[] = 'BrandId = ' . Request::get('brand');
				}
				return $this->getView()->htmlColors($Product->findShortList($params, 'Name asc'));

			case 'units':
				$Unit = new Product_Unit();
				$params = array();
				$params[] = 'ProductId = ' . Request::get('id');
				return $this->getView()->htmlUnits($Unit->findList($params, 'Position asc'));

			case 'order':
				$response = array('result' => 0);
				return $this->outputJSON($response);
		}
	}

	public function getSitemapNode()
	{
		$result = $params = array();
		$Product = new Product();
		$Categories = Product_Category::getCategories();		
		foreach ( $Categories as $Category )
		{
			$result[] = URL::get($Category);
			$params = array();
			$params[] = 'CategoryId = '.$Category->Id;
			foreach( $Product->findList( $params, 'Position asc' ) as $Item )
			{
				$result[] = URL::get($Item);
			}
		}
		return $result;
	}

	public function noMethod()
	{
		return $this->halt();
	}

}
