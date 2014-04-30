<?php
class Importacao extends AppModel {
 var $name = 'Importacao';
 var $validate = array(
		'data' => array(
			'date' => array(
				'rule' => array('date'),
 //'message' => 'Sua mensagem de validação aqui',
 //'allowEmpty' => false,
 //'required' => false,
 //'last' => false, // Para a validação após esta regra
 //'on' => 'create', // Limitar a validação para as operações 'create' ou 'update'
 ),
 ),
		'hora' => array(
			'notempty' => array(
				'rule' => array('notempty'),
 //'message' => 'Sua mensagem de validação aqui',
 //'allowEmpty' => false,
 //'required' => false,
 //'last' => false, // Para a validação após esta regra
 //'on' => 'create', // Limitar a validação para as operações 'create' ou 'update'
 ),
 ),
 );


 function getUltimaImportacao() {

  $sSqlUltimaImportacao = " select data
		                            from importacoes
		                        order by id desc 
		                        limit 1 ";

  $aDataImportacao = $this->query($sSqlUltimaImportacao);

  return $aDataImportacao[0][0]['data'];

 }
}
?>