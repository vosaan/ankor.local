<?

/*

{INSTALL:SQL{
create table articles(
	Id int not null auto_increment,
	Type tinyint not null,
	Title varchar(200) not null,
	Description text not null,
	Content mediumtext not null,
	ImgAlt varchar(250) not null,
	ImgTitle varchar(250) not null,
	SeoTitle varchar(250) not null,
	SeoKeywords text not null,
	SeoDescription text not null,
	HasVideo tinyint not null,
	Icon char(10) not null,
	InSubscription tinyint not null,

	Filename varchar(200) not null,
	IsFile tinyint not null,

	PostedAt int not null,

	primary key (Id),
	index (PostedAt)
) engine = MyISAM;

}}
*/

/**
 * The Article model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Article extends Object
{
	
	const NEWS		= 1;
	const ARTICLE	= 2;


	public $Id;
	public $Type;
	
	public $Title;
	public $Description;
	public $Content;
	public $ImgAlt;
	public $ImgTitle;
	public $SeoTitle;
	public $SeoKeywords;
	public $SeoDescription;
	public $HasVideo;
	public $Icon;
	public $InSubscription;
	
	public $Filename;
	public $IsFile;
	
	public $PostedAt;
	
	private $tags = null;
	
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
		return 'articles';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Title'			=> '/\S{2,}/',
			'Description'	=> '/\S{2,}/',
			'Content'		=> '/\S{2,}/',
		);
	}
	
	/**
	 * @see parent::getUploadFileInfo()
	 */
	public function getUploadFileInfo()
	{
		return array(
			'allow'			=> array('gif', 'jpg', 'jpeg', 'png'),
			'extension'		=> 'jpg',
			'folderFormat'	=> '%05d',
			'folderLimit'	=> 1000,
			'urlFormat'		=> true,

			'sizes'			=> array('700x300'),
			'quality'		=> array(90),
			'dropOrig'		=> true,
		);
	}
		
	/**
	 * @see parent::getFileUrl()
	 */
	public function getFileUrl( $class, $folder, $index, $ext )
	{
		$info = pathinfo( $this->Filename );
		return '/images/article/'.$folder.'/'.$index.'/'.$this->Id.'/'.urlencode( preg_replace( '/\s/', '-', $info['filename'] ) ).'.'.$ext;
	}
	
	/**
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		$this->InSubscription = 1;
		return parent::saveNew();
	}
	
	/**
	 * @see parent::save()
	 */
	public function save()
	{
		if ( parent::save() )
		{
			if ( $this->tags !== null )
			{
				$Ref = new Article_Tag();
				$Ref->dropList( array( 'ArticleId = '.$this->Id ) );
				foreach ( explode( ',', $this->tags ) as $tag )
				{
					$tag = trim( $tag );
					if ( $tag )
					{
						$Tag = new Tag();
						$Tag = $Tag->findItem( array( 'Name = '.$tag ) );
						if ( !$Tag->Id )
						{
							$Tag->Name = $tag;
							$Tag->save();
						}
						
						$Ref = new Article_Tag();
						$Ref->ArticleId = $this->Id;
						$Ref->TagId = $Tag->Id;
						$Ref->saveNew();
					}
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * @see parent::drop()
	 */
	public function drop()
	{
		if ( parent::drop() )
		{
			File::detach( $this );
			$Tag = new Article_Tag();
			$Tag->dropList( array( 'ArticleId = '.$this->Id ) );
			return true;
		}
		return false;
	}
	
	/**
	 * The function returns search parameter.
	 * 
	 * @access public
	 * @param string $name The field name.
	 * @param mixed $value The value.
	 * @return string The search parameter.
	 */
	public function getParam( $name, $value = null )
	{
		if ( !$value )
		{
			return null;
		}
		switch ( $name )
		{
			case 'year':
				return '* from_unixtime(PostedAt, "%Y") = '.$value;
				
			case 'tag':
				$id = 0;
				if ( $value instanceof Article_Tag )
				{
					$id = $value->TagId;
				}
				else if ( $value instanceof Tag )
				{
					$id = $value->Id;
				}
				else
				{
					$Tag = new Tag();
					$Tag = $Tag->findItem( array( 'Name = '.$value ) );
					$id = $Tag->Id;
				}
				return '* Id in (select ArticleId from articles_tags where TagId = '.$id.')';
				
			case 'reference':
				if ( $value instanceof Content_Page )
				{
					return '* Id in (select ArticleId from articles_references where Layout = '.Article_Reference::PAGE.' and RefId = '.$value->Id.')';
				}
				else if ( $value  instanceof Product )
				{
					return '* Id in (select ArticleId from articles_references where '
						.' (Layout = '.Article_Reference::PRODUCT.' and RefId = '.$value->Id.') or '
						.' (Layout = '.Article_Reference::CATALOG.' and CategoryId in ('.$value->CategoryId.', 0) '
							.' and BrandId in ('.$value->BrandId.', 0)'
							.' and TypeId in ('.$value->TypeId.', 0) '
							.' and MaterialId in ('.$value->MaterialId.', 0) )'
						.')';
				}
				break;
		}
		return null;
	}
	
	/**
	 * The function returns Article posted date.
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
	 * @see parent::setPost()
	 */
	public function setPost( array $data = array() )
	{
		parent::setPost( $data );
		if ( isset( $data['PostedAt'] ) )
		{
			$this->PostedAt = strtotime( $data['PostedAt'] );
		}
		$this->HasVideo = empty( $data['HasVideo'] ) ? 0 : 1;
		if ( isset( $data['Tags'] ) )
		{
			$this->tags = $data['Tags'];
		}
	}
	
	/**
	 * The function finds Articles but only few columns in result.
	 * 
	 * @see parent::findList()
	 */
	public function findShortList( $params = array(), $order = null, $offset = null, $limit = null )
	{
		return $this->findResult( 'Id, Type, Icon, Title, Description, ImgAlt, ImgTitle, HasVideo, InSubscription, IsFile, Filename, PostedAt', $params, $order, $offset, $limit );
	}
	
	/**
	 * The function returns Article catalog link depends on Article type.
	 * 
	 * @access public
	 * @return string The link.
	 */
	public function getParentLink()
	{
		switch ( $this->Type )
		{
			case self::NEWS:
				return _L('Controller_Frontend_News');
			
			default:
				return _L('Controller_Frontend_Articles');
		}
		return null;
	}
	
	/**
	 * The function returns Article link.
	 * 
	 * @access public
	 * @return string The link.
	 */
	public function getLink()
	{
		return $this->getParentLink().'/view/'.$this->Id;
	}
	
	/**
	 * The function returns array of years.
	 * 
	 * @access public
	 * @param int $type The article type.
	 * @return array The array of years.
	 */
	public function getYears( $type = null )
	{
		$result = array();
		$params = array();
		if ( $type !== null )
		{
			$params[] = 'Type = '.$type;
		}
		foreach ( $this->findArray( 'distinct from_unixtime(PostedAt, "%Y") as Year', $params, 'Year desc' ) as $item )
		{
			$result[] = $item['Year'];
		}
		return $result;
	}
	
	/**
	 * The function returns current Article type.
	 * 
	 * @access public
	 * @return string The Article type.
	 */
	public function getType()
	{
		$arr = self::getTypes();
		return isset( $arr[ $this->Type ] ) ? $arr[ $this->Type ] : null;
	}
	
	/**
	 * The function returns Tags for current article.
	 * 
	 * @access public
	 * @param string $delimiter The tags delimiter if NULL returns array of Tags otherwise returns string with tags.
	 * @return mixed The tags.
	 */
	public function getTags( $delimiter = null )
	{
		$Tag = new Tag();
		$params = array();
		$params[] = $Tag->getParam( 'article', $this );
		$arr = $Tag->findList( $params, 'Name asc' );
		if ( $delimiter === null )
		{
			return $arr;
		}
		return implode( $delimiter, self::convertArray( $arr, 'Id', 'Name' ) );
	}
	
	/**
	 * The function returns References for current Article.
	 * 
	 * @access public
	 * @return array The References.
	 */
	public function getReferences()
	{
		$Ref = new Article_Reference();
		return $Ref->findList( array( 'ArticleId = '.$this->Id ), 'Layout asc' );
	}
	
	/**
	 * The function returns array of Article types.
	 * 
	 * @static
	 * @access public
	 * @return array The array of types.
	 */
	public static function getTypes()
	{
		return array(
			self::ARTICLE	=> 'articles',
			self::NEWS		=> 'news',
		);
	}
	
}
