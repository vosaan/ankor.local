<?

class View_Backend extends View_Base
{
	
	protected function getTitle()
	{
		return 'Моя админка';
	}
	
	protected function getUser()
	{
		return $this->getController()->getUser();
	}
	
	protected function getSeoTitle()
	{
		return $this->getController()->getTitle();
	}
	
	protected function getLayoutDir()
	{
		return parent::getLayoutDir().'/backend';
	}
	
	protected function isAccess()
	{
		return $this->getController()->isAccess();
	}
	
	protected function htmlMenu()
	{
		return $this->includeLayout( 'block/menu.html' );
	}

	/**
	 * The function returns header HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlHeader()
	{
		return $this->includeLayout( 'block/header.html', array( 
			'Current' => str_replace( _L('Controller_Backend'), '', $this->getLink() ), 
			'Column' => array(
				array(
					'/pages'		=> 'Страницы',
					'/tags'			=> 'Темы',
					'/news'			=> 'Новости',
					'/articles'		=> 'Публикации',
					'/interviews'	=> 'Интервью',
				),
				array(
					'/documents'	=> 'Документы и бланки',
					'/laws'			=> 'Законы',
					'/faq'			=> 'Вопросы и ответы',
					'/links'		=> 'Ссылки',
					'/polls'		=> 'Голосования',
				),
				array(
					'/settings'		=> 'Настройки сайта',
				),
		) ) );
	}

	/**
	 * The function returns footer HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlFooter()
	{
		return $this->includeLayout( 'block/footer.html' );
	}
	
	/**
	 * The function returns paging HTML code.
	 * 
	 * @access protected
	 * @return string The HTML code.
	 */
	protected function htmlPaginator()
	{
		return $this->includeLayout( 'block/paginator.html' );
	}

	public function index()
	{
		return $this->includeLayout( 'view/index.html' );
	}
	
	public function login()
	{
		return $this->includeLayout( 'view/login.html' );
	}

}
