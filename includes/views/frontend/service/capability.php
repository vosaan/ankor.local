<?

/**
 * The Capabilites view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Service_Capability extends View_Frontend_Service
{
	
	public function page()
	{
		return $this->includeLayout('view/service/page.capability.html');
	}

}
