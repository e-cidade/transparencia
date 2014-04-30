<?php
class ReceitaMovimentacao extends AppModel {

 var $name         = 'ReceitaMovimentacao';
 var $useTable     = 'receitas_movimentacoes';
 var $displayField = 'receita_id';

 var $validate = array(
		'receita_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
 //'message' => 'Sua mensagem de validação aqui',
 //'allowEmpty' => false,
 //'required' => false,
 //'last' => false, // Para a validação após esta regra
 //'on' => 'create', // Limitar a validação para as operações 'create' ou 'update'
 ),
 ),
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
 );

 // As associações abaixo foram criadas com todas as chaves possíveis, então é possível remover as que não são necessárias

 //	var $belongsTo = array(
 //		'Receita' => array(
 //			'className' => 'Receita',
 //			'foreignKey' => 'receita_id',
 //			'conditions' => '',
 //			'fields' => '',
 //			'order' => ''
 //		)
 //	);


}
?>