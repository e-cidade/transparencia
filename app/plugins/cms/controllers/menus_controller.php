<?php

/**
	Controller de menus, CRUD e exibição em tela.
	@author Vitor Rocha da Silva <vitor@dbseller.com.br>
	@created 2013-06-18 13:00
*/

class MenusController extends CmsAppController {
	
	public $name = 'Menus';

	public $helpers = array('Cms.HelperMenu', 'Cms.Content');

	public $layout = 'cms';

	protected $allowedMethods = array(
		'principal', 'getContent'
	);

	public function index() {
		$this->layout = 'cms';

		$menus = $this->Menu->find( 'all', array( 
			'fields' => array(
				'id', 'name', 'visible'
			),
			'order' => 'lft' 
		));

		$this->set(compact('menus'));	
	}

	public function edit($id = null) {
		$this->layout = 'cms';
		$data =& $this->data;

		if (!empty($data)) {

			# Controle do plugin, controller e action que vem da tela,
			# Pois vem numa string só, aqui separa e coloca no model.
			if (!$data['Menu']['static']) {

				$paths = explode(' - ', $data['Menu']['path']);

				$compar = (count($paths) != 3);

				$data['Menu']['plugin'] = $compar ? '' : $paths[0];
				$data['Menu']['controller'] = $paths[$compar ? 0 : 1];
				$data['Menu']['action'] = $paths[$compar ? 1 : 2];

			} else {

				# Caso o usuário esteja fazendo upload de um arquivo
				if ($data['Menu']['upload']) {

					# Salva os nomes em variáveis
					$tmpFilename = $data['Menu']['_file']['tmp_name'];
					$filename = $data['Menu']['_file']['name'];

					# Verifica se ele realmente está fazendo um upload
					# Para caso seja um update em outro campo
					if ($tmpFilename) {

						# Colona o nome do arquivo no array para salvar no banco
						$data['Menu']['file'] = preg_replace('/\.[^.]+$/','',$filename) . '.ctp';

						# Aplica regra de validação on the fly no campo
						$this->Menu->validate['file'] = array(
							'rule' => 'notEmpty',
							'message' => 'Nome não pode ficar em branco.'
						);

					}
				}
			}

			# Seta todos os dados do POST no model
			$this->Menu->set($data);

			# Valida os dados, caso tenha algum validate específico
			if ($this->Menu->validates()) {
				try {

					# Verifica se é upload
					if (!empty($tmpFilename)) {

						# Utiliza a classe de File para manipular arquivos
						App::import('File', 'Utility');
						$f = new File($tmpFilename);

						App::import('Folder', 'Utility');
						new Folder(Configure::read('Cms.Sandbox.path'), true, 0777);

						# Copia o arquivo para a pasta setada nas configurações
						$f->copy(Configure::read('Cms.Sandbox.path') . $data['Menu']['file']);
					}

					$this->Menu->save();
					$this->setFlash('Menu salvo com sucesso.', 'alert alert-success');
					$this->redirect('index');
				} catch(PDOException $e) {
					$this->setFlash('Falha ao salvar o Menu. Tente novamente.', 'alert alert-error');
				}

			} else {
				$this->setFlash('Alguns campos estão inválidos', 'alert alert-error');
			}

		} else {
			if ($id) {

				# Busca os dados para edição
				$data = $this->Menu->read(null, $id);

				# Junta o plugin controller e action
				# para vir selecionado na tela de edição
				$paths = array(
					$data['Menu']['plugin'],
					$data['Menu']['controller'],
					$data['Menu']['action']
				);
				$data['Menu']['path'] = implode(' - ', Set::filter($paths));	
			} else {
				# Valor default para o campo static
				$data['Menu']['static'] = true;
			}
		}

		# Busca todos o métodos possíveis para o select
		$methods = $this->getMethods();

		$this->set('menuTree', $this->Menu->generatetreelist(null, null, null, "_"));

		# Seta os método na view
		$this->set('methods', $methods);

	}

	public function delete($id) {
		$this->layout = 'cms';
		# Seta ID para ser deletado depois
		$this->Menu->id = $id;

		try {
			$this->Menu->delete();
			$this->setFlash('Menu apagado com sucesso.', 'alert alert-success');
		} catch(PDOException $e) {
			$this->setFlash('Falha ao apagar o Menu. Tente novamente.', 'alert alert-error');
		}

		$this->redirect('index');
	}

