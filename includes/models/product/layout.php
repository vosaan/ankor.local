<?

/**
 * The Product Layout class.
 * 
 * @author Yarick
 * @version 0.1
 */
abstract class Product_Layout
{
	
	/**
	 * The function reutnrs current Layout name.
	 * 
	 * @abstract
	 * @access public
	 * @return string The layout name.
	 */
	abstract public function getName();
	
	/**
	 * The function reutnrs current Layout css class name.
	 * 
	 * @abstract
	 * @access public
	 * @return string The layout css class name.
	 */
	abstract public function getCssName();
	
	/**
	 * The function returns Product Category for current Layout.
	 * 
	 * @access public
	 * @return object The Product Category.
	 */
	public function getCategory()
	{
		$Category = new Product_Category();
		return $Category->findItem( array( 'Layout = '.get_class( $this ) ) );
	}
	
	/**
	 * The function returns Product Layouts.
	 * 
	 * @static
	 * @access public
	 * @param bool $assoc If TRUE returns associated array with name in value, otherwise value is object.
	 * @return array The layouts.
	 */
	public static function getLayouts( $assoc = false )
	{
		$result = array();
		foreach ( File::readDir( dirname( __FILE__ ).'/layout' ) as $file )
		{
			$name = 'Product_Layout_'.basename( $file, '.php' );
			$class = new $name();
			if ( $assoc )
			{
				$result[ get_class( $class ) ] = $class->getName();
			}
			else
			{
				$result[get_class( $class ) ] = $class;
			}
		}
		return $result;
	}
	
}