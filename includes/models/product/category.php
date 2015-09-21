<?

/*

  {INSTALL:SQL{
  create table products_category(
  Id int not null auto_increment,
  Name varchar(100) not null,
  SeoTitle varchar(250) not null,
  SeoDescription varchar(250) not null,
  SeoKeywords varchar(250) not null,
  Slug varchar(100) not null,
  Description text not null,
  Layout varchar(40) not null,

  Documents text not null,

  Position int not null,

  primary key (Id),
  index (Position)
  ) engine = MyISAM;

  }}
 */

/**
 * The Product Category model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Product_Category extends Object
{

	public $Id;
	public $Name;
	public $SeoTitle;
	public $SeoKeywords;
	public $SeoDescription;
	public $Slug;
	public $Description;
	public $Layout;
	public $Position;
	protected $Documents;

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
		return 'products_category';
	}

	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name' => '/\S{2,}/',
		);
	}

	/**
	 * @see parent::save()
	 */
	public function save()
	{
		if ( !$this->Position )
		{
			$this->Position = intval(self::getLast($this, 'Position')) + 1;
		}
		return parent::save();
	}

	/**
	 * The function returns current Category Layout.
	 * 
	 * @access public
	 * @return The Category Layout.
	 */
	public function getLayout()
	{
		$name = $this->Layout ? $this->Layout : 'Product_Layout_Standard';
		return new $name();
	}

	/**
	 * The function returns Products in current Category.
	 * 
	 * @access public
	 * @param bool $assoc If TRUE returns products in associated array.
	 * @return array The Products.
	 */
	public function getProducts( $assoc = false )
	{
		$Product = new Product();
		$params = array();
		$params[] = 'CategoryId = ' . $this->Id;
		$arr = $Product->findShortList($params, 'Position asc');
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray($arr, 'Id', 'Name');
	}

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
	
	public function hasDocuments()
	{
		$arr = $this->getDocumentsArray();
		if ( !count($arr) )
		{
			return array();
		}
		$Document = new Document();
		return $Document->findSize(array('* Id in (' . implode(',', $arr) . ')')) > 0;
	}

	public function hasDocument( Document $Document )
	{
		$arr = $this->getDocumentsArray();
		return in_array($Document->Id, $arr);
	}

	private function getDocumentsArray()
	{
		$arr = explode(':', $this->Documents);
		if ( empty($arr[0]) )
		{
			$arr = array();
		}
		return $arr;
	}

	public function setPost( array $data = array() )
	{
		parent::setPost($data);
		if ( !empty($data['clear_documents']) )
		{
			$this->Documents = '';
		}
		if ( isset($data['document']) && is_array($data['document']) )
		{
			$this->Documents = implode(':', $data['document']);
		}
	}

	/**
	 * The function returns all categories.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array.
	 * @return array The Categories.
	 */
	public static function getCategories( $assoc = false )
	{
		$Category = new self();
		$result = array();
		$arr = $Category->findList(array(), 'Position asc');
		if ( !$assoc )
		{
			return $arr;
		}
		return self::convertArray($arr, 'Id', 'Name');
	}

}
