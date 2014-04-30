<?php

class HelperMenuHelper extends AppHelper {

	var $helpers = array('Html', 'Ajax', 'Session');

	public function generateMenu($menus = array(), $k = 0) {
		$return = '';

		if (!empty($menus)) {
			$return .= '<ul style="' . ($k != 0 ? 'display:none' : '') . '">';
			
				foreach ($menus as $menu) {

					#	Verifica se menu esta disponivel para visualização do usuário				
					if ( !$this->Session->read( 'Auth.User' ) && !$menu['Menu']['visible'] ){
						continue;
					}

					$hasChildren = !empty($menu['children']);

					$return .= '<li class="' . ($hasChildren ? 'sub-menu' : '') . '">';

						# Seta a url default caso seja página estática
	  				if ($menu['Menu']['static']) :
	  					$menu['Menu']['plugin'] = 'cms';
	  					$menu['Menu']['controller'] = 'menus';
	  					$menu['Menu']['action'] = 'getContent';
	  					$menu['Menu']['params'] = $menu['Menu']['id'];
	  				endif;

		  				# Pega a url do array $menu, independente de sendo estática ou não
  					$url = array(
							'plugin' => $menu['Menu']['plugin'],
							'controller' => str_replace('_controller', '',Inflector::underscore($menu['Menu']['controller'])),
							'action' => $menu['Menu']['action'],
							$menu['Menu']['params']
						);

						# Se for ajax, seta as opções e utiliza o helper Ajax
	  				if ($menu['Menu']['ajax']) :
	  					
							$options = array(
		  						'update' => 'content',
		  						'url' => $url,
	  							'form' => 'get',
	  							'indicator' => 'ajax-loader'
							);

	  					$return .= $this->Ajax->link($menu['Menu']['name'], array(), $options);

	  				else:

	  					# Senão, só coloca o link na tela.
	  					$return .= $this->Html->link($menu['Menu']['name'], $url, array('disabled' => $hasChildren));

	  				endif;

	  				if ($hasChildren) :
	  					$return .= $this->generateMenu($menu['children'], ++$k);
	  				endif;

					$return .= '</li>';

				}
			$return .= '</ul>';
		}

		return $return;
	}

}

?>