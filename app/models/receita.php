<?php
class Receita extends AppModel {
	
  var $name = 'Receita';
 
  var $displayField = 'codreceita';
  
  var $validate = array( 'exercicio'      => array('numeric' => array('rule' => array('numeric'))),
		                     'codreceita'     => array('numeric' => array('rule' => array('numeric'))),
		                     'planoconta_id'  => array('numeric' => array('rule' => array('numeric'))),
		                     'recurso_id'     => array('numeric' => array('rule' => array('numeric'))),
		                     'instituicao_id' => array('numeric' => array('rule' => array('numeric'))));


  /**
   * Consulta todos exercícios existentes das movimentações de receita
   *
   * @return array
   */
  function getExercicios(){
	
	  $sSqlExercicio  = " select distinct receitas.exercicio ";
    $sSqlExercicio .= "     from receitas                  ";
    $sSqlExercicio .= "          inner join receitas_movimentacoes on receitas_movimentacoes.receita_id = receitas.id "; 
    $sSqlExercicio .= " order by receitas.exercicio desc   "; 
	
	  $aListaExercicio = $this->query($sSqlExercicio);
	
	  return $aListaExercicio;
  }

  /**
   * Retorna as Receitas e deduções
   *
   * @param String  $sEstrutural - O Número do estrutural
   * @param Integer $iInstit - Instituição
   * @param Integer $iExercicio - O Ano a ser consultado
   * @param String  $sWhere - Condições da busca
   * @param String  $sOrderBy - Não é utilizado
   * @param String  $sLimit - Não é utilizado
   * @param String  $sWhereExterno
   * @param Integer $iMes - Mês a ser consultado
   * @param Boolean $lRetornaDeducoes - Controla se deve retornar as deducoes em todas as consultas
   *                TRUE  - Retorna as deducoes em todas as consultas
   *                FALSE - Retorna as deducoes apenas quando o estrutural for zero
   * @return array
   */
  public function getReceitasByEstrutural($sEstrutural='',$iInstit='',$iExercicio='',$sWhere='',$sOrderBy='',$sLimit='',
                                   $sWhereExterno='', $iMes=12, $lRetornaDeducoes = false) {

    
    if ( trim($sWhereExterno) != '' ) {
      $sWhereExterno = " where {$sWhereExterno} ";
    }  
    
    if ( trim($sOrderBy) != '' ) {
      $sOrderBy = " order by {$sOrderBy} ";
    }
  
    if ( trim($sLimit) != '' ) {
      $sLimit   = " limit {$sLimit} ";
    }  	
  	
    $iNivel         = $this->getNivelEstrutural($sEstrutural);
    $aListaReceitas = array();
    $iDigEstrutural = 15;
    
    while ( $iNivel <= 15) {
      
      // Percorre todos os níveis do estrutural até retornar algum registro  
      if ($iNivel == 0) {
        
        $iNivelValor = ($iNivel+1);
        $sWhereReceitas .= " ( planocontas.estrutural    = '400000000000000'  ";
        $sWhereReceitas .= "   or planocontas.estrutural = '490000000000000'  ";
        $sWhereReceitas .= "   or planocontas.estrutural = '900000000000000' )";
        
        
        if ($iExercicio <= 2007) {
          
          $sWhereReceitas  = " (                                                                        ";
          $sWhereReceitas .= "   ( substr(planocontas.estrutural,1,2 ) = '40'                           ";
          $sWhereReceitas .= "       and plano.estrutural like substr(planocontas.estrutural,1,1 )||'%' ";
          $sWhereReceitas .= "       and plano.estrutural not like '49%'                                ";
          $sWhereReceitas .= "   )                                                                      ";
          $sWhereReceitas .= "   or                                                                     ";
          $sWhereReceitas .= "   ( substr(planocontas.estrutural,1,2 ) = '49'                           ";
          $sWhereReceitas .= "       and plano.estrutural like substr(planocontas.estrutural,1,2 )||'%' ";
          $sWhereReceitas .= "   )                                                                      ";
          $sWhereReceitas .= " )                                                                        ";
        }
        
        
        //$sWhereReceitas  = "   (planocontas.estrutural like '4%'  ";
        //$sWhereReceitas .= " or planocontas.estrutural like '9%' )";
        
      } else {
        
        if ( $iNivel >= 5  ) {
           
          $iDigEstrutural = 14;
          $iNivelValor    = ($iNivel+2);
        }
        
        $sConfiguraEstrutural = str_pad( substr($sEstrutural,0,$iNivel) . "%", $iDigEstrutural, '0', STR_PAD_RIGHT);

        $sWhereReceitas = "";

        // Verifica se deve retornar as deduções
        if ($lRetornaDeducoes) {
          $sConfiguraEstruturalDeducao = $sConfiguraEstrutural;
          $sConfiguraEstruturalDeducao{0} = '9';

          $sWhereReceitas .= " (";
        }

        $sWhereReceitas  .= " planocontas.estrutural like '{$sConfiguraEstrutural}' ";

        // Verifica se deve retornar as deduções
        if ($lRetornaDeducoes) {
          $sWhereReceitas .= "or planocontas.estrutural ilike '{$sConfiguraEstruturalDeducao}') ";
        }
        
        $sWhereReceitas .= "and planocontas.estrutural != '{$sEstrutural}' ";

        // Verifica se deve retornar as deduções
        if ($lRetornaDeducoes) {
          $sEstruturalDeducao    = $sEstrutural;
          $sEstruturalDeducao{0} = '9';

          $sWhereReceitas .= "and planocontas.estrutural != '{$sEstruturalDeducao}'";
        }
      }

      $sWhereReceitas .= " and planocontas.exercicio = {$iExercicio} ";
      
      $sSqlPlanoContas  = "  select planocontas.id, ";
      $sSqlPlanoContas .= "         planocontas.codcon,                                                   ";
      $sSqlPlanoContas .= "         planocontas.exercicio ,                                               ";
      $sSqlPlanoContas .= "         planocontas.estrutural,                                               ";
      $sSqlPlanoContas .= "         planocontas.descricao,                                                ";
      $sSqlPlanoContas .= "         case when (receitas.id is null) then false else true end as analitica ";
      $sSqlPlanoContas .= "    from planocontas                                                           ";
      $sSqlPlanoContas .= "         left join receitas on planocontas.id = receitas.planoconta_id         ";
      $sSqlPlanoContas .= "           and planocontas.exercicio          = receitas.exercicio             ";
      $sSqlPlanoContas .= "           and instituicao_id = {$iInstit}                                     ";
      $sSqlPlanoContas .= "   where {$sWhereReceitas}                                                     ";
      $sSqlPlanoContas .= " group by planocontas.id,                                                      ";
      $sSqlPlanoContas .= "          planocontas.codcon,                                                  ";
      $sSqlPlanoContas .= "          planocontas.exercicio,                                               ";
      $sSqlPlanoContas .= "          planocontas.estrutural,                                              "; 
      $sSqlPlanoContas .= "          planocontas.descricao,                                               ";
      $sSqlPlanoContas .= "          receitas.id                                                          ";

      $aBuscaReceitas   = $this->query($sSqlPlanoContas);

      $aRetornoReceita = array();
      
      foreach ($aBuscaReceitas as $iIndice => $aDadosReceita) {
        
        $oStdDadosReceita             = new stdClass();
        $oStdDadosReceita->id         = $aDadosReceita[0]['id'];
        $oStdDadosReceita->codcon     = $aDadosReceita[0]['codcon'];
        $oStdDadosReceita->exercicio  = $aDadosReceita[0]['exercicio'];
        $oStdDadosReceita->estrutural = $aDadosReceita[0]['estrutural'];
        $oStdDadosReceita->descricao  = $aDadosReceita[0]['descricao'];
        $oStdDadosReceita->analitica  = $aDadosReceita[0]['analitica'];
        
        $iNivelConta                  = $this->getNivelEstrutural($oStdDadosReceita->estrutural);
        $iParteEstrutural             = substr($oStdDadosReceita->estrutural, 0, $iNivelConta);
        
        $sSqlBuscaReceitas  = " SELECT Sum(previsaoinicial) AS previsao_inicial,                               ";
        $sSqlBuscaReceitas .= "  Sum(previsao_adicional)    AS previsao_adicional,                             ";
        $sSqlBuscaReceitas .= "  Sum(valor_periodo)         AS valor_periodo,                                  ";
        $sSqlBuscaReceitas .= "  Sum(arrecadado_acumulado)  AS valor_acumulado                                 ";
        $sSqlBuscaReceitas .= " FROM   (SELECT receitas.id,                                                    ";
        $sSqlBuscaReceitas .= "          receitas.previsaoinicial,                                             ";
        $sSqlBuscaReceitas .= "          Sum(CASE                                                              ";
        $sSqlBuscaReceitas .= "                WHEN Extract(month FROM receitas_movimentacoes.data) <= {$iMes} ";
        $sSqlBuscaReceitas .= "              THEN                                                              ";
        $sSqlBuscaReceitas .= "                previsaoadicional                                               ";
        $sSqlBuscaReceitas .= "                ELSE 0                                                          ";
        $sSqlBuscaReceitas .= "              END) AS previsao_adicional,                                       ";
        $sSqlBuscaReceitas .= "          Sum(CASE                                                              ";
        $sSqlBuscaReceitas .= "                WHEN Extract(month FROM receitas_movimentacoes.data) = {$iMes}  ";
        $sSqlBuscaReceitas .= "              THEN                                                              ";
        $sSqlBuscaReceitas .= "                valor                                                           ";
        $sSqlBuscaReceitas .= "                ELSE 0                                                          ";
        $sSqlBuscaReceitas .= "              END) AS valor_periodo,                                            ";
        $sSqlBuscaReceitas .= "          Sum(CASE                                                              ";
        $sSqlBuscaReceitas .= "                WHEN Extract(month FROM receitas_movimentacoes.data) <= {$iMes} ";
        $sSqlBuscaReceitas .= "              THEN                                                              ";
        $sSqlBuscaReceitas .= "                valor                                                           ";
        $sSqlBuscaReceitas .= "                ELSE 0                                                          ";
        $sSqlBuscaReceitas .= "              END) AS arrecadado_acumulado                                      ";
        $sSqlBuscaReceitas .= "   FROM   receitas                                                              ";
        $sSqlBuscaReceitas .= "          INNER JOIN planocontas                                                ";
        $sSqlBuscaReceitas .= "                  ON planocontas.id            = receitas.planoconta_id         ";
        $sSqlBuscaReceitas .= "                 and planocontas.exercicio = receitas.exercicio                 ";
        $sSqlBuscaReceitas .= "          left  join receitas_movimentacoes                                     ";
        $sSqlBuscaReceitas .= "                  on receitas.id = receitas_movimentacoes.receita_id            ";
        $sSqlBuscaReceitas .= "   WHERE  planocontas.estrutural ILIKE '{$iParteEstrutural}%'                   ";
        $sSqlBuscaReceitas .= "          AND receitas.instituicao_id = {$iInstit}                              ";
        $sSqlBuscaReceitas .= "          AND receitas.exercicio      = {$iExercicio}                           ";
        $sSqlBuscaReceitas .= "          {$sWhere}                                                             ";
        $sSqlBuscaReceitas .= "   GROUP  BY receitas.id,                                                       ";
        $sSqlBuscaReceitas .= "             receitas.previsaoinicial) AS valores                               ";
        
        
        $aValoresReceitas                     = $this->query($sSqlBuscaReceitas);
        $oStdDadosReceita->previsao_inicial   = $aValoresReceitas[0][0]['previsao_inicial'];
        $oStdDadosReceita->previsao_adicional = $aValoresReceitas[0][0]['previsao_adicional'];
        $oStdDadosReceita->valor_periodo      = $aValoresReceitas[0][0]['valor_periodo'];
        $oStdDadosReceita->valor_acumulado    = $aValoresReceitas[0][0]['valor_acumulado']; 
        $oStdDadosReceita->valor_diferenca    = $oStdDadosReceita->previsao_inicial + 
                                                $oStdDadosReceita->previsao_adicional - 
                                                $oStdDadosReceita->valor_acumulado;

        /**
         * ignoramos as receitas sem movimentação.
         */
        if ($oStdDadosReceita->previsao_inicial == 0 && $oStdDadosReceita->previsao_adicional == 0 && 
            $oStdDadosReceita->valor_periodo == 0 && $oStdDadosReceita->valor_acumulado == 0) {
          
          continue;
        }
        
        $aListaReceitas[] = $oStdDadosReceita;
      }
      
      if (count($aListaReceitas) > 0 || $sWhereExterno != '') {
        break;
      }
       
      if ( $iNivel >= 5  ) {
        $iNivel += 2;
      } else {
        $iNivel++;
      }
    }
    
    return $aListaReceitas;
  }  
  
  /**
   * Retorna o nível apartir do estrutural passado por parâmetro
   *
   * @param  string  $sEstrutural
   * @return integer
   */
	function getNivelEstrutural($sEstrutural='') {
	
	  $iLenghtEstrutural = strlen($sEstrutural);
	  $iUltimoDigito     = ($iLenghtEstrutural-1);
	  $sSubEstrutural    = '';
	
	  while ( $iUltimoDigito >= 0  ) {
	
	    if ( $sEstrutural{$iUltimoDigito} != '0' ) {
	      $sSubEstrutural = substr($sEstrutural,0,($iUltimoDigito+1));
	      break;
	    }
	
	    --$iUltimoDigito;
	  }
	
	  $iNivel = strlen($sSubEstrutural);
	
	  return $iNivel;
	
	}
	


}
?>
