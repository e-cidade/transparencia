<?php
class Orgao extends AppModel {
 
	var $name         = 'Orgao';
 
  var $useTable     = 'orgaos';
 
  var $displayField = 'descricao';
 
  var $validate = array( 'exercicio'      => array('numeric'  => array('rule' => array('numeric'))),
	  	                   'codorgao'       => array('numeric'  => array('rule' => array('numeric'))),
		                     'instituicao_id' => array('numeric'  => array('rule' => array('numeric'))),
		                     'descricao'      => array('notempty' => array('rule' => array('notempty'))));

  var $belongsTo = array( 'Instituicao'   => array( 'className'  => 'Instituicao',
														                        'foreignKey' => 'instituicao_id',
											                              'type'       =>'inner',
											                       			  'conditions' => '',
														                        'fields'     => '',
														                        'order'      => ''));
}
?>