<?

/**
 * The Service view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Service extends View_Frontend
{
	
	public function htmlShort( Content_Page $Page, array $Articles, array $Images )
	{
		$Page = $Page->findItem( array( 'Id = '.$Page->Id ) ); // load full item data
		$name = str_replace( 'Controller_Frontend_Service_', '', get_class( $this->getController() ) );
		return $this->includeLayout('view/service/short.'.strtolower( $name ).'.html', array(
			'Page'		=> $Page,
			'Articles'	=> $Articles,
			'Images'	=> $Images,
			));
	}
	
	protected function htmlCustomOrder()
	{
		return $this->includeLayout('view/service/order.html');
	}
	
	public function index()
	{
		return $this->includeLayout('view/service/index.html');
	}

	public function page()
	{
		return $this->includeLayout('view/service/page.html');
	}

}
