<?

class Cron
{
	// Remove expired temporary designs
	public static function temp()
	{
		
	}

	// Remove expired cart items
	public static function cart()
	{
		
	}

	// Sitemap generator
	public static function sitemap()
	{
		$xml = new SimpleXMLElement('<urlset />');
		$xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
		
		$_GET = array();

		URL::absolute(true);

		$links = array();
		$Page = new Content_Page();
		foreach ( $Page->findShortList(array('IsEnabled = 1', 'Link <> '), 'Position asc') as $Page )
		{
			if(!in_array($Page->Link, $links))
			{
				$links[]=$Page->Link;
				foreach ( $Page->getController()->getSitemapNode() as $link )
				{
					$node = $xml->addChild('url');
					$node->addChild('loc', $link);
				}
			}
		}
		$xml = $xml->asXML();
		$xml = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $xml);
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml);
		$xml = $dom->saveXML();
		file_put_contents(FILES_DIR . '/sitemap.xml', $xml);
	}

	// Sales of the day
	/*
	  public static function sales()
	  {
	  $arr = array();
	  $Player = new Player();
	  $params = array();
	  $params[] = '* Username in ("Monashka", "DonPrinton", "Drawer", "Creator", "Lider")';
	  foreach ( $Player->findList( $params ) as $Player )
	  {
	  $arr[] = $Player->Id;
	  }

	  $Design = new Product_Design();
	  $Design->clearDiscount();

	  $params = array();
	  $params[] = 'IsCustom = 0';
	  $params[] = 'IsApproved = 1';
	  $params[] = 'IsDisabled = 0';
	  $params[] = '* UserId in ('.implode( ',', $arr ).')';
	  $arr = array();
	  foreach ( $Design->findResult( 'Id', $params, 'Views asc', 0, 400 ) as $Design )
	  {
	  $arr[] = $Design->Id;
	  }
	  shuffle( $arr );
	  $arr = array_slice( $arr, 0, 24 );
	  $params = array();
	  $params[] = '* Id in ('.implode( ',', $arr ).')';
	  $Design->updateList( array( 'Discount' => 20 ), $params );
	  }

	  // Send email notifications to unpaid orders
	  public static function unpaid()
	  {
	  $Order = new Order();
	  $params = array();
	  $params[] = $Order->getParam('new');
	  $params[] = $Order->getParam('mustpay');
	  foreach ( $Order->findList( $params ) as $Order )
	  {
	  $Order->sendNotify();
	  }
	  }

	  // Calculate designs sales
	  public static function calc()
	  {
	  $Design = new Product_Design();
	  $Design->calcSales();
	  }
	 */
}
