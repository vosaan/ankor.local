<?

/**
 * The Catalog view class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class View_Frontend_Catalog extends View_Frontend
{
	
	private $columns;
	
	private function getColNames()
	{
		return array(
			'Width'		=> 'Ширина',
			'Height'	=> 'Длина',
			'HeightMax'	=> 'Высота&nbsp;по верхней точке',
			'HeightMin'	=> 'Высота&nbsp;по нижней точке',
			'SquareX'	=> 'Растояние меджу колоннами',
			'SquareY'	=> 'Растояние меджу колоннами',
		);
	}
	
	protected function getLetter( $col )
	{
		$arr = array(
			'Width'		=> 'a',
			'Height'	=> 'b',
			'HeightMax'	=> 'h1',
			'HeightMin'	=> 'h2',
			'SquareX'	=> 'z',
			'SquareY'	=> 'z2',
		);
		return isset( $arr[ $col ] ) ? $arr[ $col ] : null;
	}
	
	protected function getCols( array $models )
	{
		if ( $this->columns !== null )
		{
			return $this->columns;
		}
		$count = array();
		$arr = array_keys( $this->getColNames() );
		foreach ( $arr as $col )
		{
			$count[ $col ] = 0;
		}
		foreach ( $models as $Model )
		{
			foreach ( $arr as $col )
			{
				if ( $Model->$col )
				{
					$count[ $col ]++;
				}
			}
		}
		$result = array();
		foreach ( $count as $col => $value )
		{
			if ( $value )
			{
				$result[] = $col;
			}
		}
		$this->columns = $result;
		return $result;
	}
	
	protected function getColName( $col )
	{
		$arr = $this->getColNames();
		return isset( $arr[ $col ] ) ? $arr[ $col ] : null;
	}
	
	public function htmlItems( array $Products, Paginator $Paginator = null )
	{
		if ( $Paginator === null )
		{
			$Paginator = $this->get('Paginator');
		}
		return $this->includeLayout('view/catalog/items.html', array('Products' => $Products, 'Paginator' => $Paginator));
	}
	
	protected function htmlFilter( Product_Category $Category )
	{
		return $this->includeLayout('view/catalog/filter.html', array( 'Category', $Category ));
	}
	
	protected function htmlPricelist( Product_Category $Category )
	{
		return $this->includeLayout('view/catalog/pricelist.html', array( 'Category', $Category ));
	}
	
	public function htmlColors( array $Products )
	{
		return $this->includeLayout('view/catalog/colors.html', array('Products' => $Products) );
	}
	
	public function htmlUnits( array $Units )
	{
		return $this->includeLayout('view/catalog/units.html', array('Units' => $Units) );
	}
	
	public function index()
	{
		return $this->includeLayout('view/catalog/index.html');
	}
	
	public function category()
	{
		return $this->includeLayout('view/catalog/index.html');
	}
	
	public function view()
	{
		return $this->includeLayout('view/catalog/view.html');
	}
	
	public function custom()
	{
		return $this->includeLayout('view/catalog/custom.html');
	}	

	public function common()
	{
		return $this->includeLayout('view/catalog/common.html');
	}	
}
