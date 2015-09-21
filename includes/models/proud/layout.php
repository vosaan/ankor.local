<?

/**
 * The Proud Layout class.
 * 
 * @author Yarick
 * @version 0.1
 */
abstract class Proud_Layout
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
	 * The function returns Proud Category for current Layout.
	 * 
	 * @access public
	 * @return object The Proud Category.
	 */
	public function getCategory()
	{
		$Category = new Proud_Category();
		return $Category->findItem( array( 'Layout = '.get_class( $this ) ) );
	}
	
	/**
	 * The function returns Proud Layouts.
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
			$name = 'Proud_Layout_'.basename( $file, '.php' );
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