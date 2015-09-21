<?

/*

{INSTALL:SQL{
create table clients(
	Id int not null auto_increment,
	Name varchar(250) not null,
	Filename varchar(200) not null,
	IsFile tinyint not null,
	Projects text not null,

	PostedAt int not null,
	Position int not null,

	primary key (Id),
	index (PostedAt),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Client model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Client extends Object
{
	
	public $Id;
	public $Name;
	public $Filename;
	public $IsFile;
	protected $Projects;
	public $PostedAt;
	public $Position;
	
	
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
		return 'clients';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'			=> '/\S{2,}/',
		);
	}

	/**
	 * @see parent::getUploadFileInfo()
	 */
	public function getUploadFileInfo()
	{
		return array(
			'allow'		=> array('gif', 'jpg', 'jpeg', 'png'),
			'sizes'		=> array('170x75'),
			'quality'	=> array(100),
		);
	}
	
	/**
	 * @see parent::setPost()
	 */
	public function setPost( array $data )
	{
		parent::setPost( $data );
		if ( isset( $data['Project'] ) && is_array( $data['Project'] ) )
		{
			$this->Projects = array();
			foreach ( $data['Project']['Name'] as $i => $value )
			{
				if ( !$value )
				{
					continue;
				}
				$this->Projects[] = new Client_Project( $data['Project']['Name'][ $i ], $data['Project']['URL'][ $i ] );
			}
		}
	}
	
	/**
	 * @see parent::save()
	 */
	public function save()
	{
		if ( !$this->Position )
		{
			$this->Position = self::getLast( $this, 'Position' ) + 1;
		}
		return parent::save();
	}
	
	/**
	 * @see parent::drop()
	 */
	public function drop()
	{
		if ( parent::drop() )
		{
			File::detach( $this );
			return true;
		}
		return false;
	}
	
	/**
	 * The function returns current Client Projects.
	 * 
	 * @access public
	 * @return array The Projects.
	 */
	public function getProjects()
	{
		$this->Projects = is_array( $this->Projects ) ? $this->Projects : unserialize( $this->Projects );
		if ( !$this->Projects )
		{
			$this->Projects = array();
		}
		return $this->Projects;
	}
        
        
        public static function getClients( $assoc = false )
	{
		$Clients = new self();
		$result = array();
		$arr = $Clients->findList(array(), 'Position asc');
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray($arr, 'Id', 'Name');
	}
	
}
