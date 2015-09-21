<?

/**
 * The Object class.
 * Using to access models in database.
 * 
 * @abstract
 * @author Yarick.
 * @version 0.2
 */
abstract class Object
{
	
	private static $instanceCount = 0;
	private static $cache = array();

	/**
	 * The function returns array of primary keys.
	 * 
	 * @abstract
	 * @access protected
	 * @return array The primary keys array.
	 */
	abstract protected function getPrimary();
	
	/**
	 * The function returns the model table name.
	 * 
	 * @abstract 
	 * @access protected
	 * @return string The table name.
	 */
	abstract protected function getTableName();
	
	/**
	 * The object constructor.
	 * 
	 * @access public
	 */
	public function __construct()
	{
		self::$instanceCount++;
	}
	
	/**
	 * The function returns Database object with current connection.
	 * 
	 * @access protected
	 * @return object The Database object.
	 */
	protected function db()
	{
		return Database::getInstance();
	}
	
	/**
	 * The function sets object data array to object.
	 * 
	 * @access public
	 * @param mixed $data The data to set, must be associated array or object.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function set( $data )
	{
		if ( is_array( $data ) )
		{
			foreach ( $data as $key => $value )
			{
				if ( property_exists( $this, $key ) )
				{
					$this->$key = $value;
				}
			}
			return true;
		}
		if ( is_object( $data ) )
		{
			foreach ( get_object_vars( $data ) as $key => $value )
			{
				if ( property_exists( $this, $key ) )
				{
					$this->$key = $value;
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * The function sets object data from posted array.
	 * 
	 * @access public
	 * @param array $data The posted data to set.
	 */
	public function setPost( array $data = array() )
	{
		return $this->set( $data );
	}

	/**
	 * The function returns object keys with their values.
	 * 
	 * @access public
	 * @return array The object fields.
	 */
	public function getFields()
	{
		$result = array();
		foreach ( get_object_vars( $this ) as $key => $value )
		{
			if ( substr( $key, 0, 1 ) != '_' )
			{ 
				$result[ $key ] = $value;
			}
		}
		ksort( $result );
		return $result;
	}
	
	/**
	 * The function returns TRUE if primary key is not set, otherwise FALSE.
	 * 
	 * @access protected
	 * @return bool TRUE if primary key is empty, otherwise FALSE.
	 */
	protected function isPrimaryEmpty()
	{
		foreach ( $this->getPrimary() as $field )
		{
			if ( !empty( $this->$field ) )
			{
				return false;
			}
		}
		return true;
	}
	
	/**
	 * The function returns TRUE if auto cache is enabled for current object, otherwise FALSE.
	 * 
	 * @access protected
	 * @return bool TRUE if auto cache is enabled.
	 */
	protected function hasAutoCache()
	{
		return true;
	}
	
	/**
	 * The function flushes (clear) current item cache data.
	 * 
	 * @access protected
	 * @return bool TRUE on success, FALSE on failure.
	 */
	protected function flushCache()
	{
		if ( !property_exists( $this, 'Id' ) || !$this->Id )
		{
			return false;
		}
		if ( isset( self::$cache[ get_class( $this ) ][ $this->Id ] ) )
		{
			self::$cache[ get_class( $this ) ][ $this->Id ] = null;
			return true;
		}
		return false;
	}

	/**
	 * The function add cache object to global cache.
	 * 
	 * @access protected
	 * @param object $Object The Object to add.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	protected function pushCache( Object $Object )
	{
		if ( !$this->hasAutoCache() || !property_exists( $Object, 'Id' ) || !$Object->Id )
		{
			return false;
		}
		if ( !isset( self::$cache[ get_class( $this ) ] ) )
		{
			self::$cache[ get_class( $this ) ] = array();
		}
		self::$cache[ get_class( $this ) ][ $Object->Id ] = $Object;
		return true;
	}
	
	/**
	 * The function returns Object from cache if it exists already.
	 * 
	 * @access public
	 * @param array $params The clause.
	 * @return mixed The cached Object or FALSE.
	 */
	protected function popCache( $params = array() )
	{
		if ( $this->hasAutoCache() && count( $params ) == 1 )
		{
			foreach ( $params as $param )
			{
				$arr = explode( ' ', $param, 3 );
				if ( $arr[0] == 'Id' && $arr[1] == '=' && count( $arr ) == 3 )
				{
					if ( isset( self::$cache[ get_class( $this ) ][ $arr[2] ] ) )
					{
						return self::$cache[ get_class( $this ) ][ $arr[2] ];
					}
				}
			}
		}
		return false;
	}
	
