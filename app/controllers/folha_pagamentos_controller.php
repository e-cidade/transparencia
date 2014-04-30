<?php

class FolhaPagamentosController extends AppController {

  public $components = array(
    'RequestHandler'
  );

  public $helpers = array(
    'Javascript'
  );

  public function index() {
    
    $aMeses = $aAnos = $aCargos = $aLotacoes = $aVinculos = array();
    $iMatricula = $sNome = '';

    $aInstituicoes = $this->__getInstituicoes();

    /**
     * Peristir os dados do filtro quando voltar para a tela de busca
     */
    if (!empty($_GET['instituicao'])) {
      
      // Persiste instituicao
      $this->data['Filtro']['instituicao'] = $_GET['instituicao'];
      $aAnos = $this->__getAnos($_GET['instituicao']);


      // Persiste o ano
      if (!empty($_GET['ano'])) {

        $this->data['Filtro']['ano'] = $_GET['ano'];
        $aMeses = $this->__getMeses($_GET['ano'], $_GET['instituicao']);

        // Persiste o mês
        if (!empty($_GET['mes'])) {
          
          $this->data['Filtro']['mes'] = $_GET['mes'];
          
          // Persiste cargos
          $aCargos = $this->__getCargos($_GET['ano'], $_GET['mes'], $_GET['instituicao']);
          if (!empty($_GET['cargo'])) {
            $this->data['Filtro']['cargo'] = $_GET['cargo'];
          }

          //Persiste lotações
          $aLotacoes = $this->__getLotacoes($_GET['ano'], $_GET['mes'], $_GET['instituicao']);
          if (!empty($_GET['lotacao'])) {
            $this->data['Filtro']['lotacao'] = $_GET['lotacao'];
          }

          // Persiste vinculos
          $aVinculos = $this->__getVinculos($_GET['ano'], $_GET['mes'], $_GET['instituicao']);
          if (!empty($_GET['vinculo'])) {
            $this->data['Filtro']['vinculo'] = $_GET['vinculo'];
          }

        }
      }

    }

    // Persiste matrícula
    if (!empty($_GET['matricula'])) {
      $this->data['Filtro']['matricula'] = $iMatricula = preg_replace('/[\\\]/', '', $_GET['matricula']);
    }

    // Persiste nome
    if (!empty($_GET['nome'])) {
      $this->data['Filtro']['nome'] = $sNome = preg_replace('/[\\\]/', '', $_GET['nome']);
    }

    // Persiste checkbox de demitidos
    if (!empty($_GET['demitidos'])) {
      $this->data['Filtro']['demitidos'] = $_GET['demitidos'];
    }

    $this->set(array(
      'aAnos'         => $this->json($aAnos, false),
      'aMeses'        => $this->json($aMeses, false),
      'aInstituicoes' => $this->json($aInstituicoes, false),
      'aCargos'       => $this->json($aCargos, false),
      'aLotacoes'     => $this->json($aLotacoes, false),
      'aVinculos'     => $this->json($aVinculos, false),
      'matricula'     => $this->json($iMatricula, false),
      'nome'          => $this->json($sNome, false)
    ));

  }

