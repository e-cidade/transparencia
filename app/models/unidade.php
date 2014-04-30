<?php
class Unidade extends AppModel {

	var $name         = 'Unidade';
	
  var $displayField = 'descricao';
  	
	var $validate     = array( 'exercicio'      => array('numeric'),
		                         'orgao_id'       => array('numeric'),
		                         'codunidade'     => array('numeric'),
		                         'instituicao_id' => array('numeric'),
		                         'descricao'      => array('notempty'));


}
?>