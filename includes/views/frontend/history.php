<?

/**
 * The History view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_History extends View_Frontend
{
	
	public function htmlShort( Content_Page $Page )
	{
		return $this->includeLayout('view/history/short.html', array( 'Page' => $Page ));
	}

	public function index()
	{
		return $this->includeLayout('view/history/index.html');
	}

}