  /**
   *
   */
  public function pesquisar() {
    unset($_GET['url']);

    // Se for ajax, quer dizer que veio da jqGrid
    if ($this->RequestHandler->isAjax()) {
      
      // Transforma parametros de paginacao do jqGrid
      // Para o padrão do cake
      $this->passedArgs['page'] = $_POST['page'];
      $this->passedArgs['direction'] = $_POST['sord'];
      $this->passedArgs['sort'] = $_POST['sidx'];
      $this->passedArgs['limit'] = $_POST['rows'];

      // Condições do select - Regra do negócio
      $sComparacaoAdmissaoServidor  = "lpad(extract('year' from Servidor.admissao::date)::varchar, 4, '0')";
      $sComparacaoAdmissaoServidor .= "||lpad(extract('month' from Servidor.admissao::date)::varchar, 2, '0')";

      $iCompetenciaAtual  = str_pad($_POST['ano'], 4, '0', STR_PAD_LEFT);
      $iCompetenciaAtual .= str_pad($_POST['mes'], 2, '0', STR_PAD_LEFT);

      $aConditions = array(
        'AND' => array(
          'ano' => $_POST['ano'],
          'mes' => $_POST['mes'],
          'Servidor.instituicao_id' => $_POST['instituicao'],
          'Servidor.nome ILIKE' => '%'.utf8_decode($_POST['nome']).'%',
          "({$sComparacaoAdmissaoServidor})::integer <= {$iCompetenciaAtual}::integer"
        )
      );

      if (!empty($_POST['cargo'])) {
        $aConditions['AND ']['cargo'] = utf8_decode($_POST['cargo']);
      }
      if (!empty($_POST['lotacao'])) {
        $aConditions['AND ']['lotacao'] = utf8_decode($_POST['lotacao']);
      }
      if (!empty($_POST['vinculo'])) {
        $aConditions['AND ']['vinculo'] = utf8_decode($_POST['vinculo']);
      }

      if (!empty($_POST['matricula'])) {
        $aConditions['AND']['servidor_id'] = $_POST['matricula'];
        unset($aConditions['AND ']);
        unset($aConditions['AND']['Servidor.nome']);
      }

      $sComparacaoDemissaoServidor  = "lpad(extract('year' from Servidor.rescisao::date)::varchar, 4, '0')";
      $sComparacaoDemissaoServidor .= "||lpad(extract('month' from Servidor.rescisao::date)::varchar, 2, '0')";

      if (!$_POST['demitidos']) {

        $aConditions['AND'][] = "(({$sComparacaoDemissaoServidor})::integer >= {$iCompetenciaAtual} or Servidor.rescisao is null)";
      }

      // Pagina os servidores
      $aServidores = $this->paginate('FolhaPagamento.ServidorMovimentacao', $aConditions);

      // Transforma o retorno padrão do cake, no padrão do jqGrid
      $aReturn = array();

      foreach ($aServidores as $servidor) {
        // Seta os regitros para o jqGrid
        $aReturn['rows'][] = array(
          'cell' => array(
            $servidor['Servidor']['id'],
            $servidor['Servidor']['nome'],
            $servidor['ServidorMovimentacao']['cargo'],
            $servidor['ServidorMovimentacao']['lotacao']
          ),
          'id' => $servidor['ServidorMovimentacao']['id']
        );
      }

      // Seta algumas informações de paginação para jqGrid
      $aReturn['page'] = $_POST['page'];
      $aReturn['total'] = $this->params['paging']['ServidorMovimentacao']['pageCount'];
      $aReturn['records'] = $this->params['paging']['ServidorMovimentacao']['count'];

      // Sai do programa com um JSON dos regitros paginados
      exit($this->json($aReturn));

    } else {

      $this->set('aParametros', $_GET);
    }

  }

  /**
   * Retorna as intituicoes que estão dentro de um determinada competência
   * @return array - Instituicoes
   * @access private
   */
  private function __getInstituicoes() {
    $this->loadModel('Instituicao');

    return $this->Instituicao->getInstituicoesServidores();
  }

  /**
   * Retorna os meses de um ano, cadastrados no banco
   * @param integer - $iAno - Ano
   * @param integer - $iInstituicao - ID da instituicao
   * @return JSON<integer, integer> - Meses
   * @access public
   */
  public function getMeses($iAno, $iInstituicao) {

    if (!$this->RequestHandler->isAjax())  {
      $this->redirect('index');
    }
    
    $aMeses = $this->__getMeses($iAno, $iInstituicao);

    $this->layout = false;
    $this->autoRender = false;

    return $this->json($aMeses);
  }

  /**
   * Retorna o meses de um ano, cadastrados no banco
   * @param integer - $iAno - Ano
   * @param integer - $iInstituicao - ID da instituicao
   * @return integer[] meses
   * @access private
   */
  private function __getMeses($iAno, $iInstituicao) {
    return $this->FolhaPagamento->ServidorMovimentacao->getMeses($iAno, $iInstituicao);
  }

  /**
   * Retorna os anos disponiveis da folha para a instituicao
   * @param integer - $iInstituicao - ID da instituicao
   * @return JSON<integer, integer> - Anos
   * @access public
   */
  public function getAnos($iInstituicao) {

    if (!$this->RequestHandler->isAjax())  {
      $this->redirect('index');
    }
    
    $aAnos = $this->__getAnos($iInstituicao);

    $this->layout = false;
    $this->autoRender = false;

    return $this->json($aAnos);
  }

