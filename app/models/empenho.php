<?php
class Empenho extends AppModel {

  var $name         = 'Empenho';
 
  var $displayField = 'codempenho';

  var $validate     = array( 'codempenho'     => array('numeric' => array('rule' => array('numeric'))),
 													 	 'exercicio'      => array('numeric' => array('rule' => array('numeric'))),
 												     'instituicao_id' => array('numeric' => array('rule' => array('numeric'))),
 														 'dataemissao'    => array('date'    => array('rule' => array('date'))),
														 'dotacao_id'     => array('numeric' => array('rule' => array('numeric'))),
														 'pessoa_id'      => array('numeric' => array('rule' => array('numeric')))
		                       );


  var $belongsTo = array( 'Instituicao' => array( 'className'  => 'Instituicao',
																					        'foreignKey' => 'instituicao_id',
																					        'conditions' => '',
																						      'type'       =>'inner',
																					        'fields'     => '',
																					        'order'      => ''));


  /**
   * Retorna os dados dos empenhos
   *
   * @param string $sWhere
   * @param string $sOrderBy
   * @param string $sLimit
   * @return array
   */  
  function getDadosEmpenho($sWhere='',$sOrderBy='',$sLimit='') {

    if ( trim($sWhere) != '' ) {
      $sWhere   = " where {$sWhere} ";
    }

    if ( trim($sOrderBy) != '' ) {
      $sOrderBy = " order by {$sOrderBy} ";
    }

    if ( trim($sLimit) != '' ) {
      $sLimit   = " limit {$sLimit} ";
    }

    $sSqlEmpenhos  = " select empenhos.*,                                                 ";
    $sSqlEmpenhos .= "        recursos.descricao as descrrecurso                          ";          
    $sSqlEmpenhos .= "   from empenhos                                                    ";
    $sSqlEmpenhos .= "        inner join dotacoes    on empenhos.dotacao_id = dotacoes.id ";
    $sSqlEmpenhos .= "        inner join recursos    on recursos.id         = recurso_id  ";
    $sSqlEmpenhos .= "        {$sWhere}                                                   ";
    $sSqlEmpenhos .= "        {$sOrderBy}                                                 ";
    $sSqlEmpenhos .= "        {$sLimit}                                                   ";

    return $this->query($sSqlEmpenhos);

  }
  
  
  /**
   * Retorna os empenhos agrupados apartir do nível informado
   *  
   *   Níveis :
   * 
   *   1 - Instituição  
   *   2 - Orgão
   *   3 - Elemento
   *   4 - Credor
   *   5 - Empenho
   * 
   * @param integer $iNivel
   * @param integer $iExercicio
   * @param string  $sWhere
   * @param string  $sOrderBy
   * @param string  $sLimit
   * @param string  $sWhereExterno
   * @return array
   */
  function getEmpenhosByNivel($iNivel=1, $iExercicio='', $sWhere='',$sOrderBy='',$sLimit='',$sWhereExterno=''){
  	
    if ( trim($sWhere) != '' ) {
      $sWhere   = " and {$sWhere} ";
    }
  	
    if ( trim($sWhereExterno) != '' ) {
      $sWhereExterno = " where {$sWhereExterno} ";
    }    
    
    if ( trim($sOrderBy) != '' ) {
      $sOrderBy = " order by {$sOrderBy} ";
    }

    if ( trim($sLimit) != '' ) {
      $sLimit   = " limit {$sLimit} ";
    }

    switch ($iNivel) {
    	
    	// Instituição
    	case 1:
    		$sCamposConsulta  = " instituicoes.id,       ";
        $sCamposConsulta .= " instituicoes.descricao ";
        
        $sInnerConsulta   = " inner join instituicoes on instituicoes.id = empenhos.instituicao_id ";
        $sInnerConsulta  .= " inner join dotacoes     on dotacoes.id     = empenhos.dotacao_id     ";
        $sInnerConsulta  .= " inner join planocontas  on planocontas.id  = dotacoes.planoconta_id  ";
        $sInnerConsulta  .= " inner join pessoas      on pessoas.id      = empenhos.pessoa_id      ";
        
        $sCamposAgrupa    = $sCamposConsulta;
        
    	  break;
    	  
  	  // Orgão 
      case 2:
        $sCamposConsulta  = " orgaos.codorgao,  ";
        $sCamposConsulta .= " ( select org.descricao 
                                  from orgaos org
                                 where org.codorgao = orgaos.codorgao
                                  order by org.exercicio desc limit 1 ) as descricao ";
        
        $sInnerConsulta   = " inner join dotacoes    on dotacoes.id    = empenhos.dotacao_id    ";
        $sInnerConsulta  .= " inner join orgaos      on orgaos.id      = dotacoes.orgao_id      ";
        $sInnerConsulta  .= " inner join planocontas on planocontas.id = dotacoes.planoconta_id ";
        $sCamposAgrupa    = " orgaos.codorgao ";      	
        break;
        
       // Elemento     	  
      case 3:
      	$sCamposConsulta  = " planocontas.codcon,                                                         ";
        $sCamposConsulta .= " planocontas.descricao,                                                      ";
        $sCamposConsulta .= " ( select grupo.descricao                                                    ";
        $sCamposConsulta .= "     from planocontas as grupo                                               ";
        $sCamposConsulta .= "    where grupo.estrutural = rpad(substr(planocontas.estrutural,1,3),15,'0') ";
        $sCamposConsulta .= "    order by grupo.exercicio limit 1 ) as grupo                              ";
      	
        $sInnerConsulta   = " inner join dotacoes    on dotacoes.id    = empenhos.dotacao_id              ";
        $sInnerConsulta  .= " inner join orgaos      on orgaos.id      = dotacoes.orgao_id                ";
        $sInnerConsulta  .= " inner join planocontas on planocontas.id = dotacoes.planoconta_id           ";
        
        $sCamposAgrupa    = " planocontas.codcon,                                                         ";
        $sCamposAgrupa   .= " planocontas.descricao,                                                      ";
        $sCamposAgrupa   .= " planocontas.estrutural                                                      "; 
        break;
        
      // Credor  
      case 4:
      	$sCamposConsulta  = " pessoas.codpessoa,                                                ";
        $sCamposConsulta .= " case                                                              ";
        $sCamposConsulta .= "   when    pessoas.cpfcnpj::numeric = 0::numeric                   ";  
        $sCamposConsulta .= "         or trim(pessoas.cpfcnpj) = ''                             ";
        $sCamposConsulta .= "         or length(trim(pessoas.cpfcnpj)) = 11 then '00000000000'  "; 
        $sCamposConsulta .= "   else pessoas.cpfcnpj                                            ";
        $sCamposConsulta .= " end as cpfcnpj,                                                   ";
        $sCamposConsulta .= " pessoas.nome                                                      ";
        
        $sInnerConsulta   = " inner join pessoas     on pessoas.id     = empenhos.pessoa_id     ";
        $sInnerConsulta  .= " inner join dotacoes    on dotacoes.id    = empenhos.dotacao_id    ";
        $sInnerConsulta  .= " inner join orgaos      on orgaos.id      = dotacoes.orgao_id      ";
        $sInnerConsulta  .= " inner join planocontas on planocontas.id = dotacoes.planoconta_id ";
        
        $sCamposAgrupa    = " pessoas.codpessoa,                                                ";             	
        $sCamposAgrupa   .= " pessoas.cpfcnpj,                                                  ";
        $sCamposAgrupa   .= " pessoas.nome                                                      ";
        break; 

      // Empenho
      case 5:
     	  $sCamposConsulta  = " empenhos.id, ";
        $sCamposConsulta .= " empenhos.codigo||' / '||empenhos.exercicio as codempenho,      ";
        $sCamposConsulta .= " empenhos.dataemissao,                                          ";
        $sCamposConsulta .= " funcoes.descricao              as funcao_descricao,            ";
        $sCamposConsulta .= " subfuncoes.descricao           as subfuncao_descricao,         ";
        $sCamposConsulta .= " programas.descricao            as programa_descricao,          ";
        $sCamposConsulta .= " projetos.descricao             as projeto_descricao,           ";
        $sCamposConsulta .= " planocontas.descricao          as planoconta_descricao,        ";
        $sCamposConsulta .= " recursos.descricao             as recurso_descricao            ";
        
        $sInnerConsulta   = " inner join dotacoes    on empenhos.dotacao_id  = dotacoes.id            ";
        $sInnerConsulta  .= " inner join funcoes     on funcoes.id           = dotacoes.funcao_id     ";
        $sInnerConsulta  .= " inner join subfuncoes  on subfuncoes.id        = dotacoes.subfuncao_id  ";
        $sInnerConsulta  .= " inner join programas   on programas.id         = dotacoes.programa_id   ";
        $sInnerConsulta  .= " inner join projetos    on projetos.id          = dotacoes.projeto_id    ";
        $sInnerConsulta  .= " inner join planocontas on planocontas.id       = dotacoes.planoconta_id ";
        $sInnerConsulta  .= " inner join recursos    on recursos.id          = dotacoes.recurso_id    ";
        $sInnerConsulta  .= " inner join pessoas     on pessoas.id           = empenhos.pessoa_id     ";
        $sInnerConsulta  .= " inner join orgaos      on orgaos.id            = dotacoes.orgao_id      ";
        
        $sCamposAgrupa    = " empenhos.id,          ";
        $sCamposAgrupa   .= " empenhos.codigo,      ";
        $sCamposAgrupa   .= " empenhos.exercicio,   ";
        $sCamposAgrupa   .= " empenhos.dataemissao, ";
        $sCamposAgrupa   .= " funcoes.descricao,     ";
        $sCamposAgrupa   .= " subfuncoes.descricao,  ";
        $sCamposAgrupa   .= " programas.descricao,  ";
        $sCamposAgrupa   .= " projetos.descricao,   ";
        $sCamposAgrupa   .= " planocontas.descricao, ";
        $sCamposAgrupa   .= " recursos.descricao    ";
        break;        
    }
    