	private function getMethods() {
		$this->layout = 'cms';
		# Carrega Folder Utility do Cake,
		# para ler as pastas do sistema
		App::import('Folder', 'Utility');

		# Le os plugins
		$f = new Folder(APP . DS . 'plugins') ;

		# Ignora pasta do CVS
		$fp = $f->read(true, array('CVS'));
		$pluginsFolder = $fp[0];

		# Busca os controllers dos plugins
		$f = new Folder(APP . DS . 'controllers');
		$fp = $f->read(true, array('CVS'));
		
		# Seta o plugins None para os controller
		# que não estao dentro de plugins
		$plugins = array('None' => $fp[1]);

		foreach ($pluginsFolder as $key => $plugin) {
			$f = new Folder(APP . DS . 'plugins' . DS . $plugin . DS . 'controllers');
			$pControllers = $f->read(true, array('CVS'));
			if (!empty($pControllers[1])){
				$plugins[$plugin] = $pControllers[1];
			}
		}

		$return = array();
		foreach ($plugins as $plugin=>$controllers) {
			
			# Varre os controllers para buscar os método publicos
			foreach ($controllers as $controller) {

				# Utiliza o Inflector para obter o nome da classe
				# a partir do nome do arquivo
				$splittedFile = explode(".", $controller);
				$controllerName = Inflector::classify($splittedFile[0]);


				# Foi necessário require_once, pois App::import não funcionou
				# por algum motivo. Se puder trocar, melhor.

				# Se não for plugin, dá um require dentro da pasta default de controllers
				if ($plugin == 'None')
					require_once(APP . 'controllers' . DS . $controller);
				else 
					# Senao na pasta dos plugins
					require_once(APP . 'plugins' . DS . $plugin . DS . 'controllers' . DS . $controller);

				# Dá uim reflection nas classes pra buscar os métodos públicos.
				$class = new ReflectionClass($controllerName);
				$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
				
				# Varre os métodos de cada classe
				foreach ($methods as $method) {
					# Se for método específico da classe, coloca no array
					if ($method->class == $controllerName){
						# Reune plugin, controlle e action na mesma linha
						$str = ($plugin != 'None' ? $plugin . ' - ': '') . $controllerName . ' - ' . $method->name;
						$return[$str] = $str;
					}
				}
				
			}
		}
		
		return $return;
	}

	public function principal() {
		$this->layout = 'cms';
		$this->set('appendBootstrap', false);

		/**
		 * Verifica se exista algum checkpoint,
		 * Se nao existir, gera o conteudo padrao
		 */
		App::import("Utility", "Folder");
		$oFolderCheckPoints = new Folder(Configure::read('Cms.CheckPoint.menus'));
		$aFolderCheckPoints = $oFolderCheckPoints->read();

		if (empty($aFolderCheckPoints[1])) {
			$this->loadModel("Cms.CheckPoint");

			App::import('Xml');

      $oSnapXml = new Xml( Configure::read('Cms.CheckPoint.menus') . DS . "default" . DS . "conteudo.xml");
      $aSnap    = $oSnapXml->toArray();

      $aRestore = !empty($aSnap['Menus']) ? $aSnap['Menus']['Menu'] : array();
      if (!empty($aRestore) && !isset($aRestore[0])) {
        $aRestore = array($aRestore);
      }

			if ($this->Menu->restauraBackup($aRestore)) {
				$this->CheckPoint->generate();
			} else {
				$this->log("Erro no load do conteudo padrao", "chekcpoints");
			}
		}


		$menus = $this->Menu->find('threaded', array(
			'order' => array(
				'Menu.lft'
			)
		));
		# Seta os menus na tela, para serem listados corretamente.
		$this->set('menus', $menus);
	}

	public function getContent($id) {
		$isAjax = $this->RequestHandler->isAjax();

		# Verifica se é ajax, para setar o layout correto
		if ($isAjax) {
			$this->layout = 'ajax';
		}

		# Busca o conteúdo do menu no banco.
		$menu = $this->Menu->read(null, $id);

		# Verifica se é arquivo do cliente
		if (!$menu['Menu']['upload']) {

			$this->set('appendBootstrap', false);
			$this->set('content', $menu['Menu']['content']);

			# Renderiza :)
			$this->render('menu_content');

		} else {
			
			# Se for arquivo do cliente,
			# Renderiza ele :(
			$this->render(Configure::read('Cms.Sandbox.path') . $menu['Menu']['file']);

		}
	}

	public function moveUp($id, $delta) {
		$this->layout = 'cms';
		try {
			if ($this->Menu->moveUp($id, abs($delta))) {
				$this->setFlash('Menu reordenado.', 'alert alert-success');
			} else {
				$this->setFlash('Menu não foi reordenado.', 'alert alert-info');
			}
		} catch(PDOException $e) {
			$this->setFlash($e->getMessage(), 'alert alert-error');
		}

		$this->redirect('index');
	}

	public function moveDown($id, $delta) {
		$this->layout = 'cms';
		try {
			if ($this->Menu->moveDown($id, abs($delta))) {
				$this->setFlash('Menu reordenado.', 'alert alert-success');
			} else {
				$this->setFlash('Menu não foi reordenado.', 'alert alert-info');
			}
		} catch(PDOException $e) {
			$this->setFlash($e->getMessage(), 'alert alert-error');
		}

		$this->redirect('index');
	}

}

?>
