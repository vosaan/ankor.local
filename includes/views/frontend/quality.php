<?

/**
 * The Quality view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Quality extends View_Frontend
{
	
	public function index()
	{
		return $this->includeLayout('view/quality/index.html');
	}

}
