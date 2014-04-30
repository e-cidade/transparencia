<?php
class Pessoa extends AppModel {

  var $name = 'Pessoa';
 
  var $displayField = 'nome';
 
  var $validate = array( 'codpessoa' => array('numeric'  => array('rule' => array('numeric'))),
		                     'nome'      => array('notempty' => array('rule' => array('notempty'))),
		                     'cpfcnpj'   => array('notempty' => array('rule' => array('notempty'))));

}
?>