<?

/**
 * The Resume class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Resume extends Object
{
	
	public $Id;
	public $Name;
	public $Email;
	public $Phone;
	public $Education;
	public $Filename;
	public $IsFile;
	public $PostedAt;
	
	/**
	 * @see parent::getPrimary()
	 */
	protected function getPrimary()
	{
		return array('Id');
	}
	
	/**
	 * @see parent::getTableName()
	 */
	protected function getTableName()
	{
		return 'resumes';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'			=> '/\S{2,}/',
			'Email'			=> '?'.Error::EMAIL,
			'Phone'			=> '/\S{2,}/',
		);
	}
	
	/**
	 * @see parent::getUploadFileInfo()
	 */
	public function getUploadFileInfo()
	{
		return array(
			'allow'		=> array('pdf', 'doc', 'docx', 'rtf', 'txt'),
		);
	}
	
}
