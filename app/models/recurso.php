<?php
class Recurso extends AppModel {
	
  var $name = 'Recurso';
  
  var $displayField = 'descricao';
  
  var $validate = array( 'codrecurso' => array( 'numeric' => array('rule' => array('numeric'))),
		                     'descricao'  => array('notempty' => array('rule' => array('notempty'))));

  function getReceitas($sWhere='',$sOrderBy='',$sLimit='', $iAno='', $iMes='12') {

    if ( trim($sWhere) != '' ) {
      $sWhere   = " and {$sWhere} ";
    }
	
	  if ( trim($sOrderBy) != '' ) {
	    $sOrderBy = " order by {$sOrderBy} ";
	  }
	
	  if ( trim($sLimit) != '' ) {
	    $sLimit   = " limit {$sLimit} ";
	  }

	  $sSqlReceitas = "select recursos.id,
	  recursos.descricao,
	  sum(valores.valor_arrecadado) as valor_periodo,
	  sum(valores.valor_acumulado) as valor_acumulado,
	  sum(valores.previsao_adicional) as previsao_adicional,
	  sum(receitas.previsaoinicial) as previsao_inicial
	  from recursos
	  inner join receitas          on receitas.recurso_id               = recursos.id
	  inner join (select rec.recurso_id as codigo_recurso,
	      rec.id as codigo_receita,
	      sum(case when extract(month from data ) = {$iMes} then receitas_movimentacoes.valor else 0 end) as valor_arrecadado,
	      sum(case when extract(month from data ) <= {$iMes} then receitas_movimentacoes.valor else 0 end) as valor_acumulado,
	      sum(case when extract(month from data ) <= {$iMes} then receitas_movimentacoes.previsaoadicional else 0 end) as previsao_adicional
	      from receitas rec
	      inner join receitas_movimentacoes on receitas_movimentacoes.receita_id = rec.id
	      where rec.exercicio      = {$iAno}
	      group by rec.recurso_id,
	      rec.id
	  ) as valores on codigo_recurso = recursos.id and codigo_receita = receitas.id
	  where (valor_arrecadado != 0 or previsao_adicional != 0 or receitas.previsaoinicial != 0 or valores.valor_acumulado != 0)
	  and      receitas.exercicio      = {$iAno}
	  group by recursos.id,
	  recursos.descricao
	  {$sOrderBy}
      {$sLimit} ";
      
    $aBuscaReceitas = $this->query($sSqlReceitas);
    
    foreach ($aBuscaReceitas as $iIndice => $aDadosReceita) {
    
      $oStdDadosReceita                     = new stdClass();
      $oStdDadosReceita->id                 = $aDadosReceita[0]['id'];
      $oStdDadosReceita->descricao          = $aDadosReceita[0]['descricao'];
      $oStdDadosReceita->previsao_inicial   = $aDadosReceita[0]['previsao_inicial'];      
      $oStdDadosReceita->previsao_adicional = $aDadosReceita[0]['previsao_adicional'];    
      $oStdDadosReceita->valor_periodo      = $aDadosReceita[0]['valor_periodo'];         
      $oStdDadosReceita->valor_acumulado    = $aDadosReceita[0]['valor_acumulado'];       
      $oStdDadosReceita->valor_diferenca    = $oStdDadosReceita->previsao_inicial +       
                                              $oStdDadosReceita->previsao_adicional -   
                                              $oStdDadosReceita->valor_acumulado;       
      
       $aListaReceitas[] = $oStdDadosReceita;
    }
    
    return $aListaReceitas;
  }
}
?>