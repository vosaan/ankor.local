<?

/**
 * The Assemblies view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Assemblies extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/assemblies/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/assemblies/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/assemblies/form.html');
	}

}
