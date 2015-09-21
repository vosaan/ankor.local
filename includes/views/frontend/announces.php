<?

/**
 * The Announces view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Announces extends View_Frontend
{
	
	public function index()
	{
		return $this->includeLayout('view/announces/index.html');
	}
	
}
