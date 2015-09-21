<?

/**
 * The Dealers view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Dealers extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/dealers/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/dealers/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/dealers/form.html');
	}
	
	public function change()
	{
		return $this->includeLayout('view/dealers/change.html');
	}
	
	
}
