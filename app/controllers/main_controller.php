<?php
class MainController extends AppController {

 var $name       = 'Main';
 var $uses       = array('Glossario', 'PaginaPrincipal');
 var $components = array('RequestHandler');
 var $helpers    = array('Session','Javascript','Ajax','Crumb');

 function index() {
   
 }

 function consulta_dados() {

  $aItens = $this->PaginaPrincipal->getItens();
  $this->set('aItensPagina', $aItens);

 }

 function download($sName='', $sExtension='', $sPath='') {
   
   $sFilePath = str_replace("|",DS,$sPath);
   
   $this->view = 'Media';
   $params     = array( 'id'       => $sName.'.'.$sExtension,
                        'name'     => $sName,
                        'download' => true,
                        'extension'=> $sExtension,
                        'path'     => 'files'.DS.$sFilePath.DS);
   
   $this->set($params);

 }
 
 function outras_informacoes($sPath='') {

   $aParametros  = $_POST;
   $oFolderAtual = new stdClass();

   if (!empty($aParametros)) {
     
     $oFolderAtual->sFolderPath = $aParametros['sFolderPath']; 
     $oFolderAtual->sFolderName = $aParametros['sFolderName'];
     $iNivelCrumb               = count(explode(DS,$oFolderAtual->sFolderPath)); 
     
   } else {
     
     $oFolderAtual->sFolderPath = ''; 
     $oFolderAtual->sFolderName = 'Outras Informações';
     $iNivelCrumb               = 0;
   }
   
   $oFolder        = new Folder(WWW_ROOT."files".DS."outras_informacoes".$oFolderAtual->sFolderPath);
   $aFolderDetails = $oFolder->read(true,array('.svn', '.git', 'CVS', 'tests', 'templates','empty'));

   $aFolder = array();
   
   foreach ($aFolderDetails[0] as $sFolderPath) {
     
     $oFolderDetail = new stdClass();
     $oFolderDetail->sFolderName = end(explode(DS,$sFolderPath));
     $oFolderDetail->sFolderPath = $oFolderAtual->sFolderPath.DS.$sFolderPath;
     
     $aFolder[] = $oFolderDetail;
   }
   
   $aFile  = array();
   
   foreach ($aFolderDetails[1] as $sFilePath) {
     
     $oFile        = new File($sFilePath);
     $aFileDetails = $oFile->info(); 
     
     if ( substr($aFileDetails['extension'],-1) == '~') {
       continue;
     }
     
     $oFileDetail = new stdClass();
     $oFileDetail->sFileName     = $aFileDetails['filename']; 
     $oFileDetail->sFilePath     = str_replace(DS,"|","outras_informacoes".$oFolderAtual->sFolderPath);
     $oFileDetail->sExtension    = $aFileDetails['extension'];
     $oFileDetail->sFileBaseName = $aFileDetails['basename'];
     
     if ( $oFileDetail->sExtension == 'link') {
       $sUrl = file_get_contents($aFileDetails['dirname'].DS."files".DS."outras_informacoes".DS.$oFolderAtual->sFolderPath.DS.$aFileDetails['basename']);
     } else {
       $sUrl = '';
     }
     
     $oFileDetail->sUrl = $sUrl;
     
     $aFile[] = $oFileDetail;
   }
   
   $this->set('aFile'           ,$aFile);
   $this->set('aFolder'         ,$aFolder);
   $this->set('iNivelCrumb'     ,$iNivelCrumb);
   $this->set('oFolderAtual'    ,$oFolderAtual);
   
 } 
 
  function loadMenu($sView='') {
  
  	if ($sView == '') {
      $sView = 'pagina_principal';
  	}
  	
    if ($sView == 'pagina_principal') {
    	
    	$aItens = $this->PaginaPrincipal->getItens();
    	$this->set('aItensPagina', $aItens);
    }
    
    if ( $sView == 'glossario' ) {
    
      $aTipos = $this->Glossario->getTipos(); 
      $this->set('aListaTipos',$aTipos);
    }
    
    if ( $sView == 'itens_glossario' ) {
    
      $aItens = $this->Glossario->getItensGlossarioByTipo($_POST['id']);
      $this->set('aListaItens',$aItens);
    }  

    $this->render($sView);
  }
}