<?php
class DespesasController extends AppController {

  var $name       = 'Despesas';

  // Models utilizados pelo controller
  var $uses       = array('Instituicao',
                          'Dotacao',
                          'Empenho',
                          'Orgao',
                          'Planoconta',
                          'Pessoa',
                          'EmpenhoMovimentacao',
                          'Importacao');
  
  var $components = array('RequestHandler');
  
  var $helpers    = array('CakePtbr.Formatacao', // Helper de Formatação para padrão Português
                          'Crumb',               // Helper de criação da navegação do portal
                          'JavaScript',          // Helper de auxílio para javascript
                          'Transparencia');      // Helper de auxílio para a construção das telas do portal 

  
  
  /**
   *  Método de direcionamento da página inicial do controller
   */
  function index() {

  	// Consulta a lista de exercícios disponíveis para a despesa 
    $aListaExercicios = $this->Empenho->find('all',array('fields'=>'DISTINCT Empenho.exercicio',
		                                                      'order'=>'Empenho.exercicio DESC'));

    // Retorna a data de atualização dos dados
    $dtDataImportacao = $this->Importacao->getUltimaImportacao();

    // Define o valor das variáveis disponíveis na view 
    $this->set('aListaExercicios',$aListaExercicios);
    $this->set('dtDataImportacao',$dtDataImportacao);
  }
  
  
  /**
   *  Método de redirecionamento do formulário a partir da view informada
   *  utilizado para o carregamento das Grids de despesa do portal 
   *
   * @param string $sView
   */
  function loadLink($iIdLink=''){

    $aParametros = $_POST;
    
  	// Define layout padrão com conteúdo vazio 
    $this->layout = 'ajax';

    // Caso não houver view informada será carregado a página principal
    if ( $iIdLink=='') {
      $this->redirect($this->base);
    }

    $aListaLinks[1] = array('instituicoes',
                            'orgaos',
                            'elementos',
                            'credores',
                            'empenhos',
                            'empenhos_movimentacoes');
        
    $aListaLinks[2] = array('elementos',
                            'instituicoes',
                            'orgaos',
                            'credores',
                            'empenhos',
                            'empenhos_movimentacoes');    
    
    $aListaLinks[3] = array('credores',
                            'instituicoes',
                            'empenhos',
                            'empenhos_movimentacoes');
    // Diárias
    $aListaLinks[4] = array('instituicoes',
                            'credores',
                            'empenhos',
                            'empenhos_movimentacoes');    
    
    $aListaLinks[5] = array('credores',
                            'instituicoes',
                            'empenhos',
                            'empenhos_movimentacoes');    
    
    
    if ($aParametros['sViewAtual'] == '') {
      
      $sView = $aListaLinks[$iIdLink][0];
      
    } else {
        
      foreach ($aListaLinks[$iIdLink] as $iInd => $sViewAtual ) {
        
        if ( $sViewAtual == $aParametros['sViewAtual']) {
          $sView = $aListaLinks[$iIdLink][($iInd+1)];
        }
      }
    }

    $aParametros['iIdLink']    = $iIdLink;
    $aParametros['sViewAtual'] = $sView;
    
    // Define o valor das variáveis disponíveis na view
	  $this->set('aParametros',$aParametros);
	  
	  // Renderização da view informada
	  $this->render($sView);
  }

  
  function loadView($sView=''){
    
     // Define layout padrão com conteúdo vazio 
    $this->layout = 'ajax';

    // Caso não houver view informada será carregado a página principal
    if ( $sView=='') {
      $this->redirect($this->base);
    }

    // Define o valor das variáveis disponí­veis na view
    $this->set('aParametros',$_POST);
    
    // Renderização da view informada
    $this->render($sView);
  }
  
  
  function loadDiarias(){
    

    // Consulta a lista de exercícios disponíveis para a despesa 
    $aListaExercicios = $this->Empenho->find('all',array('fields'=>'DISTINCT Empenho.exercicio',
                                                          'order'=>'Empenho.exercicio DESC'));

    // Retorna a data de atualização dos dados
    $dtDataImportacao = $this->Importacao->getUltimaImportacao();
    
    $iElemento = $this->Planoconta->getElementoDiaria();
    $aParametros['iElemento'] = $iElemento;

    // Define o valor das variáveis disponíveis na view 
    $this->set('aListaExercicios',$aListaExercicios);
    $this->set('dtDataImportacao',$dtDataImportacao);
    
     // Define o valor das variáveis disponíveis na view
    $this->set('aParametros',$aParametros);    

    // Renderização da view informada
    $this->render('diarias');
  }
  

