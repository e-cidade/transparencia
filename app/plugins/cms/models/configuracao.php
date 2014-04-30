<?php
class Configuracao extends CmsAppModel{

	var $name = 'Configuracao';


	public function getConfiguracoes () {

		$aConfiguracao = $this->find('first');
		return empty($aConfiguracao) || $aConfiguracao['Configuracao']['contador_visitas'];
		
	}
	
	
}