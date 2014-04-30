<?php
class Dotacao extends AppModel {
 
  var $name = 'Dotacao';
 
  var $displayField = 'coddotacao';
 
  var $validate  = array( 'coddotacao'     => array('numeric' => array('rule' => array('numeric'))),
	 	                      'orgao_id'       => array('numeric' => array('rule' => array('numeric'))),
		                      'unidade_id'     => array('numeric' => array('rule' => array('numeric'))),
		                      'funcao_id'      => array('numeric' => array('rule' => array('numeric'))),
		                      'subfuncao_id'   => array('numeric' => array('rule' => array('numeric'))),
                    		  'programa_id'    => array('numeric' => array('rule' => array('numeric'))),
                    		  'projeto_id'     => array('numeric' => array('rule' => array('numeric'))),
                   		    'planoconta_id'  => array('numeric' => array('rule' => array('numeric'))),
                   		    'recurso_id'     => array('numeric' => array('rule' => array('numeric'))),
                  		    'instituicao_id' => array('numeric' => array('rule' => array('numeric'))));
 
  var $belongsTo = array( 'Orgao'      => array( 'className'  => 'Orgao'     ,'foreignKey' => 'orgao_id'),
                          'Unidade'    => array( 'className'  => 'Unidade'   ,'foreignKey' => 'unidade_id'),
                          'Funcao'     => array( 'className'  => 'Funcao'    ,'foreignKey' => 'funcao_id'),
                          'SubFuncao'  => array( 'className'  => 'Subfuncao' ,'foreignKey' => 'subfuncao_id'),
                          'Programa'   => array( 'className'  => 'Programa'  ,'foreignKey' => 'programa_id'),
                          'Projeto'    => array( 'className'  => 'Projeto'   ,'foreignKey' => 'projeto_id'),
                          'Planoconta' => array( 'className'  => 'Planoconta','foreignKey' => 'planoconta_id'),
                          'Recurso'    => array( 'className'  => 'Recurso'   ,'foreignKey' => 'recurso_id'));  
  
}
?>