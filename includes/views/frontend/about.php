<?

/**
 * The About view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_About extends View_Frontend
{
	
	public function index()
	{
		return $this->includeLayout('view/about/index.html');
	}

}
