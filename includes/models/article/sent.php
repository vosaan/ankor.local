<?

/*

{INSTALL:SQL{
create table articles_sent(
	Id int not null auto_increment,
	MailerId int not null,
	Email varchar(100) not null,
	SentAt int not null,
	Result tinyint not null,

	primary key (Id),
	index (MailerId),
	index (SentAt)
) engine = MyISAM;

}}
*/

/**
 * The Article mailer Sent model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Article_Sent extends Object
{
	
	public $Id;
	public $MailerId;
	public $Email;
	public $SentAt;	
	public $Result;
	
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
		return 'articles_sent';
	}
	
	/**
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		$this->SentAt = time();
		return parent::saveNew();
	}
	
	/**
	 * The function returns time where item has been sent.
	 * 
	 * @access public
	 * @return string The time.
	 */
	public function getTime()
	{
		return date( 'H:i:s', $this->SentAt );
	}
	
}
