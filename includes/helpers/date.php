<?

/**
 * The Date helper class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Date
{
	
	private static $months = array(
		'January'		=> array( 'Январь', 'Января' ),
		'February'		=> array( 'Февраль', 'Февраля' ),
		'March'			=> array( 'Март', 'Марта' ),
		'April'			=> array( 'Апрель', 'Апреля' ),
		'May'			=> array( 'Май', 'Мая' ),
		'June'			=> array( 'Июнь', 'Июня' ),
		'July'			=> array( 'Июль', 'Июля' ),
		'August'		=> array( 'Август', 'Августа' ),
		'September'		=> array( 'Сентябрь', 'Сентября' ),
		'October'		=> array( 'Октябрь', 'Октября' ),
		'November'		=> array( 'Ноябрь', 'Ноября' ),
		'December'		=> array( 'Декабрь', 'Декабря' ),
	);
	
	/**
	 * The function returns date with formatted month.
	 * 
	 * @static
	 * @access public
	 * @param string $date The date.
	 * @param bool $declension If TRUE returns month in declension.
	 * @return string The formatted date.
	 */
	public static function formatMonth( $date, $declension = false )
	{
		$replace = array();
		foreach ( self::$months as $source => $target )
		{
			$replace[ $source ] = $target[ $declension ? 1 : 0 ];
		}
		return strtr( $date, $replace );
	}
	
	public static function months( $declension = false )
	{
		$result = array();
		$i = 0;
		foreach ( self::$months as $target )
		{
			$result[ ++$i ] = $target[ $declension ? 1 : 0 ];
		}
		return $result;
	}
	
}
