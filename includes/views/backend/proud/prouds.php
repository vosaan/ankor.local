<?

/**
 * The Prouds view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Proud_Prouds extends View_Backend
{
	
	public function htmlUpload( Proud $Proud )
	{
		return $this->includeLayout('view/proud/proud/upload.html', array('Proud' => $Proud));
	}
	
	public function index()
	{
		return $this->includeLayout('view/proud/proud/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/proud/proud/add.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/proud/proud/form.html');
	}

}
