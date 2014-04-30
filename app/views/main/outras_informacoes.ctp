<div id="breadcrumb">
<?php
  
  if ($iNivelCrumb == 0) {
    echo $this->Crumb->addPage(0,'Principal'         ,'main');
    echo $this->Crumb->addPage(1,'Outras Informações','main/outras_informacoes') ;
  } else {
    
    $aOptions = array('update' => 'main_content',
                      'url'    => array('action' => 'outras_informacoes'),
                      'data'   => $this->Javascript->object($oFolderAtual));
    
    echo $this->Crumb->addPage($iNivelCrumb,$oFolderAtual->sFolderName,null,$aOptions);
  }
  
  echo $this->Crumb->getHtml() ;	
	
?>
</div>

<br>
 <div class="lista_arquivo">
   <h3>
     <?php echo $oFolderAtual->sFolderName; ?>
   </h3>
   <ul>
     <?php
     
       foreach ($aFolder as $oFolder) {
                                     
         $aOptions = array('update'=> 'main_content',
                           'url'   => array('action' => 'outras_informacoes'),
                           'data'  => $this->Javascript->object($oFolder));
         
         echo "<li><span></span>";
         echo $this->Ajax->link($oFolder->sFolderName,array(),$aOptions);
         echo "</li>";
       }       
       
       foreach ($aFile as $oFile) {
           
         if ( $oFile->sExtension == 'link' ) {
          
           echo "<li><span></span>";
           echo "<a target='_blank' href='http://{$oFile->sUrl}'>{$oFile->sFileName}</a>";
           echo "</li>";             
         } else {
                    
           echo "<li><span></span>";
           echo $this->Html->link($oFile->sFileName,array("action"=>"download",$oFile->sFileName,$oFile->sExtension,$oFile->sFilePath));
           echo "</li>";
         }
       }
     ?>
   </ul>   
  </div>
<br>
<br>