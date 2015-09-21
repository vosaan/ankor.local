<?

/**
 * The Documents view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Documents extends View_Frontend
{
	
	public function index()
	{
		return $this->includeLayout('view/documents/layout.html');
	}

}
