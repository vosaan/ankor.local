<?

/**
 * The Announces view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Backend_Announces extends View_Backend
{
	
	protected function isPurchase()
	{
		return $this->getController()->getAnnounceType() == Announce::PURCHASE;
	}
	
	public function index()
	{
		return $this->includeLayout('view/announces/index.html');
	}

	public function add()
	{
		return $this->includeLayout('view/announces/form.html');
	}

	public function edit()
	{
		return $this->includeLayout('view/announces/form.html');
	}

}
