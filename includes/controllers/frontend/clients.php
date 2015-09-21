<?

/**
 * The Clients controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Clients extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Клиенты';
	}
	
	public function index()
	{
		$Client = new Client();
		$this->getView()->set('Clients', $Client->findList( array(), 'Position asc' ));
		return $this->getView()->render();
	}
	
	/**
	 * The function returns short HTML block for current page.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function htmlShort()
	{
		$Page = new Content_Page();
		$Page = $Page->findItem( array( 'Module = Controller_Frontend_Clients' ) );
		$Client = new Client();
		$Clients = $Client->findList( array(), 'Position asc', 0, 5 );
		return $this->getView()->htmlShort( $Page, $Clients );
	}
	
	public function noMethod()
	{
		return $this->halt();
	}
        
        
        /**
	 * The catalog view handler.
	 * 
	 * @param int $id The Product id.
	 * @return string The HTML code.
	 */
	public function view( $id = null )
	{
		$Product = new Proud();
		$Product = $Product->findItem(array('Id = ' . $id));
                $Client = new Client();
                
		if ( !$Product->Id )
		{
			return $this->halt();
		}
		$Category = $Product->getCategory();
		
	/*	$this->getView()->set('Product', $Product); */
                
                
		$this->getView()->set(array(
			'Product' => $Product,
			'Client' => $Client->findItem(array('Id = ' . $Product->CategoryId)),
		));
		
		$this->getView()->setMethod('view');
/*
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
*/
		return $this->getView()->render();
	}
        
	
}