  /**
   * Retorna os anos disponiveis da folha para a instituicao
   * @param integer - $iInstituicao - ID da instituicao
   * @return array - Anos
   * @access private
   */
  private function __getAnos($iInstituicao) {
    return $this->FolhaPagamento->ServidorMovimentacao->getAnos($iInstituicao);
  }


  /**
   * Retorna cargos de um instituicao dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param integer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituicao
   * @return JSON<integer, string> - Cargos
   * @access public
   */
  public function getCargos($iAno, $iMes, $iInstituicao) {
    if (!$this->RequestHandler->isAjax())  {
      $this->redirect('index');
    }
    
    $aCargos = $this->__getCargos($iAno, $iMes, $iInstituicao);

    $this->layout = false;
    $this->autoRender = false;

    return $this->json($aCargos);
  }

  /**
   * Retorna cargos de um instituicao dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param integer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituicao
   * @return array - Cargos
   * @access private
   */
  private function __getCargos($iAno, $iMes, $iInstituicao) {
    return $this->FolhaPagamento->ServidorMovimentacao->getCargos($iAno, $iMes, $iInstituicao);
  }

  /**
   * Retorna lotações de um instituicao dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param integer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituicao
   * @return JSON<string, string> - Lotações
   * @access public
   */
  public function getLotacoes($iAno, $iMes, $iInstituicao) {
    if (!$this->RequestHandler->isAjax())  {
      $this->redirect('index');
    }
    
    $aLotacoes = $this->__getLotacoes($iAno, $iMes, $iInstituicao);

    $this->layout = false;
    $this->autoRender = false;

    return $this->json($aLotacoes);
  }

  /**
   * Retorna lotacoes de um instituicao dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param integer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituicao
   * @return array - Lotacoes
   * @access private
   */
  private function __getLotacoes($iAno, $iMes, $iInstituicao) {
    return $this->FolhaPagamento->ServidorMovimentacao->getLotacoes($iAno, $iMes, $iInstituicao);
  }

  /**
   * Retorna os vínculos de uma instituição dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param intefer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituição
   * @return JSON<string, string> - Vinculos
   * @access public
   */
  public function getVinculos($iAno, $iMes, $iInstituicao) {
    if (!$this->RequestHandler->isAjax()) {
      $this->redirect('index');
    }

    $aVinculos = $this->__getVinculos($iAno, $iMes, $iInstituicao);

    $this->layout = false;
    $this->autoRender = false;

    return $this->json($aVinculos);
  }

  /**
   * Retorna os vínculos de uma instituição dentro de uma determinada competência
   * @param integer - $iAno - Ano
   * @param intefer - $iMes - Mes
   * @param integer - $iInstituicao - ID da instituição
   * @return array - Vinculos
   * @access private  
   */
  private function __getVinculos($iAno, $iMes, $iInstituicao) {
    return $this->FolhaPagamento->ServidorMovimentacao->getVinculos($iAno, $iMes, $iInstituicao);
  }

  /**
   * Renderiza os dados detalhados do servidor
   *
   * @param Integer iMovimentacaoId
   */
  public function view($iMovimentacaoId) {
    $aServidorMovimentacao = $this->FolhaPagamento->ServidorMovimentacao->getMovimentacao($iMovimentacaoId);

    if (!empty($aServidorMovimentacao)) {
      $this->loadModel('Assentamento');

      $aServidorMovimentacao['FolhaPagamento'] = $this->FolhaPagamento->getFolha($iMovimentacaoId);
      $aServidorMovimentacao['Assentamento']   = $this->Assentamento->getAssentamento( $aServidorMovimentacao['Servidor']['id'] );      
    } else {
      $this->redirect('/');
    }


    $aLegendaFolhaPagamento = array(
        '13salario'    => '13º Salário',
        'adiantamento' => 'Adiantamento',
        'salario'      => 'Salário',
        'complementar' => 'Complementar',
        'rescisao'     => 'Rescisão'
    );
    
    $this->set(array('aServidorMovimentacao', 'aLegendaFolhaPagamento'), array($aServidorMovimentacao, $aLegendaFolhaPagamento));
  }

}


?>
