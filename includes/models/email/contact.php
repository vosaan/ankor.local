<?

/**
 * The Contact Email class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Email_Contact extends Email
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
		if ( !Request::get('book') )
		{
			$id = intval(Request::get('Department'));
			$arr = Contact::getDepartments('Email');
			if ( isset( $arr[ $id ] ) )
			{
				return $arr[ $id ];
			}
		}
		return Config::get('email@contacts/to');
	}
	
	/**
	 * @see parent::getTo()
	 */
	protected function getSubject()
	{
		return 'Обратная связь';
	}
	
	/**
	 * @see parent::getMessage()
	 */
	protected function getMessage()
	{
		if ( Request::get('book') )
		{
			return $this->includeLayout('book.html');
		}
		return $this->includeLayout('contact.html');
	}
	
}