  /**
   *  Método de carregamento do formulário com os dados da movimentação do empenho
   */
  function empenhos_movimentacoes_dados(){

    // Define layout padrão com conteúdo vazio
    $this->layout = 'ajax';

    // Consulta os dados da movimentação informada
    $sWhere = " empenhos_movimentacoes.id = ".$_POST['iMovimentacaoEmpenho'];
    $aListaDados = $this->EmpenhoMovimentacao->getMovimentacoes($sWhere);

    // Define o valor das variáveis disponíveis na view    
    $this->set('aParametros'        ,$_POST);
    $this->set('aDadosMovimentacao' ,$aListaDados[0][0]);
  }

  
  /**
   *  Método de carregamento do formulário com os dados do empenho
   */
  function empenhos_dados(){

    // Define layout padrão com conteúdo vazio
    $this->layout = 'ajax';
    
    // Código do empenho e movimentação a partir do parâmetro enviado por POST
    $iEmpenho = $_POST['iEmpenho'];
    $iMovimentacao = $_POST['iMovimentacaoEmpenho'];
    
    // Consulta os dados do empenho
    $aDadosEmpenho   = $this->Empenho->getDadosEmpenho(" empenhos.id = {$iEmpenho}");

    // Consulta os dados da movimentação informada
    $aListaDados = $this->EmpenhoMovimentacao->getMovimentacoes(" empenhos_movimentacoes.id = {$iMovimentacao} ");
    
    // Consulta os processo do empenho
    $aDadosProcessos = $this->Empenho->getProcessos(" empenho_id = {$iEmpenho}");
    
    $sProcessos = '';

    // Cria uma string separada por vírgula com os processos do empenho
    if ( count($aDadosProcessos) > 0 ) {
    	$sProcessos = implode(", ",$aDadosProcessos[0][0]);
    }
    
    // Define o valor das variáveis disponíveis na view    
    $this->set('aParametros'       ,$_POST);
    $this->set('aDadosEmpenho'     ,$aDadosEmpenho[0][0]);
    $this->set('aDadosMovimentacao',$aListaDados[0][0]);    
    $this->set('sProcessos'        ,$sProcessos);
  }
 
 
  /**
   *  Método de consulta das instituições
   */
  function getInstit() {

    // Define layout padrão com conteúdo vazio  	
    $this->layout     = 'ajax';
    $this->autoRender = false;

    $iPages      = $_POST['page'];
    $iLimit      = $_POST['rows'];
    $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
    $oParametros = json_decode(stripslashes($_POST['aParametros']));

	  $aWhere = array();
    
    if (isset($oParametros->iCredor) && trim($oParametros->iCredor) != '') {
      $aWhere[] = "pessoas.codpessoa  = {$oParametros->iCredor}";
    }   	  
	  
	  if ( isset($oParametros->iElemento) && trim($oParametros->iElemento) != '' ) {
	    $aWhere[] = "planocontas.codcon = {$oParametros->iElemento}";
	  }
    
    $sWhere = implode(" and ",$aWhere);
	
	  $aListaInstituicoes = $this->Empenho->getEmpenhosByNivel(1,$oParametros->iExercicio,$sWhere);
	
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
	  $aListaInstituicoes = $this->Empenho->getEmpenhosByNivel(1,$oParametros->iExercicio,$sWhere,$sOrderBy,$iLimit." offset $sOffSet ");
	   
	  foreach ($aListaInstituicoes as $iInd => $aInstituicao ) {
	
	   $iIdInstituicao   = $aInstituicao[0]['id'];
	   $sNomeInstituicao = $aInstituicao[0]['descricao'];
	   $nVlrEmpenhado    = $aInstituicao[0]['valor_empenhado'];
	   $nVlrAnulado      = $aInstituicao[0]['valor_anulado'];
	   $nVlrLiquidado    = $aInstituicao[0]['valor_liquidado'];
	   $nVlrPago         = $aInstituicao[0]['valor_pago'];
	
	   $oRetorno->rows[$iInd]['id']   = $iIdInstituicao;
	   $oRetorno->rows[$iInd]['cell'] = array(utf8_encode($sNomeInstituicao),
	   $nVlrEmpenhado,
	   $nVlrAnulado,
	   $nVlrLiquidado,
	   $nVlrPago);
	   
	  }
	
	  echo json_encode($oRetorno);
  }


