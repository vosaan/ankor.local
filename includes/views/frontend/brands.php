<?

/**
 * The Brands view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Brands extends View_Frontend
{
	
	public function index()
	{
		return $this->includeLayout('view/brands/index.html');
	}

	public function view()
	{
		return $this->includeLayout('view/brands/view.html');
	}

}
