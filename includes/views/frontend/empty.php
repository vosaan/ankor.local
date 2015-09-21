<?

/**
 * The Empty page view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Empty extends View_Frontend
{
	
	public function index()
	{
		return $this->includeLayout('view/empty/index.html');
	}

}