  /**
   *  Método de consulta dos Orgãos
   */
	function getOrgao() {
	   
    // Define layout padrão com conteúdo vazio		
	  $this->layout     = 'ajax';
	  $this->autoRender = false;
	
	  $iPages      = $_POST['page'];
	  $iLimit      = $_POST['rows'];
	  $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
	  $oParametros = json_decode(stripslashes($_POST['aParametros']));
	  
	  $aWhere = array();

	  if (isset($oParametros->iInstituicao) && trim($oParametros->iInstituicao) != '') {
  	  $aWhere[] = "empenhos.instituicao_id = {$oParametros->iInstituicao}";
	  }
	  
	  if (isset($oParametros->iElemento) && trim($oParametros->iElemento) != '') {
	    $aWhere[] = "planocontas.codcon      = {$oParametros->iElemento}";
	  }
    
	  if ( isset($_POST['filters']) ) {
	    
		  $oFiltros = json_decode(stripslashes($_POST['filters']));
		  $aFiltros = $oFiltros->rules;
		  
		  foreach ( $aFiltros as $oFiltro ) {
		   	$aWhere[] = "orgaos.{$oFiltro->field} ilike '%{$oFiltro->data}%'";
		  }  
	  }
	  
    $sWhere = implode(" and ",$aWhere);
    
	  $aListaOrgaos = $this->Empenho->getEmpenhosByNivel(2,$oParametros->iExercicio,$sWhere);
	
	  $iCount = count($aListaOrgaos);
	
	  if( $iCount > 0 ) {
	   $iTotalPages = ceil($iCount/$iLimit);
	  } else {
	   $iTotalPages = 0;
	  }
	
	  $oRetorno->total   = $iTotalPages;
	  $oRetorno->records = $iCount;
	  $oRetorno->page    = $iPages;
	
	  $sOffSet      = $iLimit * $iPages - $iLimit;
	  $aListaOrgaos = $this->Empenho->getEmpenhosByNivel(2,$oParametros->iExercicio,$sWhere,$sOrderBy,$iLimit." offset $sOffSet ");
	   
	  foreach ($aListaOrgaos as $iInd => $aOrgao ) {
	
	    $oRetorno->rows[$iInd]['id']   = $iInd;
	    $oRetorno->rows[$iInd]['cell'] = array( utf8_encode($aOrgao[0]['descricao']),
																					    $aOrgao[0]['valor_empenhado'],
																					    $aOrgao[0]['valor_anulado'],
																					    $aOrgao[0]['valor_liquidado'],
																					    $aOrgao[0]['valor_pago'],
																					    $aOrgao[0]['codorgao']);
	  }
	
	  echo json_encode($oRetorno);
	
  }


