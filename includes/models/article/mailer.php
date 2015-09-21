<?

/*

{INSTALL:SQL{
create table articles_mail(
	Id int not null auto_increment,
	Name varchar(100) not null,
	Articles text not null,
	PostedAt int not null,
	SentAt int not null,

	primary key (Id),
	index (SentAt)
) engine = MyISAM;

}}
*/

/**
 * The Subscription model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Article_Mailer extends Object
{
	
	public $Id;
	public $Name;
	protected $Articles;
	public $PostedAt;
	public $SentAt;	
	
	private $articles = null;
	
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
		return 'articles_mail';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'	=> '/\S{2,}/i',
		);
	}
	
	/**
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		$this->PostedAt = time();
		$Article = new Article();
		$arr = array();
		foreach ( $Article->findResult( 'Id', array( 'InSubscription = 1' ), 'PostedAt desc' ) as $Article )
		{
			$arr[] = $Article->Id;
		}
		$this->Articles = implode( ',', $arr );
		return parent::saveNew();
	}
	
	/**
	 * The function sends emails with new articles to subscribers.
	 * 
	 * @access public
	 */
	public function send()
	{
		$Subscription = new Subscription();
		foreach ( $Subscription->findList() as $Subscription )
		{
			$Sent = new Article_Sent();
			$Sent->MailerId = $this->Id;
			$Sent->Email = $Subscription->Email;
			
			$Email = new Email_Article( $Subscription );
			$Email->setArticles( $this->getArticles() );
			if ( $Email->send() )
			{
				$Sent->Result = 1;
			}
			else
			{
				$Sent->Result = 0;
			}
			$Sent->saveNew();
		}
		foreach ( $this->getArticles() as $Article )
		{
			$Article = $Article->findItem( array( 'Id = '.$Article->Id ) );
			if ( $Article )
			{
				$Article->InSubscription = 0;
				$Article->save();
			}
		}
		$this->SentAt = time();
		$this->save();
	}
	
	/**
	 * The cache function for articles.
	 * 
	 * @access public
	 * @return array The Articles.
	 */
	public function getArticles()
	{
		if ( $this->Id )
		{
			if ( $this->Articles )
			{
				$Article = new Article();
				$params = array();
				$params[] = '* Id in ('.$this->Articles.')';
				$this->articles = $Article->findShortList( $params, 'PostedAt desc' );
			}
			else
			{
				$this->articles = array();
			}
		}
		if ( $this->articles === null )
		{
			$Article = new Article();
			$this->articles = $Article->findShortList( array( 'InSubscription = 1' ), 'PostedAt desc' );
		}
		return $this->articles;
	}
	
	/**
	 * The function returns Sent objects in current Mailer.
	 * 
	 * @access public
	 * @return array The Sent objects.
	 */
	public function getSent()
	{
		$Sent = new Article_Sent();
		return $Sent->findList( array( 'MailerId = '.$this->Id ), 'SentAt asc' );
	}
	
	/**
	 * The function returns Mailer posted date.
	 * 
	 * @access public
	 * @param bool $short The short format.
	 * @return string The date.
	 */
	public function getDate( $short = false, $time = null )
	{
		if ( !$this->PostedAt )
		{
			$this->PostedAt = $time;
		}
		if ( $short )
		{
			return date( 'd.m.Y', $this->PostedAt );
		}
		return Date::formatMonth( date( 'j F Y', $this->PostedAt ), true );
	}
	
	/**
	 * The function returns Mailer sent date.
	 * 
	 * @access public
	 * @param bool $short The short format.
	 * @return string The date.
	 */
	public function getSentDate( $short = false, $time = null )
	{
		if ( !$this->SentAt )
		{
			$this->SentAt = $time;
		}
		if ( $short )
		{
			return date( 'd.m.Y', $this->SentAt );
		}
		return Date::formatMonth( date( 'j F Y', $this->SentAt ), true );
	}
	
}
