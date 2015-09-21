<?

/**
 * The Signup Email class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Email_Signup extends Email
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
		return Config::get('email@signup/to');
	}
	
	/**
	 * @see parent::getTo()
	 */
	protected function getSubject()
	{
		return 'Регистрация на сайте';
	}
	
	/**
	 * @see parent::getMessage()
	 */
	protected function getMessage()
	{
		return $this->includeLayout('signup.html');
	}
	
}