  /**
   *  Método de consulta dos elementos ( Plano de Contas )
   */
	function getElementos() {
	
    // Define layout padrão com conteúdo vazio		
	  $this->layout     = 'ajax';
	  $this->autoRender = false;
	
	  $iPages      = $_POST['page'];
	  $iLimit      = $_POST['rows'];
	  $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
	  $oParametros = json_decode(stripslashes($_POST['aParametros']));
	  $aWhere      = array();
	
	  if ( isset($oParametros->iInstituicao) && trim($oParametros->iInstituicao) != '' ) {
	    $aWhere[] = " empenhos.instituicao_id  = {$oParametros->iInstituicao} ";
	  }
	
	  if ( isset($oParametros->iOrgao) && trim($oParametros->iOrgao) != '' ) {
	    $aWhere[] = " orgaos.codorgao  = {$oParametros->iOrgao}      ";
	  }
	  
	  $sWhere = implode(' and ',$aWhere);
	  $sWhereExterno = '';
	  
	  if ( isset($_POST['filters']) ) {
	  	
		  $oFiltros = json_decode(stripslashes($_POST['filters']));
		  $aFiltros = $oFiltros->rules;
		  $aWhereExterno = array();
		  
		  foreach ( $aFiltros as $oFiltro ) {
		    $aWhereExterno[] = " {$oFiltro->field} ilike '%{$oFiltro->data}%' ";
		  }    
		  
		  $sWhereExterno = implode(" and ",$aWhereExterno);
	  }
	  
	  $aListaElementos = $this->Empenho->getEmpenhosByNivel(3,$oParametros->iExercicio,$sWhere,null,null,$sWhereExterno);
	
	  $iCount = count($aListaElementos);
	
	  if( $iCount > 0 ) {
	    $iTotalPages = ceil($iCount/$iLimit);
	  } else {
	    $iTotalPages = 0;
	  }
	
	  $oRetorno->total   = $iTotalPages;
	  $oRetorno->records = $iCount;
	  $oRetorno->page    = $iPages;
	
	  $sOffSet         = $iLimit * $iPages - $iLimit;
	  $aListaElementos = $this->Empenho->getEmpenhosByNivel(3,$oParametros->iExercicio,$sWhere,$sOrderBy,$iLimit." offset $sOffSet ",$sWhereExterno);
	   
	  foreach ($aListaElementos as $iInd => $aElemento ) {
	
	    $oRetorno->rows[$iInd]['id']   = $iInd;
	    $oRetorno->rows[$iInd]['cell'] = array( utf8_encode($aElemento[0]['grupo']),
																				      utf8_encode($aElemento[0]['descricao']),
																				      $aElemento[0]['valor_empenhado'],
																				      $aElemento[0]['valor_anulado'],
																				      $aElemento[0]['valor_liquidado'],
																				      $aElemento[0]['valor_pago'],
																				      $aElemento[0]['codcon']);
	  }
	
	  echo json_encode($oRetorno);
	}
	