    $sSqlEmpenhos  = " select empenhos.id,                                                                                                                       ";
    $sSqlEmpenhos .= "        sum(case when empenhos_movimentacoes_tipos.codgrupo = 10 then empenhos_movimentacoes.valor else 0 end ) as valor_empenhado,        ";
    $sSqlEmpenhos .= "        sum(case when empenhos_movimentacoes_tipos.codgrupo = 11 then empenhos_movimentacoes.valor else 0 end ) as valor_anulado,          ";
    $sSqlEmpenhos .= "        sum(case when empenhos_movimentacoes_tipos.codgrupo = 20 then empenhos_movimentacoes.valor else 0 end ) as valor_liquidado,        ";
    $sSqlEmpenhos .= "        sum(case when empenhos_movimentacoes_tipos.codgrupo = 21 then empenhos_movimentacoes.valor else 0 end ) as valor_liquidado_estorno,";
    $sSqlEmpenhos .= "        sum(case when empenhos_movimentacoes_tipos.codgrupo = 30 then empenhos_movimentacoes.valor else 0 end ) as valor_pago,             ";
    $sSqlEmpenhos .= "        sum(case when empenhos_movimentacoes_tipos.codgrupo = 31 then empenhos_movimentacoes.valor else 0 end ) as valor_pago_estorno      ";                             
    $sSqlEmpenhos .= "   from empenhos                                                                                                                           ";
    $sSqlEmpenhos .= "        inner join empenhos_movimentacoes       on empenhos_movimentacoes.empenho_id = empenhos.id                                         ";
    $sSqlEmpenhos .= "        inner join empenhos_movimentacoes_tipos on empenhos_movimentacoes_tipos.id   = empenhos_movimentacoes.empenho_movimentacao_tipo_id ";
    $sSqlEmpenhos .= "        {$sInnerConsulta}                                                                                                                  ";
    $sSqlEmpenhos .= "  where empenhos.exercicio = {$iExercicio}                                                                                                 ";
    $sSqlEmpenhos .= "    and extract( year from empenhos_movimentacoes.data ) = {$iExercicio}                                                                   ";
    $sSqlEmpenhos .= "        {$sWhere}                                                                                                                          ";
    $sSqlEmpenhos .= "  group by empenhos.id                                                                                                                     ";
                    
