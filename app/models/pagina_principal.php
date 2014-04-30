<?php
class PaginaPrincipal extends AppModel {
	
	var $name = 'PaginaPrincipal';
	
	function __construct() {}
	
	/**
	 * Busca os itens cadastrados e coloca estes itens em um objeto stdClass e armazena em um array
	 * @return array
	 */
	function getItens() {
		
		$sSqlItens          = "select * from paginaprincipalitens order by id";
		$aBuscaItens        = $this->query($sSqlItens);
		$aItensBuscaRetorno = array();
		foreach ($aBuscaItens as $aItem) {
			
			$oStdItem             = new stdClass();
			$oStdItem->id         = $aItem[0]['id'];
			$oStdItem->descricao  = $aItem[0]['descricao'];
			$oStdItem->resumo     = $aItem[0]['resumo'];
			$oStdItem->acao       = $aItem[0]['acao'];
			$oStdItem->habilitado = $aItem[0]['habilitado'];
			$aItensBuscaRetorno[] = $oStdItem;
			unset($oStdItem);
		}
		return $aItensBuscaRetorno;
	}
}
?>