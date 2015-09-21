<?

/**
 * The Subscribers view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Subscribers extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/subscribers/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/subscribers/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/subscribers/form.html');
	}

}
