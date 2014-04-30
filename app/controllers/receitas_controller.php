<?php
class ReceitasController extends AppController {

  var $name       = 'Receitas';
  var $uses       = array('Instituicao','Receita','Recurso','ReceitaMovimentacao','Importacao');
  var $components = array('RequestHandler');
  var $helpers    = array('CakePtbr.Formatacao','Crumb','JavaScript','Transparencia');

  function index() {

    $aListaExercicios = $this->Receita->getExercicios();
    $dtDataImportacao = $this->Importacao->getUltimaImportacao();

    $this->set('aListaExercicios',$aListaExercicios);
    $this->set('dtDataImportacao',$dtDataImportacao);
  }


  function loadLink($sView=''){

    $this->layout = 'ajax';
  
    if ( $sView=='') {
     $this->redirect($this->base);
    }
  
    $this->set('aParametros',$_POST);
    $this->render($sView);
  
  }



  function getInstit() {

    $this->layout     = 'ajax';
    $this->autoRender = false;
  
    $iPages      = $_POST['page'];
    $iLimit      = $_POST['rows'];
    $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
    $oParametros = json_decode(stripslashes($_POST['aParametros']));
  
    $sWhere  = " receitas.exercicio = {$oParametros->iExercicio}  ";
    
    //@todo
    if (isset($_POST['dtDataConsulta'])) {
      $sWhere .= " AND receitas_movimentacoes.data <=  ";
    }
  
    $aListaInstituicoes = $this->Instituicao->getReceitas($sWhere, null,null, $oParametros->iMes);
  
    $iCount = count($aListaInstituicoes);
  
    if( $iCount > 0 ) {
      $iTotalPages = ceil($iCount/$iLimit);
    } else {
      $iTotalPages = 0;
    }
  
    $oRetorno->total   = $iTotalPages;
    $oRetorno->records = $iCount;
    $oRetorno->page    = $iPages;
  
    $sOffSet = $iLimit * $iPages - $iLimit;
    $aListaInstituicoes = $this->Instituicao->getReceitas($sWhere,$sOrderBy,$iLimit." offset $sOffSet ", $oParametros->iMes);

    foreach ($aListaInstituicoes as $iInd => $aInstituicao ) {
  
      $oRetorno->rows[$iInd]['id']   = $aInstituicao[0]['id'];
      $oRetorno->rows[$iInd]['cell'] = array(utf8_encode($aInstituicao[0]['descricao']),
                                                         $aInstituicao[0]['previsao_inicial'], 
                                                         $aInstituicao[0]['previsao_adicional'],
                                                         $aInstituicao[0]['valor_arrecadado'],
                                                         $aInstituicao[0]['arrecadado_acumulado'],
                                                         $aInstituicao[0]['valor_diferenca']
                                             );
    }
  
    echo json_encode($oRetorno);

  }


  public function getReceitas() {

    $this->layout     = 'ajax';
    $this->autoRender = false;
  
    $iPages          = $_POST['page'];
    $iLimit          = $_POST['rows'];
    $sOrderBy        = $_POST['sidx'] . " " . $_POST['sord'];
    $oParametros     = json_decode(stripslashes($_POST['aParametros']));
    $lLiquidoDeducao = (isset($oParametros->iTipoExibicao) && $oParametros->iTipoExibicao == 1);
    
    $sWhere = '';
  
    if ( isset($oParametros->iRecurso) && trim($oParametros->iRecurso) != ''  ) {
      $sWhere .= " and receitas.recurso_id = {$oParametros->iRecurso} ";
    }
    
    $aWhereExterno = array();
    
    if (isset($_POST['filters'])) {
    	
  	  $oFiltros = json_decode(stripslashes($_POST['filters']));
  	  $aFiltros = $oFiltros->rules;
  	  
  	  foreach ( $aFiltros as $oFiltro ) {
  	    $aWhereExterno[] = " {$oFiltro->field} ilike '%{$oFiltro->data}%' ";
  	  }    
    }
    
    $sWhereExterno  = implode(" and ",$aWhereExterno);
    
    $aListaReceitas = $this->Receita->getReceitasByEstrutural( $oParametros->sEstrutural,
															                                 $oParametros->iInstituicao,
															                                 $oParametros->iExercicio,
															                                 $sWhere,
															                                 null,
															                                 null,
															                                 $sWhereExterno,
                                                               $oParametros->iMes );
  
    $sOffSet = $iLimit * $iPages - $iLimit;
  
    $aListaReceitas = $this->Receita->getReceitasByEstrutural( $oParametros->sEstrutural,
                                                               $oParametros->iInstituicao,
                                                               $oParametros->iExercicio,
                                                               $sWhere,
                                                               $sOrderBy,
                                                               $iLimit . " offset $sOffSet ",
                                                               $sWhereExterno, 
                                                               $oParametros->iMes,
                                                               $lLiquidoDeducao );
    
    // Verifica se deve subtrair as deducoes das receitas
    if ($lLiquidoDeducao) {

      // Somente para as receitas acima de 2007
      if ($oParametros->iExercicio <= 2007) {
        $oParametros->rows = array();

        return json_encode($oParametros);
      }

      $this->__organizaReceitasLiquidasDeducoes( $aListaReceitas );
    }

    $iCount = count($aListaReceitas);
  
    if( $iCount > 0 ) {
     $iTotalPages = ceil($iCount/$iLimit);
    } else {
     $iTotalPages = 0;
    }
  
    $oRetorno->total   = $iTotalPages;
    $oRetorno->records = $iCount;
    $oRetorno->page    = $iPages;

    foreach ($aListaReceitas as $iInd => $oReceita ) {
  
      $oRetorno->rows[$iInd]['id']   = $oReceita->id;
      $oRetorno->rows[$iInd]['cell'] = array( $oReceita->estrutural,
                                              $oReceita->analitica,
                                              utf8_encode($oReceita->descricao),
                                              $oReceita->previsao_inicial,  
                                              $oReceita->previsao_adicional,
                                              $oReceita->valor_periodo,     
                                              $oReceita->valor_acumulado,   
                                              $oReceita->valor_diferenca );   
    }
  
    return json_encode($oRetorno);
  }

