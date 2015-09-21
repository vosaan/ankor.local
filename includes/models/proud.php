<?

/*

{INSTALL:SQL{
create table products(
	Id int not null auto_increment,
	Name varchar(100) not null,
	Slug varchar(100) not null,
	CategoryId int not null,
	BrandId int not null,
	TypeId int not null,
	MaterialId int not null,
	`Text` text not null,
	Warning text not null,
	Content text not null,
	Design text not null,
	Frame text not null,
	UnitName varchar(50) not null,
	IsOwn tinyint not null,
	SeoTitle varchar(250) not null,
	SeoDescription varchar(250) not null,
	SeoKeywords varchar(250) not null,
	Filename varchar(200) not null,
	IsFile tinyint not null,

	UpdatedAt int not null,
	Position int not null,

	primary key (Id),
	index (Name),
	index (Slug),
	index (CategoryId,BrandId,TypeId,MaterialId),
	index (Position)
) engine = MyISAM;

}}
*/

/**
 * The Proud model class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Proud extends Object
{

	public $Id;
	public $Name;
	public $Slug;
	public $CategoryId;
	public $BrandId;
	public $TypeId;
	public $MaterialId;
	public $Text;
	public $Warning;
	public $Content;
	public $Design;
	public $Frame;
	public $UnitName;
	public $IsOwn;
	public $SeoTitle;
	public $SeoDescription;
	public $SeoKeywords;
	public $Filename;
	public $IsFile;
	public $UpdatedAt;
	public $Position;
	
	private $Units;
	private $UnitsDelete;
	private $Models;
	private $ModelsDelete;
	
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
		return 'prouds';
	}
	
	/**
	 * @see parent::getTestRules()
	 */
	public function getTestRules()
	{
		return array(
			'Name'			=> '/\S{2,}/',
			//'Slug'			=> '/^[a-z0-9\-\.]+$/',
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

			'sizes'			=> array('142x98','160x160', '550x415'),
			'quality'		=> array(100, 95, 90),
			'timeAffix'		=> 'UpdatedAt',
			'dropOrig'		=> true,
		);
	}
	
	/**
	 * @see parent::setPost()
	 */
	public function setPost( array $data = array() )
	{
		parent::setPost( $data );
		if ( isset( $data['Unit'] ) )
		{
			$this->Units = $this->UnitsDelete = array();
			foreach ( $data['Unit']['Name'] as $id => $value )
			{
				$arr = array();
				foreach ( array_keys( $data['Unit'] ) as $key )
				{
					if ( isset( $data['Unit'][ $key ][ $id ] ) )
					{
						if ( $key == 'Delete' )
						{
							$this->UnitsDelete[] = $data['Unit'][ $key ][ $id ];
						}
						else 
						{
							$arr[ $key ] = $data['Unit'][ $key ][ $id ];
						}
					}
				}
				$this->Units[] = $arr;
			}
		}
		if ( isset( $data['Model'] ) )
		{
			$this->Models = $this->ModelsDelete = array();
			foreach ( $data['Model']['Name'] as $id => $value )
			{
				$arr = array();
				foreach ( array_keys( $data['Model'] ) as $key )
				{
					if ( isset( $data['Model'][ $key ][ $id ] ) )
					{
						if ( $key == 'Delete' )
						{
							$this->ModelsDelete[] = $data['Model'][ $key ][ $id ];
						}
						else
						{
							$arr[ $key ] = $data['Model'][ $key ][ $id ];
						}
					}
				}
				$this->Models[] = $arr;
			}
		}
		if ( isset( $data['Design'] ) && isset( $data['Design']['Name'] ) )
		{
			$this->Design = array();
			foreach ( $data['Design']['Name'] as $id => $value )
			{
				if ( !$value )
				{
					continue;
				}
				$arr = array();
				foreach ( array_keys( $data['Design'] ) as $key )
				{
					$arr[ $key ] = isset( $data['Design'][ $key ][ $id ] ) ? $data['Design'][ $key ][ $id ] : null;
				}
				$this->Design[] = $arr;
			}
		}
		if ( isset( $data['Frame'] ) && isset( $data['Frame']['Name'] ) )
		{
			$this->Frame = array();
			foreach ( $data['Frame']['Name'] as $id => $value )
			{
				if ( !$value )
				{
					continue;
				}
				$arr = array();
				foreach ( array_keys( $data['Frame'] ) as $key )
				{
					$arr[ $key ] = isset( $data['Frame'][ $key ][ $id ] ) ? $data['Frame'][ $key ][ $id ] : null;
				}
				$this->Frame[] = $arr;
			}
		}
		$this->IsOwn = empty( $data['IsOwn'] ) ? 0 : 1;
	}
	
	/**
	 * @see parent::saveNew()
	 */
	public function saveNew()
	{
		$this->Position = intval( self::getLast( $this, 'Position', array( 'CategoryId = '.$this->CategoryId ) ) ) + 1;
		return parent::saveNew();
	}
	
	/**
	 * @see parent::save()
	 */
	public function save()
	{
		if ( parent::save() )
		{
			if ( is_array( $this->Units ) )
			{
				$pos = 1;
				foreach ( $this->Units as $item )
				{
					$Unit = new Proud_Unit();
					if ( $item['Id'] )
					{
						$Unit = $Unit->findItem( array( 'Id = '.$item['Id'], 'ProudId = '.$this->Id ) );
					}
					if ( $item['Name'] )
					{
						// save
						$Unit->set( $item );
						$Unit->ProudId = $this->Id;
						$Unit->Position = $pos++;
						$Unit->save();
					}
					else if ( $Unit->Id )
					{
						// delete
						$Unit->drop();
					}
				}
			}
			if ( is_array( $this->UnitsDelete ) )
			{
				foreach ( $this->UnitsDelete as $id )
				{
					$Unit = new Proud_Unit();
					$Unit = $Unit->findItem( array( 'Id = '.$id, 'ProudId = '.$this->Id ) );
					$Unit->drop();
				}
			}
			if ( is_array( $this->Models ) )
			{
				$pos = 1;
				foreach ( $this->Models as $item )
				{
					$Model = new Proud_Model();
					if ( $item['Id'] )
					{
						$Model = $Model->findItem( array( 'Id = '.$item['Id'], 'ProudId = '.$this->Id ) );
					}
					if ( $item['Name'] )
					{
						// save
						$Model->set( $item );
						$Model->ProudId = $this->Id;
						$Model->Position = $pos++;
						$Model->save();
					}
					else if ( $Model->Id )
					{
						// delete
						$Model->drop();
					}
				}
			}
			if ( is_array( $this->ModelsDelete ) )
			{
				foreach ( $this->ModelsDelete as $id )
				{
					$Model = new Proud_Model();
					$Model = $Model->findItem( array( 'Id = '.$id, 'ProudId = '.$this->Id ) );
					$Model->drop();
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
			$Document = new Proud_Document();
			foreach ( $Document->findList( array( 'ProudId = '.$this->Id ) ) as $Document )
			{
				$Document->drop();
			}
			$Example = new Proud_Example();
			foreach ( $Example->findList( array( 'ProudId = '.$this->Id ) ) as $Example )
			{
				$Example->drop();
			}
			$Image = new Proud_Image();
			foreach ( $Image->findList( array( 'ProudId = '.$this->Id ) ) as $Image )
			{
				$Image->drop();
			}
			$Model = new Proud_Model();
			foreach ( $Model->findList( array( 'ProudId = '.$this->Id ) ) as $Model )
			{
				$Model->drop();
			}
			$Unit = new Proud_Unit();
			foreach ( $Unit->findList( array( 'ProudId = '.$this->Id ) ) as $Unit )
			{
				$Unit->drop();
			}
			return true;
		}
		return false;
	}
	
	/**
	 * The function returns search param for Proud.
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
	 * @see parent::findList()
	 */
	public function findShortList( array $params = array(), $order = null, $offset = null, $limit = null )
	{
		return $this->findResult( 'Id, Name, Slug, IsOwn, IsFile, Filename, CategoryId', $params, $order, $offset, $limit );
	}
	
	/**
	 * The function returns array of Proud Examples.
	 * 
	 * @access public
	 * @return array The Examples.
	 */
	public function getExamples()
	{
		$Example = new Proud_Example();
		return $Example->findList( array( 'ProudId = '.$this->Id ), 'Position asc' );
	}
	
	/**
	 * The function returns array of Proud Images.
	 * 
	 * @access public
	 * @return array The Images.
	 */
	public function getImages()
	{
		$Image = new Proud_Image();
		return $Image->findList( array( 'ProudId = '.$this->Id ), 'Position asc' );
	}
	
	/**
	 * The function returns array of Documents in current Proud.
	 * 
	 * @access public
	 * @return array The Documents.
	 */
	public function getDocuments()
	{
		$Document = new Proud_Document();
		return $Document->findList( array( 'ProudId = '.$this->Id ), 'Position asc' );
	}
	
	/**
	 * The function returns count of Documents in current Proud.
	 * 
	 * @access public
	 * @return int The count.
	 */
	public function getDocumentsCount()
	{
		$Document = new Proud_Document();
		return $Document->findSize( array( 'ProudId = '.$this->Id ) );
	}
	
	/**
	 * The function returns current product Units.
	 * 
	 * @access public
	 * @return array The product Units.
	 */
	public function getUnits()
	{
		$Unit = new Proud_Unit();
		return $Unit->findList( array( 'ProudId = '.$this->Id ), 'Position asc' );
	}
	
	/**
	 * The function returns current product Models.
	 * 
	 * @access public
	 * @return array The product Models.
	 */
	public function getModels()
	{
		$Model = new Proud_Model();
		return $Model->findList( array( 'ProudId = '.$this->Id ), 'Position asc' );
	}
	
	/**
	 * The function returns minimum price of current Proud.
	 * 
	 * @access public
	 * @return float The price.
	 */
		
	public function getMinPrice( )
	{
		$Unit = new Proud_Unit();
		foreach ( $Unit->findList( array( 'ProudId = '.$this->Id ), 'Price asc', 0, 1 ) as $Unit );
		
		return $Unit->Price;
		
	}
	
	/**
	 * The function returns maximum price of current Proud.
	 * 
	 * @access public
	 * @return float The price.
	 */
	public function getMaxPrice( )
	{
		$Unit = new Proud_Unit();
		foreach ( $Unit->findList( array( 'ProudId = '.$this->Id ), 'Price desc', 0, 1 ) as $Unit );
		
		return $Unit->Price;
	}
	
	/**
	 * The function returns current product Category.
	 * 
	 * @access public
	 * @return object The Category.
	 */
	public function getCategory()
	{
		$Category = new Client();
		return $Category->findItem( array( 'Id = '.$this->CategoryId ) );
	}
	
	/**
	 * The function returns current product Brand.
	 * 
	 * @access public
	 * @return object The Brand.
	 */

	
	/**
	 * The function returns related Prouds.
	 * 
	 * @access public
	 * @param bool $excludeCurrent If TRUE excludes current Proud from related list.
	 * @return array The Prouds.
	 */
	public function getRelated( $excludeCurrent = false )
	{
		$params = array();
		$params[] = 'CategoryId = '.$this->CategoryId;
		$params[] = 'BrandId = '.$this->BrandId;
		$params[] = 'TypeId = '.$this->TypeId;
		if ( $excludeCurrent )
		{
			$params[] = 'Id <> '.$this->Id;
		}
		return $this->findShortList( $params, 'Position asc' );
	}
	
	/**
	 * The function returns design data.
	 * 
	 * @access public
	 * @return array The design data.
	 */
	public function getDesign()
	{
		if ( !is_array( $this->Design ) )
		{
			$this->Design = $this->Design ? unserialize( $this->Design ) : array();
		}
		return $this->Design;
	}
	
	/**
	 * The function returns frame data.
	 * 
	 * @access public
	 * @return array The frame data.
	 */
	public function getFrame()
	{
		if ( !is_array( $this->Frame ) )
		{
			$this->Frame = $this->Frame ? unserialize( $this->Frame ) : array();
		}
		return $this->Frame;
	}
	
	/**
	 * The function returns last added Prouds.
	 * 
	 * @access public
	 * @param int $limit The limit of result.
	 * @param mixed $category The Category object or id or layout.
	 * @return array The Prouds.
	 */
	public static function getLastProuds( $limit = 10, $category = null )
	{
		$Proud = new self();
		$params = array();
		if ( $category instanceof Proud_Layout )
		{
			$params[] = 'CategoryId = '.$category->getCategory()->Id;
		}
		return $Proud->findShortList( $params, 'Position desc', 0, $limit );
	}
        
        public static function getLastProudsId( $limit = 10, $category = null )
	{
		$Proud = new self();
		$params = array();
		if ( $category != null )
		{
			$params[] = 'CategoryId = '.$category;
		}
              
		return $Proud->findShortList( $params, 'Position desc', 0, $limit );
	}
	
}
