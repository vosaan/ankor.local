<?

/**
 * The String helper class.
 * 
 * @version 0.1
 */
class String
{

	/**
	 * The function returns random string with passed length.
	 * 
	 * @static
	 * @access public
	 * @param int $length The string length.
	 */
	public static function random( $length )
	{
		if ( $length <= 0 )
		{
			return null;
		}
		$string = '';
		for ( $i = 0; $i < ceil( $length / 40 ); $i++ )
		{
			$string .= sha1( microtime().rand( 1, 10000 ).date( 'Ymd' ) );
		}
		return substr( $string, 0, $length );
	}

	/**
	 * The function returns string converted with first upper case.
	 * 
	 * @static
	 * @access public
	 * @param string $string The input string.
	 * @param string $delimiter The glue string.
	 * @param string $separator The words delimiter.
	 * @return string
	 */
	public static function toFirstCase( $string, $delimiter = ' ', $separator = ' ' )
	{
		$arr = explode( $separator, $string );
		for ( $i = 0, $len = count( $arr ); $i < $len; $i++ )
		{
			$arr[ $i ] = mb_strtoupper( mb_substr( $arr[ $i ], 0, 1, Config::get( 'encoding', 'utf-8' ) ), Config::get( 'encoding', 'utf-8' ) )
				.mb_substr( $arr[ $i ], 1, mb_strlen( $arr[ $i ] ) - 1, Config::get( 'encoding', 'utf-8' ) );
		}
		return implode( $delimiter, $arr );
	}
	
	/**
	 * The function returns string converted to link case 
	 * (all symbols are lower case).
	 * 
	 * @static
	 * @access public
	 * @param string $string The input string.
	 * @param string $delimiter The glue string.
	 * @param string $separator The words delimiter.
	 * @return string
	 */
	public static function toLinkCase( $string, $delimiter = '-', $separator = ' ' )
	{
		$arr = explode( $separator, $string );
		for ( $i = 0, $len = count( $arr ); $i < $len; $i++ )
		{
			$arr[ $i ] = urlencode( mb_strtolower( $arr[ $i ], Config::get( 'encoding', 'utf-8' ) ) );
		}
		return implode( $delimiter, $arr );
	}
	
	/**
	 * The function returns string converted to camel case 
	 * (all symbols are lower case but first of each word is upper case).
	 * 
	 * @static
	 * @access public
	 * @param string $string The input string.
	 * @param string $delimiter The glue string.
	 * @param string $separator The words delimiter.
	 * @return string
	 */
	public static function toCamelCase( $string, $delimiter = '', $separator = ' ' )
	{
		$arr = explode( $separator, $string, 2 );
		$arr[0] = mb_strtolower( $arr[0], Config::get( 'encoding', 'utf-8' ) );
		if ( isset( $arr[1] ) )
		{
			$arr[1] = self::toFirstCase( $arr[1], $delimiter, $separator );
		}
		return implode( $delimiter, $arr );
	}
	
	/**
	 * The function cuts left part of string if it equals left part.
	 * 
	 * @static
	 * @access public
	 * @param string $string The string for cut.
	 * @param string $left The left part to cut.
	 * @return string The cutted string.
	 */
	public static function cutLeft( $string, $left = '' )
	{
		$len = mb_strlen( $left, Config::get( 'encoding', 'utf-8' ) );
		if ( mb_substr( $string, 0, $len, Config::get( 'encoding', 'utf-8' ) ) == $left )
		{
			$string = mb_substr( $string, $len, mb_strlen( $string, Config::get( 'encoding', 'utf-8' ) ), 
				Config::get( 'encoding', 'utf-8' ) );
		}
		return $string;
	}
	
	/**
	 * The function converts json encoded data with replacing tags.
	 * For PHP version less 5.3.0
	 * 
	 * @static
	 * @access public
	 * @param mixed $data The object data.
	 * @return string The JSON response.
	 */
	public static function json_encode( $data )
	{
		return strtr( json_encode( $data ), array(
			'<'	=> '\u003C',
			'>' => '\u003E',
		) );
	}
	
}
