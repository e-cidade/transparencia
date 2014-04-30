<?php
class ConfiguracoesController extends CmsAppController {

  var $name       = 'Configuracoes';
  
  public function index () {
  	
    if (!empty($this->data)) {
    	
    		
    	if ($this->Configuracao->save($this->data)){
    		
    		$this->Session->setFlash('Configuração salva com sucesso.', 'default', array('class' => 'alert alert-success' ));
    		$this->redirect(array('controller' => 'dashboard'));
    	} else {
    		$this->Session->setFlash('Erro ao atualizar os dados.', 'default', array('class' => 'alert alert-error' ));
    	}
    }
  	$this->data = $this->Configuracao->find('first');
  	
  }  
  
  
  
  
}