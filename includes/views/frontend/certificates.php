<?

/**
 * The Certificates view class.
 * 
 * @author Slava
 * @version 0.1
 */
class View_Frontend_Certificates extends View_Frontend
{
	
	public function htmlShort( Content_Page $Page, array $Papers )
	{
		return $this->includeLayout('view/certificates/short.html', array( 'Page' => $Page, 'Papers' => $Papers ));
	}

	public function index()
	{
		return $this->includeLayout('view/certificates/index.html');
	}
	
}