	/**
	 * The function saves current object to database.
	 * 
	 * @access public
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function save()
	{
		if ( $this->isPrimaryEmpty() )
		{
			return $this->saveNew();
		}
		else
		{
			if ( $this->hasAutoCache() )
			{
				$this->flushCache();
			}
			$this->db()->update( $this->getTableName(), $this->getFields(), $this->getPrimaryClause() );
			return $this->db()->getError() == '00000';
		}
	}
	
	/**
	 * The function saves current object to database.
	 * Always saves as new object.
	 *
	 * @access public
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function saveNew()
	{
		$this->db()->insert( $this->getTableName(), $this->getFields() ) > 0;
		if ( $this->db()->getError() == '00000' )
		{
			if ( in_array( 'Id', array_keys( get_object_vars( $this ) ) ) )
			{
				if ( property_exists( $this, 'Id' ) )
				{
					$id = $this->db()->getLastId();
					if ( $id )
					{
						$this->Id = $id;
					}
				}
			}
			$Item = $this->findItem( $this->getPrimaryClause() ) ;
			$this->set( $Item->getFields() );
			return true;
		}
		return false;
	}
	
	/**
	 * The function returns array of primary clause.
	 * 
	 * @access protected
	 * @return array The primary clause array.
	 */
	protected function getPrimaryClause()
	{
		$result = array();
		foreach ( $this->getPrimary() as $field )
		{
			$result[] = $field.' = '.$this->$field;
		}
		return $result;
	}
	
	/**
	 * The function deletes current object from database.
	 * 
	 * @access public
	 * @return bool TRUE on success, FALSE on failure
	 */
	public function drop()
	{
		return $this->dropList( $this->getPrimaryClause() );
	}
	
	/**
	 * The function deletes object rows from database with passed clause.
	 * 
	 * @access public
	 * @param array $params The clause.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function dropList( $params = array() )
	{
		return $this->db()->delete( $this->getTableName(), $params );
	}
	
	/**
	 * The function prints out error if it occured.
	 * 
	 * @access protected
	 */
	protected function showError()
	{
		if ( $this->db()->getError() != '00000' )
		{
			echo get_class( $this ).': '.end( $this->db()->getLog() )."\n".$this->getError();
			exit;
		}
	}

	/**
	 * The function finds an object item with passed clause.
	 * 
	 * @access public
	 * @param array $params The clause.
	 * @return object The current class object.
	 */
	public function findItem( $params = array() )
	{
		$Object = $this->popCache( $params );
		if ( $Object !== false )
		{
			return $Object;
		}
		$arr = $this->db()->select( $this->getTableName(), '*', $params, null, 0, 1 );
		$this->showError();
		$arr = $this->processDbResult( $arr, 'item' );
		if ( count( $arr ) )
		{
			$Object = array_pop( $arr );
			$this->pushCache( $Object );
		}
		else
		{
			$name = get_class( $this );
			$Object = new $name();
		}
		return $Object;
	}

	/**
	 * The function finds and object items with passed clause.
	 * 
	 * @access public
	 * @param array $params The clause.
	 * @param string $order The order string.
	 * @param int $offset The offset.
	 * @param int $limit The limit.
	 * @return array The array of current class objects.
	 */
	public function findList( $params = array(), $order = null, $offset = null, $limit = null )
	{
		$arr = $this->db()->select( $this->getTableName(), '*', $params, $order, $offset, $limit );
		$this->showError();
		return $this->processDbResult( $arr, 'list' );
	}
	
	/**
	 * The function executes query an returns array of objects.
	 *
	 * @access public
	 * @param string $query The SQL query string.
	 * @return array The result.
	 */
	public function query( $query )
	{
		$arr = $this->db()->query( $query );
		$this->showError();
		return $this->processDbResult( $arr, 'list' );
	}
	
	/**
	 * The function processes database result to array of objects.
	 *
	 * @access protected
	 * @param array $data The database result data.
	 * @param string $type The type of triggered function: item | list | result.
	 * @param string $className The object class name, by default gets itself class name $this.
	 */
	protected function processDbResult( array $data, $type = 'list', $className = null )
	{
		if ( !$className )
		{
			$className = get_class( $this );
		}
		$result = array();
		foreach ( $data as $item )
		{
			$Object = new $className();
			$Object->set( $item );
			$Object->init( $type );
			$result[] = $Object;
			if ( $type == 'item' )
			{
				break;
			}
		}
		return $result;
	}
	
	/**
	 * The function returns count of object rows with passed clause.
	 * 
	 * @access public
	 * @param array $params The clause.
	 * @return int The count of rows.
	 */
	public function findSize( $params = array() )
	{
		$arr = $this->db()->select( $this->getTableName(), 'count(*) as count', $params );
		foreach ( $arr as $item )
		{
			if ( isset( $item['count'] ) )
			{
				return $item['count'];
			}
		}
		$this->showError();
		return null;
	}
	
