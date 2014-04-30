<?php
class EmpenhoMovimentacao extends AppModel {

 var $name = 'EmpenhoMovimentacao';

 var $useTable = "empenhos_movimentacoes";

 var $validate = array('empenho_movimentacao_tipo_id' => array('numeric' => array('rule' => array('numeric'))),
		                   'empenho_id'                   => array('numeric' => array('rule' => array('numeric'))),
		                   'data'                         => array('date'    => array('rule' => array('date'))));


  function getMovimentacoes($sWhere='',$sOrderBy='',$sLimit='') {
 
    if ( trim($sWhere) != '' ) {
      $sWhere   = " where {$sWhere} ";
    }

    if ( trim($sOrderBy) != '' ) {
      $sOrderBy = " order by {$sOrderBy} ";
    }
 
    if ( trim($sLimit) != '' ) {
      $sLimit   = " limit {$sLimit} ";
    }

    $sSqlEmpenhos = " select empenhos_movimentacoes.*,
                             empenhos_movimentacoes_tipos.codtipo,
                             empenhos_movimentacoes_tipos.descricao as tipo
                        from empenhos_movimentacoes
                             inner join empenhos_movimentacoes_tipos on empenhos_movimentacoes_tipos.id = empenhos_movimentacoes.empenho_movimentacao_tipo_id
                             {$sWhere}
                             {$sOrderBy}
                             {$sLimit} "; 

    return $this->query($sSqlEmpenhos);

  }
}
?>