<?php
class EmpenhoItem extends AppModel { 
	
	var $name = 'EmpenhoItem';
	
	var $useTable = "empenhos_itens";
	
  var $displayField = 'descricao';
  
	var $validate = array( 'empenho_id' => array('numeric'  => array('rule' => array('numeric'))),
												 'descricao'  => array('notempty' => array('rule' => array('notempty'))),
												 'quantidade' => array('numeric'  => array('rule' => array('numeric')))
	                      );
}
?>