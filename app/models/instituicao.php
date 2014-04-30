<?php
class Instituicao extends AppModel {

  var $name = 'Instituicao';
	
  var $displayField = 'descricao';
	
  var $validate = array( 'codinstit' => array('numeric' => array('rule' => array('numeric'))),
   	                     'descricao' => array('notempty'=> array('rule' => array('notempty'))));
	

  function getReceitas($sWhere='',$sOrderBy='',$sLimit='', $iMes=12) {
	
	if ( trim($sWhere) != '' ) {
	  $sWhere   = " where {$sWhere} ";
	}
	
	if ( trim($sOrderBy) != '' ) {
	  $sOrderBy = " order by {$sOrderBy} ";
	}
	
	if ( trim($sLimit) != '' ) {
	  $sLimit   = " limit {$sLimit} ";
	}
	
	  
	  
    $sSqlReceitas  =" select                                                                                                                   ";
    $sSqlReceitas .="     id_instituicao as id,                                                                                                ";
    $sSqlReceitas .="     descricao,                                                                                                           ";
    $sSqlReceitas .="     sum(previsaoinicial)                           as previsao_inicial,                                                  ";
    $sSqlReceitas .="     sum(previsaoadicional)                         as previsao_adicional,                                                ";
    $sSqlReceitas .="     sum(previsaoinicial) + sum(previsaoadicional)  as previsao_atualizada,                                               ";
    $sSqlReceitas .="     sum(valor_arrecadado)                          as valor_arrecadado,                                                  ";
    $sSqlReceitas .="     sum(valor_acumulado)                           as arrecadado_acumulado,                                              ";
    $sSqlReceitas .="     (sum(previsaoinicial) + sum(previsaoadicional)) - sum(valor_acumulado)  as valor_diferenca                           ";
    $sSqlReceitas .=" from (                                                                                                                   ";
    $sSqlReceitas .="     select                                                                                                               ";
    $sSqlReceitas .="     instituicoes.id as id_instituicao,                                                                                   ";
    $sSqlReceitas .="     instituicoes.descricao,                                                                                              ";
    $sSqlReceitas .="     previsaoinicial,                                                                                                     ";
    $sSqlReceitas .="     receitas.id,                                                                                                         ";
    $sSqlReceitas .="     sum(                                                                                                                 ";
    $sSqlReceitas .="          case when (extract(month from receitas_movimentacoes.data) <= {$iMes})                                          ";
    $sSqlReceitas .="             then receitas_movimentacoes.previsaoadicional                                                                ";
    $sSqlReceitas .="          else 0                                                                                                          ";
    $sSqlReceitas .="     end) as previsaoadicional,                                                                                           ";
    $sSqlReceitas .="                                                                                                                          ";
    $sSqlReceitas .="     sum(                                                                                                                 ";
    $sSqlReceitas .="         case                                                                                                             ";
    $sSqlReceitas .="             when ( extract(month from receitas_movimentacoes.data) = {$iMes})                                                  ";
    $sSqlReceitas .="             then                                                                                                         ";
    $sSqlReceitas .="         case when substr(estrutural,1,1) = '9'                                                                           ";
    $sSqlReceitas .="             then (valor*1) else valor end                                                                                ";
    $sSqlReceitas .="             else 0                                                                                                       ";
    $sSqlReceitas .="     end ) as valor_arrecadado,                                                                                           ";
    $sSqlReceitas .="                                                                                                                          ";
    $sSqlReceitas .="     sum(                                                                                                                 ";
    $sSqlReceitas .="         case                                                                                                             ";
    $sSqlReceitas .="             when ( extract(month from receitas_movimentacoes.data) <= {$iMes})                                                 ";
    $sSqlReceitas .="             then                                                                                                         ";
    $sSqlReceitas .="         case when substr(estrutural,1,1) = '9' then (valor*1) else valor end                                             ";
    $sSqlReceitas .="             else 0                                                                                                       ";
    $sSqlReceitas .="     end) as valor_acumulado                                                                                              ";
    $sSqlReceitas .="                                                                                                                          ";
    $sSqlReceitas .="     from                                                                                                                 ";
    $sSqlReceitas .="         instituicoes                                                                                                     ";
    $sSqlReceitas .="         inner join receitas               on receitas.instituicao_id           = instituicoes.id                         ";
    $sSqlReceitas .="         left  join receitas_movimentacoes on receitas_movimentacoes.receita_id = receitas.id                             ";
    $sSqlReceitas .="         inner join planocontas            on planocontas.id                    = receitas.planoconta_id                  ";
    $sSqlReceitas .="                                                                                                                          ";
    $sSqlReceitas .="     {$sWhere}                                                                                                            ";
    $sSqlReceitas .="                                                                                                                          ";
    $sSqlReceitas .="         group by instituicoes.id,                                                                                        ";
    $sSqlReceitas .="         instituicoes.descricao,                                                                                          ";
    $sSqlReceitas .="         receitas.id,                                                                                                     ";
    $sSqlReceitas .="         previsaoinicial                                                                                                  ";
    $sSqlReceitas .="                                                                                                                          ";
    $sSqlReceitas .="                                                                                                                          ";
    $sSqlReceitas .=" ) as soma group by id_instituicao, descricao                                                                             ";
    $sSqlReceitas .=" {$sOrderBy}  {$sLimit}                                                                                                   ";
    return $this->query($sSqlReceitas);
	
  }

  /**
   * Retorna as intituicoes que possuem servidores na rhpessoalmov
   * @return array - Instituicoes
   * @access public
   */
  public function getInstituicoesServidores() {
    return $this->find( 'list', array(
        'fields' => array('id', 'descricao'),
        'order' => 'descricao',
        'conditions' => array(
          'exists (select *                                                              '
          . '        from servidores sr                                                  '
          . '             inner join servidor_movimentacoes mv on mv.servidor_id = sr.id '
          . '       where sr.instituicao_id = "Instituicao"."id")                        '
        )
      ));
  }

}
?>