  /**
   * Subtrai as deduções das receitas
   * O Estrutural das deduções começa com 9
   *
   * @param array &$aListaReceitas
   */
  private function __organizaReceitasLiquidasDeducoes(&$aListaReceitas) {
    $aDeducoes = array();

    // Coloca todas as deduções em um array separado
    foreach ($aListaReceitas as $iIndice => $oReceita) {

      if ($oReceita->estrutural[0] == 9) {

        $aDeducoes[ substr($oReceita->estrutural, 1, strlen($oReceita->estrutural)-1) ] = $oReceita;
        unset($aListaReceitas[$iIndice]);
      }
    }

    // Percorre o array das receitas verificando se existe alguma dedução a ser aplicada
    foreach ($aListaReceitas as $iIndice => &$oReceita) {

      $sEstrutural = substr($oReceita->estrutural, 1, strlen($oReceita->estrutural)-1);

      if (isset($aDeducoes[$sEstrutural])) {

        $oReceita->previsao_inicial   += $aDeducoes[$sEstrutural]->previsao_inicial;
        $oReceita->previsao_adicional += $aDeducoes[$sEstrutural]->previsao_adicional;
        $oReceita->valor_periodo      += $aDeducoes[$sEstrutural]->valor_periodo;
        $oReceita->valor_acumulado    += $aDeducoes[$sEstrutural]->valor_acumulado;
        $oReceita->valor_diferenca    += $aDeducoes[$sEstrutural]->valor_diferenca;
      }
    }

    // Reorganiza o array para não ficarem indices faltando (Senão da erro na JQGrid)
    sort($aListaReceitas);
  }

  public function getRecursos() {

    $this->layout     = 'ajax';
    $this->autoRender = false;
  
    $iPages      = $_POST['page'];
    $iLimit      = $_POST['rows'];
    $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
    $oParametros = json_decode(stripslashes($_POST['aParametros']));
  
    $sWhere  = "     receitas.exercicio      = {$oParametros->iExercicio}   ";
    $sWhere .= " and receitas.instituicao_id = {$oParametros->iInstituicao} ";
  
    if (isset($_POST['filters'])) {
    	
  	  $oFiltros = json_decode(stripslashes($_POST['filters']));
  	  $aFiltros = $oFiltros->rules;
  	  
  	  foreach ( $aFiltros as $oFiltro ) {
  	    $sWhere .= " and {$oFiltro->field} ilike '%{$oFiltro->data}%' ";
  	  }    
    }
    
    $aListaRecursos = $this->Recurso->getReceitas($sWhere);
  
    $iCount = count($aListaRecursos);
  
    if( $iCount > 0 ) {
      $iTotalPages = ceil($iCount/$iLimit);
    } else {
      $iTotalPages = 0;
    }
  
    $oRetorno->total   = $iTotalPages;
    $oRetorno->records = $iCount;
    $oRetorno->page    = $iPages;
  
    $sOffSet = $iLimit * $iPages - $iLimit;
  
    $aListaRecursos = $this->Recurso->getReceitas($sWhere,$sOrderBy,$iLimit." offset $sOffSet ", 
                                                  $oParametros->iExercicio, 
                                                  $oParametros->iMes);
     
    foreach ($aListaRecursos as $iInd => $oRecuros ) {
  
      $oRetorno->rows[$iInd]['id']   = $oRecuros->id;
      $oRetorno->rows[$iInd]['cell'] = array(utf8_encode($oRecuros->descricao),
                                                         $oRecuros->previsao_inicial,
                                                         $oRecuros->previsao_adicional,
                                                         $oRecuros->valor_periodo,
                                                         $oRecuros->valor_acumulado,
                                                         $oRecuros->valor_diferenca);
    }
  
    echo json_encode($oRetorno);

  }

}