<?

/*

{INSTALL:SQL{
create table content_pages  (
	Id int not null auto_increment,
	ParentId int not null,
	Name varchar(250) not null,
	Link varchar(250) not null, 
	Module varchar(100) not null,
	Layout varchar(100) not null,
	Menu varchar(100) not null,
	Title varchar(200) not null,
	Description text not null, 
	Content mediumtext not null,
	ImageId int not null,
	
	SeoKeywords text not null, 
	SeoDescription text not null,
	SeoTitle varchar(200) not null,

	Documents text not null,
	InMenu tinyint not null,
	Children int not null,
	Position int not null,
	IsEnabled tinyint not null,

	primary key (Id),
	index (Link),
	index (ParentId),
	index (InMenu),
	index (Position)
) engine=MyISAM;

}}
 */

/**
 * The Content Page model.
 * 
 * @author Yarick.
 * @version 0.2
 */
class Content_Page extends Object
{

	const URL_EXPR	= '/^\/[\w\d-_\.\#\/]*$/';


	public $Id;
	public $ParentId;
	public $Name;
	public $Link;
	public $Module;
	public $Layout;
	public $Menu;
	public $Title;
	public $Description;
	public $Content;
	public $ImageId;
	public $SeoKeywords;
	public $SeoDescription;
	public $SeoTitle;
	public $InMenu;
	public $Children;
	public $Position;
	public $IsEnabled;
	
	protected $Documents;
	
	/**
	 * @see parent::getPrimary()
	 */
	protected function getPrimary()
	{
		return array( 'Id' );
	}
	
