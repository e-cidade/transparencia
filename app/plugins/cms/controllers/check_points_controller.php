<?php

class CheckPointsController extends CmsAppController {
  
  public $name = "CheckPoints";

  /**
   * Função para restauração
   */
  public function restore() {

    $sDiretorio = Configure::read('Cms.CheckPoint.menus');

    if ( !is_dir($sDiretorio) ) {

      $this->Session->setFlash("Diretório das restaurações não configurado.", 'default', array('class' => "alert alert-error"));
      $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
    }

    /**
     * Faz a restauração
     */
    if ( !empty( $this->data ) ) {

      $this->loadModel('Cms.Menu');
      App::import('Xml');

      $oSnapXml = new Xml( $sDiretorio . $this->data['CheckPoint']['snapshot'] );    
      $aSnap    = $oSnapXml->toArray();

      $aRestore = !empty($aSnap['Menus']) ? $aSnap['Menus']['Menu'] : array();

      /**
       * Verifica se possui apenas um item de menu no xml e trata de uma forma diferente.
       * -- Função de XML do Cake salva de formas diferentes o XML quando possui apenas um item.
       */
      if (!empty($aRestore) && !isset($aRestore[0])) {
        $aRestore = array($aRestore);
      }
      
      $this->CheckPoint->generate();

      if ($this->Menu->restauraBackup( $aRestore )) {
        $this->Session->setFlash("Restaurado com sucesso.", 'default', array('class' => "alert alert-success"));
      } else {
        $this->Session->setFlash("Erro ao restaurar.", 'default', array('class' => "alert alert-error"));
      }
    }

    /**
     * Pega os snapshots salvos e invverte a ordem para exibir do mais novo para o mais antigo
     */
    $oFolder = new Folder(Configure::read('Cms.CheckPoint.menus'));
    $aFolder = $oFolder->read();
    $aFiles  = array_reverse($aFolder[1]);
    $aSnapshot = array();

    foreach($aFiles as $sFile) {

      if ( !in_array($sFile, array('.', '..') ) ) {
        $aSnapshot[$sFile] = date('d/m/Y H:i:s', str_replace( '.xml', '', $sFile ) );
      }
    }

    if ( empty( $aSnapshot) ) {

      $this->Session->setFlash("Nenhum ponto de restauração encontrado.", 'default', array('class' => "alert alert-error"));
      $this->redirect(array('controller' => 'dashboard', 'action' => 'index'));
    }

    $this->set( compact('aSnapshot') );    
  }
  
  public function save() {
  
    $this->Session->setFlash("Não salvo.", 'default', array('class' => "alert alert-error"));

    if ($this->CheckPoint->generate()) {
      $this->Session->setFlash("Salvo com sucesso.", 'default', array('class' => "alert alert-success"));
    }
  
    $this->redirect(array("plugin" => "cms", "controller" => "dashboard", "action" => "index"));
    return;
  
  }

}


?>