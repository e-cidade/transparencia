<?php
class Planoconta extends AppModel {
	
  var $name = 'Planoconta';
 
  var $displayField = 'descricao';
  
  var $validate = array( 'codcon'     => array('numeric'  => array('rule' => array('numeric'))),
		                     'exercicio'  => array('numeric'  => array('rule' => array('numeric'))),
		                     'estrutural' => array('notempty' => array('rule' => array('notempty'))),
		                     'descricao'  => array('notempty' => array('rule' => array('notempty'))));
  
  
  function getElementoDiaria() {
    
    $sSqlElementoDiaria  = " select distinct planocontas.codcon                ";
    $sSqlElementoDiaria .= "   from planocontas                                ";
    $sSqlElementoDiaria .= "  where planocontas.estrutural = '333901400000000'  order by planocontas.codcon limit 1";

    $aElemento = $this->query($sSqlElementoDiaria);
    
    return $aElemento[0][0]['codcon']; 
    
  }

}
?>
