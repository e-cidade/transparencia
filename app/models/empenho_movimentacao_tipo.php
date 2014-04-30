<?php

class EmpenhoMovimentacaoTipo extends AppModel {

 var $name = 'EmpenhoMovimentacaoTipo';

 var $useTable = 'empenhos_movimentacoes_tipos';

 var $displayField = 'descricao';

 var $validate = array(
		'codtipo' => array(
			'numeric' => array(
				'rule' => array('numeric')
 ),
 ),
		'descricao' => array(
			'notempty' => array(
				'rule' => array('notempty')
 ),
 ),
 );


}
?>