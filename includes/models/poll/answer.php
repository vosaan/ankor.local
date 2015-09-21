<?

/**
 * The Poll Answer model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Poll_Answer extends Object
{

	public $Id;
	public $Text;
	public $Count;

	/**
	 * @see parent::getPrimary()
	 */
	protected function getPrimary()
	{
		return array();
	}
	
	/**
	 * @see parent::getTableName()
	 */
	protected function getTableName()
	{
		return null;
	}
	
}
