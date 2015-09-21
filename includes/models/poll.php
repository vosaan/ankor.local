<?

/*

{INSTALL:SQL{
create table polls(
	Id int not null auto_increment,
	Question text not null,
	Answers text not null,
	IsActive tinyint not null,

	primary key (Id),
	index (IsActive)
) engine = MyISAM;

}}
*/

/**
 * The Poll model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Poll extends Object
{

	public $Id;
	public $Question;
	public $IsActive;
	protected $Answers;
	
	private $print = null;
	private $isVoted = null;

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
		return 'polls';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Question'			=> '/\S{2,}/',
		);
	}
	
	/**
	 * The function returns limit of answers.
	 * 
	 * @access protected
	 * @return int The limit.
	 */
	protected function getAnswersLimit()
	{
		return 10;
	}
	
	/**
	 * The function sets browser print value.
	 * 
	 * @access public
	 * @param string $print The browser print.
	 */
	public function setPrint( $print = null )
	{
		$this->print = sha1( $print );
	}
	
	/**
	 * @see parent::setPost()
	 */
	public function setPost( array $data )
	{
		parent::setPost( $data );
		if ( isset( $data['answer'] ) && is_array( $data['answer'] ) )
		{
			$this->setAnswers( $data['answer'] );
		}
	}
	
	/**
	 * The function votes for answer in current poll.
	 * 
	 * @access public
	 * @param int $answer The answer Id.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function vote( $answer )
	{
		if ( $this->isVoted() || !$this->print || !$this->Id )
		{
			return false;
		}
		$Answer = $this->getAnswer( $answer );
		if ( !$Answer->Id || !$Answer->Text )
		{
			return false;
		}
		$Record = new Poll_Record();
		$Record->PollId = $this->Id;
		$Record->BrowserPrint = $this->print;
		$Record->AnswerId = $Answer->Id;
		$Record->Ip = ip2long( Request::get('REMOTE_ADDR', null, 'SERVER') );
		if ( $Record->saveNew() )
		{
			$Answer->Count++;
			$this->setAnswer( $Answer );
			return $this->save();
		}
		return false;
	}
	
	/**
	 * The function returns TRUE if poll is already voted by current user.
	 * 
	 * @access public
	 * @return bool TRUE if poll voted already, otherwise FALSE.
	 */
	public function isVoted()
	{
		if ( $this->isVoted === null )
		{
			$Record = new Poll_Record();
			$this->isVoted = $Record->findSize( array( 'PollId = '.$this->Id, 'BrowserPrint = '.$this->print ) ) > 0;
		}
		return $this->isVoted;
	}
	
	/**
	 * The function returns Poll answers.
	 * 
	 * @access public
	 * @return array The array of answers.
	 */
	public function getAnswers( $all = false )
	{
		$arr = @unserialize( $this->Answers );
		if ( !is_array( $arr ) )
		{
			$arr = array();
		}
		$result = array();
		foreach ( $arr as $i => $Answer )
		{
			if ( $i + 1 > $this->getAnswersLimit() )
			{
				break;
			}
			if ( !$all && !$Answer->Text )
			{
				return $result;
			}
			$result[] = $Answer;
		}
		if ( $all )
		{
			$limit = $this->getAnswersLimit() - count( $result );
			for ( $i = 0; $i < $limit; $i++ )
			{
				$Answer = new Poll_Answer();
				$Answer->Id = count( $result ) + 1;
				$Answer->Count = 0;
				$result[] = $Answer;
			}
		}
		return $result;
	}
	
	/**
	 * The function returns Poll Answer by its Id.
	 * 
	 * @access public
	 * @param int $id The Answer Id.
	 * @return object The Answer.
	 */
	public function getAnswer( $id )
	{
		foreach ( $this->getAnswers() as $Answer )
		{
			if ( $Answer->Id == $id )
			{
				return $Answer;
			}
		}
		return new Poll_Answer();
	}
	
	/**
	 * The function updates Answer in current Poll.
	 * 
	 * @access public
	 * @param object $Answer The Answer.
	 */
	public function setAnswer( Poll_Answer $Answer )
	{
		$answers = array();
		foreach ( $this->getAnswers() as $Item )
		{
			if ( $Item->Id == $Answer->Id )
			{
				$Item = $Answer;
			}
			$answers[] = $Item;
		}
		$this->setAnswers( $answers );
	}
	
	/**
	 * The function sets answers by array.
	 * 
	 * @access public
	 * @param array $array The answer array.
	 */
	public function setAnswers( array $array )
	{
		$answers = array();
		foreach ( $array as $id => $item )
		{
			if ( is_a( $item, 'Poll_Answer' ) )
			{
				$answers[] = $item;
			}
			else
			{
				$Answer = new Poll_Answer();
				$Answer->Id		= $id;
				$Answer->Text	= $item['Text'];
				$Answer->Count	= intval( $item['Count'] );
				$answers[] = $Answer;
			}
		}
		$this->Answers = serialize( $answers );
	}
	
	/**
	 * The function returns total votes in current Poll.
	 * 
	 * @access public
	 * @return int The total votes.
	 */
	public function getTotal()
	{
		$total = 0;
		foreach ( $this->getAnswers() as $Answer )
		{
			$total += $Answer->Count;
		}
		return $total;
	}
	
	/**
	 * The function returns percentage of voting for curren Answer.
	 * 
	 * @access public
	 * @param object $Answer The Answer.
	 * @return float The percentage of voting.
	 */
	public function getPercentage( Poll_Answer $Answer )
	{
		$total = $this->getTotal();
		if ( !$total )
		{
			return 0;
		}
		$rest = 0;
		$answers = $this->getAnswers();
		foreach ( $answers as $i => $Item )
		{
			if ( $Item->Id == $Answer->Id && $i + 1 == count( $answers ) )
			{
				return 100 - $rest;
			}
			$rest += intval( 100 * ( $Item->Count / $total ) );
		}
		return intval( 100 * ( $Answer->Count / $total ) );
	}
	
}