	/**
	 * The function finds and object items with passed clause.
	 * 
	 * @access public
	 * @param string $columns The columns to fetch.
	 * @param array $params The clause.
	 * @param string $order The order string.
	 * @param int $offset The offset.
	 * @param int $limit The limit.
	 * @return array The array of current class objects.
	 */
	public function findResult( $columns = '*', $params = array(), $order = null, $offset = null, $limit = null )
	{
		$result = array();
		if ( !isset( $columns ) )
		{
			$columns = '*';
		}
		$arr = $this->db()->select( $this->getTableName(), $columns, $params, $order, $offset, $limit );
		$this->showError();
		return $this->processDbResult( $arr, 'result' );
	}
	
	/**
	 * The function finds and object items with passed clause.
	 * 
	 * @access public
	 * @param string $columns The columns to fetch.
	 * @param array $params The clause.
	 * @param string $order The order string.
	 * @param int $offset The offset.
	 * @param int $limit The limit.
	 * @return array The array of current class objects.
	 */
	public function findArray( $columns = '*', $params = array(), $order = null, $offset = null, $limit = null )
	{
		if ( !isset( $columns ) )
		{
			$columns = '*';
		}
		return $this->db()->select( $this->getTableName(), $columns, $params, $order, $offset, $limit );
	}
	
	/**
	 * @see Database::getError()
	 */
	public function getError()
	{
		return $this->db()->getError( true );
	}
	
	/**
	 * The function returns array of rules for object fields.
	 * 
	 * @access public
	 * @return array The array of rules.
	 */
	public function getTestRules()
	{
		return array();
	}
	
	/**
	 * The function returns array of file uploading rules (parameters).
	 * 
	 * @access public
	 * @return array The info array.
	 */
	public function getUploadFileInfo()
	{
		return null;
		
		// as example
		return array(
			'sizes'			=> array( '800x600', '200x200' ),
			'forceResize'	=> array( true, false ),
			'quality'		=> array( 75, 90 ),
			'crop'			=> array( 'North', 'Center' ),
			'deny'			=> array( 'exe', 'php', 'pl', 'cgi', 'asp', 'php3', 'php4', 'php5' ),
			'allow'			=> array( 'jpg', 'gif', 'png' ),
			'folder'		=> 'my_object',
			'folderFormat'	=> '%05d',
			'folderLimit'	=> 1000,
			'urlFormat'		=> true,
			'timeAffix'		=> null,
			'dropOrig'		=> true,
			'extension'		=> 'jpg',
		);
	}
	
	/**
	 * The function returns file url for object.
	 * 
	 * @access public
	 * @return string The file url.
	 */
	public function getFileUrl( $class, $folder, $index )
	{
		return null;
	}
	
	/**
	 * The event on initializing Object on getting from database.
	 * 
	 * @access public
	 * @param string $type The type of triggered function: item | list | result.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public function init( $type = 'list' )
	{
		return true;
	}
	
	/**
	 * The function returns last field value in object list.
	 * 
	 * @static
	 * @access public
	 * @param object $Object The Object.
	 * @param string $field The field name.
	 * @param array $params The clause.
	 * @return mixed The last field value.
	 */
	public static function getLast( Object $Object, $field, array $params = array() )
	{
		if ( !property_exists( $Object, $field ) )
		{
			return null;
		}
		foreach ( $Object->findList( $params, $field.' desc', 0, 1 ) as $Item )
		{
			return $Item->$field;
		}
		return null;
	}
	
	/**
	 * The function returns count of object instances.
	 * 
	 * @static
	 * @access public
	 * @return int The count object instances.
	 */
	public static function getInstanceCount()
	{
		return self::$instanceCount;
	}
	
	/**
	 * The function returns hashed password.
	 * 
	 * @static
	 * @access public
	 * @param string $password The password.
	 * @return string The hashed password.
	 */
	public static function hashPassword( $password )
	{
		return sha1( $password );
	}
	
	/**
	 * The function returns associated array from array of Objects.
	 *
	 * @static
	 * @access public
	 * @param array $input The array of Objects.
	 * @param string $key The key field name.
	 * @param string $value The value field name.
	 * @return array The array.
	 */
	public static function convertArray( array $input, $key, $value )
	{
		$result = array();
		foreach ( $input as $Item )
		{
			if ( !$Item instanceof Object )
			{
				continue;
			}
			if ( property_exists( $Item, $key ) && property_exists( $Item, $value ) )
			{
				$result[ $Item->$key ] = $Item->$value;
			}
		}
		return $result;
	}
	
	/**
	 * The function returns string representation of object.
	 * 
	 * @access public
	 * @return string The object string.
	 */
	public function __toString()
	{
		$result = 'Object: '.get_class( $this )." {\n";
		foreach ( $this->getFields() as $name => $value )
		{
			$result .= sprintf( "\t%-20s%s\n", $name, $value );
		}
		$result .= "}\n";
		return $result;
	}

}

