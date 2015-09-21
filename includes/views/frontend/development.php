<?

/**
 * The Development view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Development extends View_Frontend
{
	
	public function htmlShort( Content_Page $Page, Gallery $Gallery )
	{
		return $this->includeLayout('view/development/short.html', array( 'Page' => $Page, 'Gallery' => $Gallery ));
	}

	public function index()
	{
		return $this->includeLayout('view/development/index.html');
	}

}