	/**
	 *  Método de consulta dos Empenhos
	 */
  function getEmpenhos() {
	
    // Define layout padrão com conteúdo vazio  	
	  $this->layout     = 'ajax';
	  $this->autoRender = false;
	
	  $iPages      = $_POST['page'];
	  $iLimit      = $_POST['rows'];
	  $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
	  $oParametros = json_decode(stripslashes($_POST['aParametros']));
	  
	  $aWhere      = array();
	  
    if (isset($oParametros->iInstituicao) && trim($oParametros->iInstituicao) != '') {
      $aWhere[] = "empenhos.instituicao_id = {$oParametros->iInstituicao}";
    }
    if (isset($oParametros->iOrgao) && trim($oParametros->iOrgao) != '') {
      $aWhere[] = "orgaos.codorgao         = {$oParametros->iOrgao}";
    }
    if (isset($oParametros->iElemento) && trim($oParametros->iElemento) != '') {
      $aWhere[] = "planocontas.codcon      = {$oParametros->iElemento}";
    }	  
    if (isset($oParametros->iCredor) && trim($oParametros->iCredor) != '') {
      $aWhere[] = "pessoas.codpessoa       = {$oParametros->iCredor}";
    }   

	  
	  if ( isset($_POST['filters']) ) {
	  	
		  $oFiltros = json_decode(stripslashes($_POST['filters']));
		  $aFiltros = $oFiltros->rules;
		  
		  foreach ( $aFiltros as $oFiltro ) {
		  	
		    switch ($oFiltro->field) {
		    	case "funcao_descricao":
		    		$sNomeCampo = "funcoes.descricao";
		    		break;
		    	case "subfuncao_descricao":
		    			$sNomeCampo = "subfuncoes.descricao";
		    			break;
		    	case "programa_descricao":
		    		$sNomeCampo = "programas.descricao";
		    		break;
		   	  case "projeto_descricao":
		   	  	$sNomeCampo = "projetos.descricao";
		        break;
		     	case "planoconta_descricao":
		     		$sNomeCampo = "planocontas.descricao";
		        break;
		      case "recurso_descricao":
		        	$sNomeCampo = "recursos.descricao";
		       	break;
		      default:
		        $sNomeCampo = $oFiltro->field; 
		        break;
		    }
		  	
		    $aWhere[] = "{$sNomeCampo} ilike '%{$oFiltro->data}%'";
		  }        
	  }
	  
    $sWhere = implode(" and ",$aWhere);
	  
	  $aListaEmpenhos = $this->Empenho->getEmpenhosByNivel(5,$oParametros->iExercicio,$sWhere);
	
	  $iCount = count($aListaEmpenhos);
	
	  if( $iCount > 0 ) {
	   $iTotalPages = ceil($iCount/$iLimit);
	  } else {
	   $iTotalPages = 0;
	  }
	
	  $oRetorno->total   = $iTotalPages;
	  $oRetorno->records = $iCount;
	  $oRetorno->page    = $iPages;
	
	  $sOffSet         = $iLimit * $iPages - $iLimit;
	  $aListaEmpenhos = $this->Empenho->getEmpenhosByNivel(5,$oParametros->iExercicio,$sWhere,$sOrderBy,$iLimit." offset $sOffSet ");
	   
	  foreach ($aListaEmpenhos as $iInd => $aEmpenho ) {
	
	   $oRetorno->rows[$iInd]['id']   = $aEmpenho[0]['id'];
	   $oRetorno->rows[$iInd]['cell'] = array( $aEmpenho[0]['codempenho'],
	   		                                     utf8_encode($aEmpenho[0]['funcao_descricao']),
	   		                                     utf8_encode($aEmpenho[0]['subfuncao_descricao']),
																					   utf8_encode($aEmpenho[0]['programa_descricao']),
																					   utf8_encode($aEmpenho[0]['projeto_descricao']),
																					   utf8_encode($aEmpenho[0]['planoconta_descricao']),
	   		                                     utf8_encode($aEmpenho[0]['recurso_descricao']),
	                                           $aEmpenho[0]['dataemissao'],
																					   $aEmpenho[0]['valor_empenhado'],
																					   $aEmpenho[0]['valor_anulado'],
																					   $aEmpenho[0]['valor_liquidado'],
																					   $aEmpenho[0]['valor_pago']);
	  }
	
	  echo json_encode($oRetorno);
	}

	
	/**
	 *  Método de consulta das Movimentações do Empenho
	 */
	function getMovimentacoesEmpenhos() {
	
    // Define layout padrão com conteúdo vazio		
	  $this->layout     = 'ajax';
	  $this->autoRender = false;
	
	  $iPages      = $_POST['page'];
	  $iLimit      = $_POST['rows'];
	  $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
	  $oParametros = json_decode(stripslashes($_POST['aParametros']));
	
	  $sWhere  = " empenhos_movimentacoes.empenho_id = {$oParametros->iEmpenho} ";
	   
	  $aListaMovimentacoes = $this->EmpenhoMovimentacao->getMovimentacoes($sWhere);
	
	  $iCount = count($aListaMovimentacoes);
	
	  if( $iCount > 0 ) {
	    $iTotalPages = ceil($iCount/$iLimit);
	  } else {
	    $iTotalPages = 0;
	  }
	
	  $oRetorno->total   = $iTotalPages;
	  $oRetorno->records = $iCount;
	  $oRetorno->page    = $iPages;
	
	  $sOffSet             = $iLimit * $iPages - $iLimit;
	  $aListaMovimentacoes = $this->EmpenhoMovimentacao->getMovimentacoes($sWhere,$sOrderBy,$iLimit." offset $sOffSet ");
	   
	  foreach ($aListaMovimentacoes as $iInd => $aMovimentacao ) {
	
	   $oRetorno->rows[$iInd]['id']   = $aMovimentacao[0]['id'];
	   $oRetorno->rows[$iInd]['cell'] = array( $aMovimentacao[0]['data'],
																					   utf8_encode($aMovimentacao[0]['tipo']),
																					   $aMovimentacao[0]['valor'],
																					   $aMovimentacao[0]['codtipo']);
	  }
	
	  echo json_encode($oRetorno);
	}

	
	/**
	 *  Método de consulta dos Itens do Empenho
	 */
  function getItensEmpenho() {
	
    // Define layout padrão com conteúdo vazio  	
	  $this->layout     = 'ajax';
	  $this->autoRender = false;
	
	  $iPages      = $_POST['page'];
	  $iLimit      = $_POST['rows'];
	  $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
	  $oParametros = json_decode(stripslashes($_POST['aParametros']));
	
	  $aWhere   = array();
	  $aWhere[] = "empenhos_itens.empenho_id = {$oParametros->iEmpenho}";
	   
    if ( isset($_POST['filters']) ) {
    	
      $oFiltros = json_decode(stripslashes($_POST['filters']));
      $aFiltros = $oFiltros->rules;
      
      foreach ( $aFiltros as $oFiltro ) {
        $aWhere[] = "{$oFiltro->field} ilike '%{$oFiltro->data}%'";
      }      
    }	  
	  
    $sWhere = implode(" and ",$aWhere);
    
	  $aListaItens = $this->Empenho->getItens($sWhere);
	
	  $iCount = count($aListaItens);
	
	  if( $iCount > 0 ) {
	    $iTotalPages = ceil($iCount/$iLimit);
	  } else {
	    $iTotalPages = 0;
	  }
	
	  $oRetorno->total   = $iTotalPages;
	  $oRetorno->records = $iCount;
	  $oRetorno->page    = $iPages;
	
	  $sOffSet             = $iLimit * $iPages - $iLimit;
	  $aListaItens = $this->Empenho->getItens($sWhere,$sOrderBy,$iLimit." offset $sOffSet ");
	   
	  foreach ($aListaItens as $iInd => $aItem ) {
	
	   $oRetorno->rows[$iInd]['id']   = $aItem[0]['id'];
	   $oRetorno->rows[$iInd]['cell'] = array( utf8_encode($aItem[0]['descricao']),
	                                           $aItem[0]['quantidade'],
	                                           $aItem[0]['valor_unitario'],
	                                           $aItem[0]['valor_total']);
	  }
	
	  echo json_encode($oRetorno);
	
  } 
 
  
  /**
   *  Método de consulta dos credores
   */
  function getCredores() {
  
    // Define layout padrão com conteúdo vazio    
    $this->layout     = 'ajax';
    $this->autoRender = false;
  
    $iPages      = $_POST['page'];
    $iLimit      = $_POST['rows'];
    $sOrderBy    = $_POST['sidx']." ".$_POST['sord'];
    $oParametros = json_decode(stripslashes($_POST['aParametros']));
    
    $aWhere      = array();
    
    if (isset($oParametros->iInstituicao) && trim($oParametros->iInstituicao) != '') {
      $aWhere[] = "empenhos.instituicao_id = {$oParametros->iInstituicao}";
    }
    if (isset($oParametros->iOrgao)       && trim($oParametros->iOrgao) != '') {
      $aWhere[] = "orgaos.codorgao         = {$oParametros->iOrgao}";
    }
    if (isset($oParametros->iElemento)    && trim($oParametros->iElemento) != '') {
      $aWhere[] = "planocontas.codcon      = {$oParametros->iElemento}";
    }    
    
    if ( isset($_POST['filters']) ) {
      
      $oFiltros = json_decode(stripslashes($_POST['filters']));
      $aFiltros = $oFiltros->rules;
      
      foreach ( $aFiltros as $oFiltro ) {
        $aWhere[] = "{$oFiltro->field} ilike '%{$oFiltro->data}%'";
      }      
    }
    
    $sWhere = implode(" and ",$aWhere);
    
    $aListaCredores = $this->Empenho->getEmpenhosByNivel(4,$oParametros->iExercicio,$sWhere);
  
    $iCount = count($aListaCredores);
  
    if( $iCount > 0 ) {
      $iTotalPages = ceil($iCount/$iLimit);
    } else {
     $iTotalPages = 0;
    }
  
    $oRetorno->total   = $iTotalPages;
    $oRetorno->records = $iCount;
    $oRetorno->page    = $iPages;
  
    $sOffSet         = $iLimit * $iPages - $iLimit;
    $aListaCredores  = $this->Empenho->getEmpenhosByNivel(4,$oParametros->iExercicio,$sWhere,$sOrderBy,$iLimit." offset $sOffSet ");
     
    foreach ($aListaCredores as $iInd => $aCredor ) {
  
      $iIdCredor      = $aCredor[0]['codpessoa'];
      $sCpfCnpjCredor = $aCredor[0]['cpfcnpj'];
      $sNomeCredor    = $aCredor[0]['nome'];
      $nVlrEmpenhado  = $aCredor[0]['valor_empenhado'];
      $nVlrAnulado    = $aCredor[0]['valor_anulado'];
      $nVlrLiquidado  = $aCredor[0]['valor_liquidado'];
      $nVlrPago       = $aCredor[0]['valor_pago'];
  
      $oRetorno->rows[$iInd]['id']   = $iIdCredor;
      $oRetorno->rows[$iInd]['cell'] = array($sCpfCnpjCredor,
      
      utf8_encode($sNomeCredor),
      
      $nVlrEmpenhado,
      $nVlrAnulado,
      $nVlrLiquidado,
      $nVlrPago);
    }
  
    echo json_encode($oRetorno);
  }  
  
  
  /**
   *  Método de consulta Dotação Orçamentária do Empenho
   */
  function getDotacaoEmpenho() {
  
    $oParametros   = json_decode(stripslashes($_POST['aParametros']));
    $aDadosEmpenho = $this->Empenho->find('all',array('conditions'=>array('Empenho.id'=>$oParametros->iEmpenho)));
    $iDotacao      = $aDadosEmpenho[0]['Empenho']['dotacao_id'];
    
    $this->layout     = 'ajax';
    $this->autoRender = false;
  
    $aRetonoDotacao = $this->Dotacao->find('all',array('conditions'=>array('Dotacao.id'=>$iDotacao)));
    $aDotacao = $aRetonoDotacao[0];     
  
    $oRetorno->rows[0]['id']   = $aDotacao['Orgao'     ]['id'];
    $oRetorno->rows[1]['id']   = $aDotacao['Unidade'   ]['id'];
    $oRetorno->rows[2]['id']   = $aDotacao['Funcao'    ]['id'];
    $oRetorno->rows[3]['id']   = $aDotacao['SubFuncao' ]['id'];
    $oRetorno->rows[4]['id']   = $aDotacao['Programa'  ]['id'];
    $oRetorno->rows[5]['id']   = $aDotacao['Projeto'   ]['id'];
    $oRetorno->rows[6]['id']   = $aDotacao['Planoconta']['id'];
    $oRetorno->rows[7]['id']   = $aDotacao['Recurso'   ]['id'];
      
    $oRetorno->rows[0]['cell'] = array('Orgão'            ,$aDotacao['Orgao'     ]['codorgao'    ],utf8_encode($aDotacao['Orgao'     ]['descricao']));      
    $oRetorno->rows[1]['cell'] = array('Unidade'          ,$aDotacao['Unidade'   ]['codunidade'  ],utf8_encode($aDotacao['Unidade'   ]['descricao']));
    $oRetorno->rows[2]['cell'] = array('Função'           ,$aDotacao['Funcao'    ]['codfuncao'   ],utf8_encode($aDotacao['Funcao'    ]['descricao']));
    $oRetorno->rows[3]['cell'] = array('Sub Função'       ,$aDotacao['SubFuncao' ]['codsubfuncao'],utf8_encode($aDotacao['SubFuncao' ]['descricao']));
    $oRetorno->rows[4]['cell'] = array('Programa'         ,$aDotacao['Programa'  ]['codprograma' ],utf8_encode($aDotacao['Programa'  ]['descricao']));
    $oRetorno->rows[5]['cell'] = array('Projeto/Atividade',$aDotacao['Projeto'   ]['codprojeto'  ],utf8_encode($aDotacao['Projeto'   ]['descricao']));
    $oRetorno->rows[6]['cell'] = array('Elemento'         ,$aDotacao['Planoconta']['estrutural'  ],utf8_encode($aDotacao['Planoconta']['descricao']));
    $oRetorno->rows[7]['cell'] = array('Recurso'          ,$aDotacao['Recurso'   ]['codrecurso'  ],utf8_encode($aDotacao['Recurso'   ]['descricao']));

  
    echo json_encode($oRetorno);
  
  }   
  
}