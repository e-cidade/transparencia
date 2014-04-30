<?php

class ContentHelper extends AppHelper {

  public $name = 'Content';

  public $helpers = array('Html');

  private $aVars = array();

  /**
   * Manipula o conteudo do banco, podendo ser alterado de acordo com opcoes.
   * @param string $content - Variavel com o conteudo bruto da pagina
   */
  public function write($content = '') {
    return $this->parseContent($content);
  }

  private function parseContent($content = '') {

    $this->loadVars();

    /**
     * Trocas as variaveis do conteudo, pelos seus respctivos valores.
     */ 
    return str_replace(array_flip($this->aVars), $this->aVars, $content);
  }

  /**
   * Carrega as variaveis para o helper
   * Podendo futuramente trazer de outro lugar
   */
  private function loadVars() {

    $this->aVars = array(
      '{{url_base}}' => $this->Html->base
    );

  }

}

?>