<?

/*

{INSTALL:SQL{
create table poll_records(
	PollId int not null,
	BrowserPrint char(40) not null,
	AnswerId int not null,
	Ip int not null,
	PostedAt int not null,

	primary key (PollId, BrowserPrint),
	index (AnswerId)
) engine = MyISAM;

}}
*/

/**
 * The Poll Record model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Poll_Record extends Object
{

	public $PollId;
	public $BrowserPrint;
	public $AnswerId;
	public $Ip;
	protected $PostedAt;

	/**
	 * @see parent::getPrimary()
	 */
	protected function getPrimary()
	{
		return array('PollId', 'BrowserPrint');
	}
	
	/**
	 * @see parent::getTableName()
	 */
	protected function getTableName()
	{
		return 'poll_records';
	}
	
	/**
	 * @see parent::save()
	 */
	public function saveNew()
	{
		if ( !$this->PostedAt )
		{
			$this->PostedAt = time();
		}
		return parent::saveNew();
	}
	
}
