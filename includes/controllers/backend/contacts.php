<?

/**
 * The Contacts controller class.
 * 
 * @author Yarick
 * @version 0.1
 */
class Controller_Backend_Contacts extends Controller_Backend_Standard
{
	
	/**
	 * @see parent::getModelName()
	 */
	protected function getModelName()
	{
		return 'Contact';
	}
	
	/**
	 * @see parent::getTitle()
	 */
	public function getTitle()
	{
		return 'Контакты';
	}
	
	/**
	 * The index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Contact = new Contact();
		$this->getView()->set( 'Contacts', $Contact->findList( array(), 'Position asc' ) );
		return $this->getView()->render();
	}
	
	/**
	 * The departments edit handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function change()
	{
		if ( isset( $_POST['submit'] ) )
		{
			if ( isset( $_POST['Depart'] ) )
			{
				$arr = array();
				foreach ( $_POST['Depart']['Name'] as $i => $value )
				{
					if ( $value )
					{
						$arr['Name'][ $i ] = $value;
						$arr['Email'][ $i ] = $_POST['Depart']['Email'][ $i ];
					}
				}
				$_POST['Config']['contact/departs'] = $arr;
			}
			if ( isset( $_POST['Config'] ) && is_array( $_POST['Config'] ) )
			{
				foreach ( $_POST['Config'] as $key => $value )
				{
					Configuration::setValue( $key, $value );
				}
			}
			return $this->halt();
		}
		return $this->getView()->render();
	}
	
	/**
	 * The map edit handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function map()
	{
		return $this->change();
	}
	
}
