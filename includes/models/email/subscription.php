<?

/**
 * The Subscription Email class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Email_Subscription extends Email
{
	
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
		return 'Подтверждение подписки на публикации компании «Анкор Плюс»';
	}
	
	/**
	 * @see parent::getMessage()
	 */
	protected function getMessage()
	{
		URL::absolute(true);
		$html = $this->includeLayout('subscribe.html');
		URL::absolute(false);
		return $html;
	}
	
}
