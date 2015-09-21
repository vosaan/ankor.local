<?

/**
 * The Certificates view class.
 * 
 * @author Slava.
 * @version 0.1
 */
class View_Backend_Certificates extends View_Backend
{
	
	public function index()
	{
		return $this->includeLayout('view/certificates/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/certificates/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/certificates/form.html');
	}

	public function upload()
	{
		return $this->includeLayout('view/certificates/upload.html');
	}

}