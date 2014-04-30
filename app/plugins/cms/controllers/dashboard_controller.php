<?php

class DashboardController extends CmsAppController {

	public $name = 'Dashboard';

	public $styles = array(
		'../cms/css/dashboard/dashboard.min'
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('styles', $this->styles);
	}

	public function index() {


		$items = array(
			array(
				'title' => 'Menus',
				'url' => array(
					'plugin' => 'cms',
					'controller' => 'menus',
					'action' => 'index'
				),
				'image' => '../cms/img/menu.png',
				'escape' => false
			)
		);

		if ($this->isAdmin()) {

			$items[] = array(
				'title' => 'Usuários',
				'url' => array(
					'plugin' => 'cms',
					'controller' => 'users',
					'action' => 'index'
				),
				'image' => '../cms/img/users.png',
				'escape' => false
			);

			$items[] = array(
				"title" => "Salvar Estrutura",
				"url"   => array(
					"plugin" => "cms",
					"controller" => "check_points",
					"action" => "save"
				),
				"image" => '../cms/img/backup_menu.png',
				"escape" => false
			);

			$items[] = array(
				               "title" => "Restaurar Estrutura",
				               "url"   => array(
				                                 "plugin" => "cms",
				                                 "controller" => "check_points",
				                                 "action" => "restore"
				                                ),
				               "image" => '../cms/img/restore_menu.png',
				               "escape" => false
			);
			$items[] = array(
					             "title"  => "Preferências",
					             "url"    => array(
					               "plugin"     => "cms",
					             	 "controller" => "configuracoes",
					             	 "action"     => "index"
					             ),
					             "image"  => '../cms/img/menu.png',
					             "escape" => false
			);
		}

		$this->set('items', $items);

	}

}

?>