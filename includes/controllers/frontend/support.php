<?

/**
 * The Support controller class.
 * 
 * @author Yarick.
 * @version 0.1
 */
class Controller_Frontend_Support extends Controller_Frontend
{
	
	/**
	 * @see parent::getName()
	 */
	public function getName()
	{
		return 'Поддержка';
	}
	
	/**
	 * The support index handler.
	 * 
	 * @access public
	 * @return string The HTML code.
	 */
	public function index()
	{
		$Page = $this->getContentPage();
		$Award = new Award();
		$Faq = new Faq();
		$Article = new Article();
		$params = array();
		$params[] = 'Type = '.Article::ARTICLE;
		$params[] = 'PostedAt < '.time();
		$params[] = $Article->getParam( 'reference', $Page );
		
		$this->getView()->set( array(
			'Documents'	=> $Page->getDocuments(),
			'Faqs'		=> $Faq->findList( array(), 'Position asc' ),
			'Papers'	=> $Award->findList( array( 'Type = '.Award::SUPPORT ), 'Position asc' ),
			'Articles'	=> $Article->findList( $params, 'PostedAt desc' ),
		) );
		return $this->getView()->render();
	}
	
	/**
	 * The resume application handler.
	 * 
	 * @access public
	 * @return string The JSON response.
	 */
	public function app()
	{
		$response = array('result' => 0, 'posted' => 0);
		if ( Request::get('Name') && Request::get('Question') )
		{
			$response['posted'] = 1;
			$Email = new Email_Faq();
			if ( $Email->send() )
			{
				$response['result'] = 1;
			}
			else
			{
				$response['msg'] = 'Ошибка отправки e-mail';
			}
		}
		return $this->outputJSON( $response );
	}
	public function noMethod()
	{
		return $this->halt();
	}
	
}
