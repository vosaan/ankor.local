<?

/**
 * The Branches view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Branches extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/branches/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/branches/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/branches/form.html');
	}

}
