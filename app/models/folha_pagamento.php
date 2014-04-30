<?php

class FolhaPagamento extends AppModel {

  public $useTable = 'folha_pagamento';

  public $belongsTo  = array(
    'ServidorMovimentacao' => array(
      'className' => 'ServidorMovimentacao',
      'foreignKey' => 'servidor_movimentacao_id'
    )
  );

  /**
   * Retorna os dados da folha de pagamento do servidor para a movimentaчуo passada
   *
   * @param Integer iMovimentacaoId
   * @return array
   */
  public function getFolha($iMovimentacaoId) {

    $aFolhas = $this->find('all', array(
      'recursive'  => -1,
      'conditions' => array(
        'FolhaPagamento.servidor_movimentacao_id' => $iMovimentacaoId
      ),
      'order' => 'rubrica'
    ));

    if (empty($aFolhas)) {
      return $aFolhas;
    }

    $aRetorno = array();

    foreach (array_unique( Set::extract('/FolhaPagamento/tipofolha', $aFolhas) ) as $sTipoFolha ) {
      $aRetorno[$sTipoFolha] = Set::extract("/FolhaPagamento[tipofolha={$sTipoFolha}]", $aFolhas);
    }

    return $aRetorno;
  }

}

?>