<?

/**
 * The Clients view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Clients extends View_Frontend
{
	
	public function htmlShort( Content_Page $Page, array $Clients )
	{
		return $this->includeLayout('view/clients/short.html', array( 'Page' => $Page, 'Clients' => $Clients ));
	}

	public function index()
	{
		return $this->includeLayout('view/clients/index.html');
	}
        
        public function view()
	{
		return $this->includeLayout('view/clients/view.html');
	}

}
