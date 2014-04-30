<?php

class CheckPoint extends CmsAppModel {

  public $useTable = false;

  /**
   * Cria o snapshot na pasta que esta configurada
   *
   * @return boolean
   */
  public function generate() {

    $this->Menu = ClassRegistry::init("Cms.Menu");

    $aDados = $this->Menu->find('all');

    if (!$this->isUpToDate($aDados)) {
  
      App::import("Helper", "Xml");
      App::import("File");

      $oXml = new XmlHelper();

      $oXml->header();
      $oXml->serialize($aDados);

      $oFile = new File(Configure::read('Cms.CheckPoint.menus') . time() . ".xml", true, 0777);
  
      $oFile->append($oXml->header());
      $oFile->append("<menus>");
      $oFile->append($oXml->serialize($aDados));
      $oFile->append("</menus>");
  
      return true;  
    }

    return false;
  }

  /**
   * Verifica se os menus do banco estão atualizados com os do arquivo
   * @param $aDados- array de menus do banco
   * @return boolean
   */
  public function isUpToDate($aDados) {
  
    $aDados = Set::combine($aDados, "/Menu/id", "/Menu");
  
    App::import("Xml");
    App::import("Folder");
    App::import("File");
  
    $sCaminhosArquivos = Configure::read("Cms.CheckPoint.menus");
  
    $oFolder = new Folder($sCaminhosArquivos);
  
    $aConteudo = $oFolder->read();
    $aArquivos = Set::sort($aConteudo[1], "{n}", "desc");
  
    if (empty($aArquivos)) {
      return false;
    }
  
    $oFile    = new File($sCaminhosArquivos . $aArquivos[0]);
    $oXml     = new Xml($oFile->read());
    $aAntigo  = $oXml->toArray();

    foreach ($aDados as &$aMenu) {
      $aMenu['Menu']['content'] = str_replace("\r\n", " ", $aMenu['Menu']['content']);
    }
  
    if (isset($aAntigo["menus"])) {
      $aAntigo["Menus"] = $aAntigo["menus"];
      unset($aAntigo["menus"]);
    }
  
    if(isset($aAntigo["Menus"])) {
      $aAntigo  = Set::combine($aAntigo["Menus"], "/Menu/id", "/Menu");
      $aRetorno = Set::diff($aDados, $aAntigo);
    }

    return empty($aRetorno);
  }
}

?>