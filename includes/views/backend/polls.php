<?

/**
 * The Polls view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Polls extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/polls/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/polls/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/polls/form.html');
	}

}