	/**
	 * @see parent::getTableName()
	 */
	protected function getTableName()
	{
		return 'content_pages';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'		=> '/\S{2,}/',
			'Link'		=> '?'.self::URL_EXPR,
			'Title'		=> '/\S{2,}/',
		);
	}
	
	/**
	 * @see parent::setPost()
	 */
	
	/**
	 * @see parent::save()
	 */
	public function save()
	{
		if ( $this->ParentId == $this->Id )
		{
			$this->ParentId = 0;
		}
		if ( !$this->Position )
		{
			$this->Position = intval( self::getLast( $this, 'Position', array( 'ParentId = '.$this->ParentId ) ) ) + 1;
		}
		if ( parent::save() )
		{
			$this->getParent()->cacheChildren();
			return true;
		}
		return false;
	}
	
	/**
	 * The function returns search param for Page.
	 * 
	 * @access public
	 * @param string $name The search key.
	 * @param mixed $value The search value.
	 * @return string The param string.
	 */
	public function getParam( $name, $value = null )	
	{
		switch ( $name )
		{
			case 'search':
				return '* Name like '.$this->db()->quote($value."%");
		}
		return null;
	}

	/**
	 * The function finds Pages but only few columns in result.
	 * 
	 * @see parent::findList()
	 */
	public function findShortList( $params = array(), $order = null, $offset = null, $limit = null )
	{
		return $this->findResult( 'Id, ParentId, Name, Link, Menu, Module, InMenu, Title, Children, IsEnabled', $params, $order, $offset, $limit );
	}
	
	/**
	 * The function returns TRUE if Page has at least one Block, otherwise returns FALSE.
	 * 
	 * @access public
	 * @return bool TRUE if has blocks, otherwise FALSE.
	 */
	public function hasBlocks()
	{
		$Block = new Content_Page_Block();
		return $Block->findSize( array( 'PageId = '.$this->Id ) ) > 0;
	}
	
	/**
	 * The function returns array of Block objects.
	 * 
	 * @access public
	 * @param int $limit The blocks limit.
	 * @return array The Block objects array.
	 */
	public function getBlocks( $limit = null )
	{
		$Block = new Content_Page_Block();
		return $Block->findList( array( 'PageId = '.$this->Id ), 'Position asc', null, $limit );
	}
	
	/**
	 * @see parent::drop()
	 */
	public function drop()
	{
		if ( parent::drop() )
		{
			foreach ( $this->getBlocks() as $Block )
			{
				$Block->drop();
			}
			return true;
		}
		return false;
	}
	
	/**
	 * The function returns TRUE if at least one document attached to Page.
	 * 
	 * @access public
	 * @return bool TRUE if documents attached, otherwise FALSE.
	 */
	public function hasDocuments()
	{
		$arr = $this->getDocumentsArray();
		if ( !count( $arr ) )
		{
			return array();
		}
		$Document = new Document();
		return $Document->findSize( array( '* Id in ('.implode( ',', $arr ).')' ) ) > 0;
	}
	
	/**
	 * The function returns TRUE if current Document attached to Page.
	 * 
	 * @access public
	 * @param object $Document The Document.
	 * @return bool TRUE if Document attached, otherwise FALSE.
	 */
	public function hasDocument( Document $Document )
	{
		$arr = $this->getDocumentsArray();
		return in_array( $Document->Id, $arr );
	}
	
	/**
	 * The function returns array of current Page documents id.
	 * 
	 * @access private
	 * @return array The array of documents id.
	 */
	private function getDocumentsArray()
	{
		$arr = explode( ':', $this->Documents );
		if ( empty( $arr[0] ) )
		{
			$arr = array();
		}
		return $arr;
	}
	
	/**
	 * The function returns current Page Documents.
	 * 
	 * @access public
	 * @return array The array of Documents.
	 */
	public function getDocuments()
	{
		$Document = new Document();
		$params = array();
		$arr = $this->getDocumentsArray();
		if ( !count( $arr ) )
		{
			return array();
		}
		$params[] = '* Id in ('.implode( ',', $arr ).')';
		return $Document->findList( $params, 'Position asc' );
	}	
	
	/**
	 * The function returns current Page Image.
	 * 
	 * @access public
	 * @return object The Image.
	 */
	public function getImage()
	{
		$Image = new Content_Image();
		return $Image->findItem( array( 'Id = '.$this->ImageId ) );
	}
	
	/**
	 * The function returns parent Page.
	 *
	 * @access public
	 * @return object The parent Page.
	 */
	public function getParent()
	{
		$Page = new self();
		return $Page->findItem( array( 'Id = '.$this->ParentId ) );
	}
	
	/**
	 * The function caches children count for current Page.
	 *
	 * @access public
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function cacheChildren()
	{
		if ( !$this->Id )
		{
			return false;
		}
		$this->Children = $this->findSize( array( 'ParentId = '.$this->Id ) );
		return parent::save();
	}
	
	/**
	 * The function returns page menu text.
	 *
	 * @access public
	 * @return string The menu text.
	 */
	public function getMenuText()
	{
		return $this->Menu ? $this->Menu : $this->Title;
	}
	
	/**
	 * The function returns Page and its parents' children.
	 *
	 * @access public
	 * @return array The array of Pages.
	 */
	public function getNeighbors()
	{
		$result = array();
		if ( !$this->Id )
		{
			return $result;
		}
		$Parent = $this;
		if ( $this->ParentId )
		{
			$Parent = $this->getParent();
		}
		$result = array_merge( array( $Parent ), self::getChildren( $Parent->Id ) );
		return count( $result ) > 1 ? $result : array();
	}
	
	/**
	 * The function returns page layout.
	 *
	 * @access public
	 * @return string The layout.
	 */
	public function getLayout()
	{
		return $this->Layout ? $this->Layout : 'page.html';
	}
	
	/**
	 * The function returns title for page, if it has parent - parents title.
	 *
	 * @access public
	 * @return string The page title.
	 */
	public function getTitle()
	{
		return $this->ParentId ? $this->getParent()->Title : $this->Title;
	}
	
	/**
	 * The function returns current position of page item in the list of neighbors.
	 *
	 * @access public
	 * @return int The position.
	 */
	public function getPosition()
	{
		return $this->findSize( array( 'ParentId = '.$this->ParentId, 'Position < '.$this->Position ) ) + 1;
	}
	
	/**
	 * The function returns controller for current Page.
	 * 
	 * @access public
	 * @return object The Controller.
	 */
	public function getController()
	{
		$name = $this->Module ? $this->Module : 'Controller_Frontend';
		$class = new $name();
		$class->setContentPage( $this );
		return $class;
	}
	
	/**
	 * The function returns TRUE if current Page is shown in menu, otherwise FALSE.
	 * 
	 * @access public
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function inMenu()
	{
		if ( $this->InMenu )
		{
			return $this->getController()->isPageInMenu( $this );
		}
		return false;
	}
	
	/**
	 * The function returns children pages for current page id.
	 *
	 * @static
	 * @access public
	 * @param int $parent The parent id.
	 * @param int $exclude The page id to exclude.
	 * @return array The children Pages.
	 */
	public static function getChildren( $parent = 0, $exclude = null )
	{
		$Page = new self();
		$params = array( 'ParentId = '.$parent );
		if ( $exclude )
		{
			$params[] = 'Id <> '.$exclude;
		}
		return $Page->findList( $params, 'Position asc' );
	}
	
	/**
	 * The function returns pages which set in menu.
	 * 
	 * @static
	 * @param int $parent The parent id.
	 * @return array The Pages in menu.
	 */
	public static function getMenu( $parent = 0 )
	{
		$Page = new self();
		$params = array();
		$params[] = 'ParentId = '.intval( $parent );
		$params[] = 'InMenu = 1';
		return $Page->findShortList( $params, 'Position asc' );
	}
	
}
