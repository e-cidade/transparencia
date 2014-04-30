<?php

class ServidorMovimentacao extends AppModel {

  public $useTable = 'servidor_movimentacoes';

  public $belongsTo = array(
    'Servidor' => array(
      'className' => 'Servidor',
      'foreignKey' => 'servidor_id'
    )
  );

  /**
   * Retorna os anos possíveis cadastrados no banco 
   * @param integer - $iInstituicao - ID da instituicao
   * @return integer[] anos
   * @access public
   */
  public function getAnos($iInstituicao) {

    $aCompetenciaAberta = $this->__getCompetenciaAberta($iInstituicao);
    
    $aConditions = array();
    if ($aCompetenciaAberta['mes'] == 1) {
      $aConditions[] = "ano <> {$aCompetenciaAberta['ano']}";
    }

    $aAnos = $this->find('list', array(
      'fields' => array('ano', 'ano'),
      'joins' => array(
        array(
          'table' => 'servidores',
          'type'  => 'INNER',
          'alias' => 'Servidor',
          'conditions' => array(
            'ServidorMovimentacao.servidor_id = Servidor.id',
            "Servidor.instituicao_id = {$iInstituicao}"
          )
        )
      ),
      'group' => 'ano',
      'order' => 'ano',
      'conditions' => $aConditions
    ));

    return $aAnos;
  }

  /**
   * Retorna o meses de um ano, cadastrados no banco, exceto da competencia em aberto
   * @param integer - $iInstituicao - ID da instituicao
   * @param integer - $iAno - Ano
   * @return integer[] meses
   * @access public
   */
  public function getMeses($iAno, $iInstituicao)  {
    $aMesesValores = array(
      1  => 'Janeiro',
      2  => 'Fevereiro',
      3  => 'Março',
      4  => 'Abril',
      5  => 'Maio',
      6  => 'Junho',
      7  => 'Julho',
      8  => 'Agosto',
      9  => 'Setembro',
      10 => 'Outubro',
      11 => 'Novembro',
      12 => 'Dezembro'
    );

    $aCompetenciaAberta = $this->__getCompetenciaAberta($iInstituicao);
    
    $aConditions = array();
    if ($aCompetenciaAberta['mes'] == 1 and $aCompetenciaAberta['ano'] == $iAno) {
      $iAno--;
    } elseif ($iAno == $aCompetenciaAberta['ano']) {
      $aConditions[] = "mes <> {$aCompetenciaAberta['mes']}";
    }

    $aConditions[] = "ano = {$iAno}";

    $aMeses = $this->find('list', array(
      'fields' => array('mes', 'mes'),
      'group' => 'mes',
      'order' => 'mes',
      'joins' => array(
        array(
          'table' => 'servidores',
          'type'  => 'INNER',
          'alias' => 'Servidor',
          'conditions' => array(
            'ServidorMovimentacao.servidor_id = Servidor.id',
            "Servidor.instituicao_id = {$iInstituicao}"
          )
        )
      ),
      'conditions' => $aConditions
    ));
    
    return array_intersect_key($aMesesValores, $aMeses);
  }

  /**
   * Retorna o ultimo mes e ano da competência
   *
   * @param integer - $iInstituicao - ID da instituicao
   * @param array - ultima competência da instituicao
   */
  private function __getCompetenciaAberta($iInstituicao) {
    $aAno = $this->find('first', array(
      'recursive' => -1,
      'fields' => 'max(ano) as ano',
      'joins' => array(
        array(
          'table' => 'servidores',
          'type' => 'INNER',
          'alias' => 'Servidor',
          'conditions' => array(
            'ServidorMovimentacao.servidor_id = Servidor.id',
            "Servidor.instituicao_id = {$iInstituicao}"
          )
        )
      )
    ));

    $aMes = $this->find('first', array(
      'recursive' => -1,
      'fields' => 'max(mes) as mes',
      'joins' => array(
        array(
          'table' => 'servidores',
          'type' => 'INNER',
          'alias' => 'Servidor',
          'conditions' => array(
            'ServidorMovimentacao.servidor_id = Servidor.id',
            "Servidor.instituicao_id = {$iInstituicao}"
          )
        )
      ),
      'conditions' => array(
        'ano' => $aAno[0]['ano']
      )
    ));

    return array(
      'ano' => $aAno[0]['ano'],
      'mes' => $aMes[0]['mes']
    );
  }

  /**
   * Retorna cargos de um instituicao dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param integer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituicao
   * @return array - Cargos
   * @access public
   */
  public function getCargos($iAno, $iMes, $iInstituicao) {
    return $this->getData('cargo', $iAno, $iMes, $iInstituicao);
  }

  /**
   * Retorna lotações de um instituicao dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param integer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituicao
   * @return JSON<integer, string> - Lotações
   * @access public
   */
  public function getLotacoes($iAno, $iMes, $iInstituicao) {
    return $this->getData('lotacao', $iAno, $iMes, $iInstituicao);
  }

  /**
   * Retorna os vínculos de uma instituição dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param intefer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituição
   * @return array - Vinculos
   * @access public
   */
  public function getVinculos($iAno, $iMes, $iInstituicao) {
    return $this->getData('vinculo', $iAno, $iMes, $iInstituicao);
  }

  /**
   * Retorna os registros de acordo com o parametro $field de uma instituição dentro de uma determinada competência
   * @param string  - $field - Campo retornado.
   * @param integer - $iAno - Ano
   * @param intefer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituição
   * @return array - Vinculos
   * @access public
   */
  private function getData($field, $iAno, $iMes, $iInstituicao) {
    return $this->find('list', array(
      'fields' => array($field, $field),
      'group' => $field,
      'order' => $field,
      'joins' => array(
        array(
          'table' => 'servidores',
          'type' => 'left',
          'alias' => 'Servidor',
          'conditions' => array(
            'Servidor.instituicao_id = '. $iInstituicao,
          ),
          'fields' => array(
            'Servidor.instituicao_id', 'Servidor.id'
          )
        )
      ),
      'conditions' => array(
        'ano' => $iAno,
        'mes' => $iMes,
        'Servidor.id = ServidorMovimentacao.servidor_id'
      )
    ));
  }

  /**
   * Retorna os dados da movimentação do servidor e os dados do servidor
   *
   * @param Integer $iId - ID da movimentacao
   * @return array
   */
  public function getMovimentacao($iId) {
    
    return $this->find('first', array(
      'recursive'  => -1,
      'conditions' => array(
        'ServidorMovimentacao.id' => $iId
      ),
      'fields' => array(
        'ServidorMovimentacao.*', 'Servidor.*'
      ),
      'joins' => array(
        array(
          'table'      => 'transparencia.servidores',
          'alias'      => 'Servidor',
          'type'       => 'INNER',
          'conditions' => array(
            'Servidor.id = ServidorMovimentacao.servidor_id'
          )
        )
      )
    ));
  }

}

?>