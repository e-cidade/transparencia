<?php
class CrumbHelper extends Helper  {

  var $link_class  = 'crumb_link'; // css class for anchor tags.
  var $span_class  = 'crumb_span'; // css class for the span element .(last label).
  var $separator   = ' > ';        // separator between links.
  var $sProtocol   = 'http';
  var $helpers     = array('Session','Ajax');
  

  
  function addPage( $iLevel=null, $sTitle=null, $sUrl=null, $aParameters=array() ) {

    if ($iLevel == 0 ) {
      $this->clear();
    }
    
    if ( is_null($sUrl) ) {
      $sLink = $this->sProtocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    } else {
      $sLink = $this->sProtocol.'://'.$_SERVER['HTTP_HOST'].$this->base."/".$sUrl;
    } 
    
    $sController = $this->params['controller'];
    $sAction     = $this->params['action']; 
    
    if ( is_null($sTitle) ) {
      $sTitle = Inflector::humanize($sController);
    }
    
    if ( !isset($_SESSION['aCrumb'])) {
      $_SESSION['aCrumb'] = array();    
    }
     
    $aCrumb = $this->Session->read('aCrumb');
    
    if (array_key_exists($iLevel,$aCrumb)) {
      
      $aCrumb = $this->__stripAfter($aCrumb,$iLevel);
    } else {
      
      $oCrumb = new stdClass();
      $oCrumb->sUrl        = $sLink;
      $oCrumb->sTitle      = $sTitle;
      $oCrumb->aParameters = $aParameters;   
      
      $aCrumb[$iLevel]     = $oCrumb;
    }
    
    $_SESSION['aCrumb'] = $aCrumb; 
  }  
  
  

  function getHtml() {
    
    $aCrumb   = $this->Session->read('aCrumb');
    $iLastRow = (count($aCrumb)-1);
    $sHTML    = '' ;

    foreach ($aCrumb as $iInd => $oCrumb) {
    	
      if ($iInd == $iLastRow) {
        
        $sLink = sprintf("<span class='%s'>%s</span>", $this->span_class, $oCrumb->sTitle);

      } else {
        
        if ( count($oCrumb->aParameters) > 0 ) {
          $sLink = $this->Ajax->link($oCrumb->sTitle,array(),$oCrumb->aParameters)." ".$this->separator;
        } else {
          $sLink = sprintf('<a href="%s" class="%s">%s</a> %s ', $oCrumb->sUrl, $this->link_class, $oCrumb->sTitle, $this->separator);
        }
        
      }
       
      $sHTML .= $sLink;     
    }
    
    return $sHTML;
  }  
  

  function clear() {
    unset($_SESSION['aCrumb']);
  }  
  
  
  function __stripAfter($aReference, $sIndAfter) {

    $iCount = count($aReference)    ;
   
    for ($iInd = $sIndAfter + 1 ; $iInd < $iCount ; $iInd++ ) {
      unset($aReference[$iInd])    ;
    }

    return $aReference ;
  }
 
  
  
}
?>