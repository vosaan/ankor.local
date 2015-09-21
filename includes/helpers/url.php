<?

/**
 * The static URL class for build links.
 * 
 * @author Yarick.
 * @version 0.3
 */
class URL
{
	
	private static $absolute = false;
	
	/**
	 * The function sets format of URL - absolute or relative.
	 *
	 * @static
	 * @access public
	 * @param bool $bool If TRUE all URL will be returned with absolute path (including host),
	 * if NULL just returns current value.
	 */
	public static function absolute( $bool = null )
	{
		if ( $bool !== null )
		{
			self::$absolute = (bool)$bool;
		}
		return self::$absolute;
	}
	
	/**
	 * The function returns correct absolute or relative URL.
	 *
	 * @static
	 * @access public
	 * @param string $link The link.
	 * @return string The URL.
	 */
	public static function abs( $link )
	{
		if ( preg_match( '/^(http|https|ssl|ftp):\/\//', $link ) )
		{
			return $link;
		}
		if ( !self::$absolute )
		{
			return '/'.ltrim( $link, '/' );
		}
		return Runtime::get('HTTP_PROTOCOL').Runtime::get('HTTP_HOST').'/'.ltrim( $link, '/' );
	}
	
	/**
	 * The function returns GET query from current variables.
	 * 
	 * @static
	 * @access public
	 * @param string $questionMark The first character of query.
	 * @return string The query.
	 */
	public static function restoreGet( $questionMark = '?' )
	{
		$link = '';
		if ( count( $_GET ) )
		{
			$link .= $questionMark.http_build_query( $_GET );
		}
		return $link;
	}
	
	/**
	 * The function returns link for current object.
	 * 
	 * @static
	 * @access public
	 * @param mixed $Object The object.
	 * @param string $tag The tag.
	 * @param bool $restoreGet If TRUE returns link with GET parameters.
	 * @return string The URL.
	 */
	public static function get( $Object, $tag = '', $restoreGet = false )
	{
		$link = '';
		if ( $restoreGet && count( $_GET ) )
		{
			$link .= strpos( $link, '?' ) === false ? '?' : '&';
			$link .= http_build_query( $_GET );
		}
		if ( $Object instanceof Content_Page )
		{
			if ( !$Object->Link && $Object->Children )
			{
				foreach ( Content_Page::getChildren( $Object->Id ) as $Child )
				{
					if ( $Child->Link )
					{
						return self::abs( $Child->Link );
					}
				}
			}
			return self::abs( $Object->Link );
		}
		if ( $Object instanceof Paginator )
		{
			$data = $_GET;
			if ( isset( $data['page'] ) )
			{
				unset( $data['page'] );
			}
			$data['page'] = $tag;
			$arr = explode( '?', Request::get('REQUEST_URI', '/', 'SERVER') );
			return self::abs( $arr[0].'?'.http_build_query( $data ) );
		}
		if ( $Object instanceof Article )
		{
			if ( $tag )
			{
				if ( $tag instanceof Product )
				{
					return self::abs( $Object->getParentLink().'/view/'.$Object->Id.'?backto='.Article_Reference::PRODUCT.'-'.$tag->Id.self::restoreGet('&') );
				}
				else
				{
					return self::abs( $Object->getParentLink().'?tag='.urlencode( $tag instanceof Tag ? $tag->Name : $tag ).self::restoreGet('&') );
				}
			}
			else
			{
				return self::abs( $Object->getParentLink().'/view/'.$Object->Id.self::restoreGet() );
			}
			return self::abs( $Object->getParentLink().'/view/'.$Object->Id.self::restoreGet() );
		}
		if ( $Object instanceof Article_Tag )
		{
			return self::abs( _L('Controller_Frontend_Articles').'?tag='.urlencode( $Object->getTag()->Name ).self::restoreGet('&') );
		}
		if ( $Object instanceof Gallery )
		{
			return self::abs( _L('Controller_Frontend_Gallery').'/view/'.$Object->Id.self::restoreGet() );
		}
		if ( $Object instanceof Product )
		{
			/*
			$Layout = $Object->getCategory()->getLayout();
			$url = _L('Controller_Frontend_Catalog');
			if ( $Layout instanceof Product_Layout_Hardware )
			{
				$url = _L('Controller_Frontend_Catalog_Hardware');
			}
			if ( $Layout instanceof Product_Layout_Product || $Layout instanceof Product_Layout_Custom )
			{
				$url = _L('Controller_Frontend_Catalog_Product');
			}
			if ( $Layout instanceof Product_Layout_Standard )
			{
				$url = _L('Controller_Frontend_Catalog_Standard');
			}
			if ( $Layout instanceof Product_Layout_Custom )
			{
				$url = _L('Controller_Frontend_Catalog_Custom');
			}
			 * 
			 */
			return self::abs( $Object->getCategory()->Slug.'/view/'.$Object->Id.self::restoreGet() );
		}
		if ( $Object instanceof Product_Category )
		{
			return self::abs( $Object->Slug.self::restoreGet() );
		}
                if ( $Object instanceof Proud )
		{
			return self::abs( 'clients/view/'.$Object->Id.self::restoreGet()  );
		}
		if ( $Object instanceof Product_Brand )
		{
			return self::abs( _L('Controller_Frontend_Brands').'/'.String::toLinkCase( $Object->Name ) );
		}
		if ( $Object instanceof Banner )
		{
			return self::abs( $Object->getURL() );
		}
		if ( $Object instanceof Subscription )
		{
			return self::abs( _L('Controller_Frontend_Articles').'/unsubscribe/'.$Object->getCode() );
		}
		if ( $Object instanceof Controller )
		{
			return self::abs( _L( $Object ) );
		}
		if ( is_string( $Object ) && $Object != '' )
		{
			return self::abs( $Object );
		}
		return self::abs( _L('Controller_Frontend') );
	}
	
	/**
	 * The function returns TRUE if Target object is current URL.
	 * 
	 * @static
	 * @access public
	 * @param object $Target The Target.
	 * @return bool TRUE on success, FALSE on failure.
	 */
	public static function on( $Target )
	{
		$uri = Runtime::get('REQUEST_URI');
		if ( $Target instanceof Content_Page )
		{
			return $Target->Link == $uri;
		}
		if ( $Target instanceof Article )
		{
			return $Target->Link == $uri;
		}
		return $Target == $uri;
	}
	
}
