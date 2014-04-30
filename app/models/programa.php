<?php
class Programa extends AppModel {

	var $name = 'Programa';
	
	var $validate = array( 'exercicio'   => array('numeric'),
		                     'codprograma' => array('numeric'),
		                     'descricao'   => array('notempty'));

}
?>