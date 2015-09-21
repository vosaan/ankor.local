<?

/**
 * The Contacts view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Contacts extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/contacts/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/contacts/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/contacts/form.html');
	}
	
	public function change()
	{
		return $this->includeLayout('view/contacts/change.html');
	}
	
	public function map()
	{
		return $this->includeLayout('view/contacts/map.html');
	}
	
}
