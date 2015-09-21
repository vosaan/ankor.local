<?

/**
 * The Articles view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Articles extends View_Frontend
{
	
	protected function getArticleType()
	{
		return $this->getController()->getArticleType();
	}
	
	protected function htmlArchive( $Current = null )
	{
		$Article = new Article();
		return $this->includeLayout('view/articles/archive.html', array(
			'Years'		=> $Article->getYears( $this->getController()->getArticleType() ),
			'Current'	=> $Current,
		));
	}
	
	protected function htmlBackTo()
	{
		return $this->includeLayout('view/articles/back-to.html');
	}
	
	public function index()
	{
		return $this->includeLayout('view/articles/index.html');
	}

	public function view()
	{
		return $this->includeLayout('view/articles/view.html');
	}

	public function unsubscribe()
	{
		return $this->includeLayout('view/articles/unsubscribe.html');
	}

}
