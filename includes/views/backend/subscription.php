<?

/**
 * The Subscriptions view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Subscription extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/subscription/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/subscription/form.html');
	}

	public function view()
	{
		return $this->includeLayout('view/subscription/view.html');
	}

}
