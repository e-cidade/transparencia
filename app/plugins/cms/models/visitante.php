<?php
class Visitante extends CmsAppModel {

	var $name = 'Visitante';


	public function getVisitantes () {

		$sSqlVisitantes = "select quantidade from cms.visitantes";
		$aVisitantes    = $this->query($sSqlVisitantes);
		
		return $aVisitantes[0][0]['quantidade']; 
		
	}
	
	public function addVisitante () {
		
		$this->query("update cms.visitantes set quantidade = quantidade + 1");
		
	}
	
}