    $sSqlAgrupa  = " select distinct {$sCamposConsulta},                                           "; 
    $sSqlAgrupa .= "        sum(x.valor_empenhado)                             as valor_empenhado, ";
    $sSqlAgrupa .= "        sum(x.valor_anulado)                               as valor_anulado,   ";
    $sSqlAgrupa .= "        sum(x.valor_liquidado - x.valor_liquidado_estorno) as valor_liquidado, ";
    $sSqlAgrupa .= "        sum(x.valor_pago      - x.valor_pago_estorno)      as valor_pago       ";
    $sSqlAgrupa .= "   from ( {$sSqlEmpenhos} ) as x                                               ";
    $sSqlAgrupa .= "        inner join empenhos  on empenhos.id = x.id                             ";
    $sSqlAgrupa .= "        {$sInnerConsulta}                                                      ";
    $sSqlAgrupa .= "  group by {$sCamposAgrupa}                                                    ";

    $sSqlGeral   = " select *                  "; 
    $sSqlGeral  .= "   from ($sSqlAgrupa) as y ";
    $sSqlGeral  .= "   {$sWhereExterno}        ";
    $sSqlGeral  .= "   {$sOrderBy}             ";
    $sSqlGeral  .= "   {$sLimit}               ";
    
    return $this->query($sSqlGeral);  	
  	
  }
  
 
  /**
   * Retorna os intens do empenho
   *
   * @param string $sWhere
   * @param string $sOrderBy
   * @param string $sLimit
   * @return array
   */     
  function getItens($sWhere='', $sOrderBy='', $sLimit='') {
 
    if ( trim($sWhere) != '' ) {
      $sWhere   = " where {$sWhere} ";
    }  	
  	
    if ( trim($sOrderBy) != '' ) {
      $sOrderBy = " order by {$sOrderBy} ";
    }
 
    if ( trim($sLimit) != '' ) {
      $sLimit   = " limit {$sLimit} ";
    }

    $sSqlItensEmpenho  = " select *               ";
    $sSqlItensEmpenho .= "   from empenhos_itens  ";
    $sSqlItensEmpenho .= "   {$sWhere}            ";
    $sSqlItensEmpenho .= "   {$sOrderBy}          ";
    $sSqlItensEmpenho .= "   {$sLimit}            "; 
                              
    return $this->query($sSqlItensEmpenho);

  }       

  
  /**
   * Retorna os processos dos empenhos
   *
   * @param string $sWhere
   * @param string $sOrderBy
   * @param string $sLimit
   * @return array
   */
  function getProcessos($sWhere='', $sOrderBy='', $sLimit='') {
 
    if ( trim($sWhere) != '' ) {
      $sWhere   = " where {$sWhere} ";
    }   
    
    if ( trim($sOrderBy) != '' ) {
      $sOrderBy = " order by {$sOrderBy} ";
    }
 
    if ( trim($sLimit) != '' ) {
      $sLimit   = " limit {$sLimit} ";
    }

    $sSqlProcessoEmpenho  = " select *                  ";
    $sSqlProcessoEmpenho .= "   from empenhos_processos ";
    $sSqlProcessoEmpenho .= "  {$sWhere}                ";
    $sSqlProcessoEmpenho .= "  {$sOrderBy}              ";
    $sSqlProcessoEmpenho .= "  {$sLimit}                "; 

    return $this->query($sSqlProcessoEmpenho);

  }  
  
}
?>
