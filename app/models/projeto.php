<?php
class Projeto extends AppModel {

	var $name = 'Projeto';
	
	var $validate = array( 'exercicio'      => array('numeric'),
		                     'codprojeto'     => array('numeric'),
		                     'instituicao_id' => array('numeric'),
		                     'tipo'           => array('numeric'),
		                     'descricao'      => array('notempty'));



}
?>