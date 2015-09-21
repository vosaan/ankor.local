<?

/**
 * The Resume Email class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Email_Resume extends Email
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
		return Config::get('email@resume/to');
	}
	
	/**
	 * @see parent::getTo()
	 */
	protected function getSubject()
	{
		$Resume = $this->getObject();
		return 'Резюме с сайта. '.$Resume->Name.' ('.$Resume->Post.')';
	}
	
	/**
	 * @see parent::getMessage()
	 */
	protected function getMessage()
	{
		return $this->includeLayout('resume.html');
	}
	
	/**
	 * @see parent::send()
	 */
	public function send()
	{
		$Resume = $this->getObject();
		if ( $Resume->IsFile )
		{
			$this->attach( File::path( $Resume ), $Resume->Filename );
		}
		return parent::send();
	}
	
}
