<?

/**
 * The Article (mailer) Email class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Email_Article extends Email
{
	
	private $articles = null;
	
	/**
	 * @see parent::getFrom()
	 */
	protected function getFrom()
	{
		return Config::get('email@from');
	}
	
	/**
	 * @see parent::getTo()
	 */
	protected function getTo()
	{
		return $this->getObject()->Email;
	}
	
	/**
	 * @see parent::getTo()
	 */
	protected function getSubject()
	{
		return 'Свежие публикации от компании «Анкор Плюс»';
	}
	
	public function setArticles( array $articles )
	{
		$this->articles = $articles;
	}
	
	public function getArticles()
	{
		return $this->articles;
	}
	
	/**
	 * @see parent::getMessage()
	 */
	protected function getMessage()
	{
		URL::absolute(true);
		$html = $this->includeLayout('article.html');
		URL::absolute(false);
		return $html;
	}
	
}
