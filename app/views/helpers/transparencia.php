<?php
class TransparenciaHelper extends Helper  {

 var $helpers = array('CakePtbr.Formatacao');


 function showHistoricoReceita($aHistoricos=array()) {
   
  return $this->showHistorico($aHistoricos,1);
 }


 function showHistoricoDespesa($aHistoricos=array()) {

  return $this->showHistorico($aHistoricos,2);
 }


 function showHistorico($aHistoricos=array(), $iTipo=1 ){

  $sHtml  = "<table  class='historico_valores' width='100%' cellspacing='0'>";
   
   
  if ( count($aHistoricos) > 1 ) {

   foreach ( $aHistoricos as $iInd => $aHistorico ) {
    
    if ( $iTipo == 1 ) {
      
     if ( $iInd == 0 ) {

      $sHtml .= "  <tr>";
      $sHtml .= "    <th width='80%' align='center'>{$aHistorico['descricao']} </th>";
      $sHtml .= "    <th width='20%' align='center'>{$aHistorico['previsao_inicial']}     </th>";
      $sHtml .= "  </tr>";
     } else {

      $sHtml .= "  <tr>";
      $sHtml .= "    <td align='left' >{$aHistorico['descricao']}                      </td>";
      $sHtml .= "    <td align='right'>{$this->Formatacao->moeda($aHistorico['previsao_inicial'])}</td>";
      $sHtml .= "  </tr>";
     }
      
    } else {
      
     if ( $iInd == 0 ) {
      	
      $sHtml .= "  <tr>";
      $sHtml .= "    <th width='40%' align='center'>{$aHistorico['descricao']}      </th>";
      $sHtml .= "    <th width='15%' align='center'>{$aHistorico['valor_empenhado']}</th>";
      $sHtml .= "    <th width='15%' align='center'>{$aHistorico['valor_anulado']}  </th>";
      $sHtml .= "    <th width='15%' align='center'>{$aHistorico['valor_liquidado']}</th>";
      $sHtml .= "    <th width='15%' align='center'>{$aHistorico['valor_pago']}     </th>";
      $sHtml .= "  </tr>";
     } else {
      	
      $sHtml .= "  <tr>";
      $sHtml .= "    <td align='left' >{$aHistorico['descricao']}                                </td>";
      $sHtml .= "    <td align='right'>{$this->Formatacao->moeda($aHistorico['valor_empenhado'])}</td>";
      $sHtml .= "    <td align='right'>{$this->Formatacao->moeda($aHistorico['valor_anulado'])}  </td>";
      $sHtml .= "    <td align='right'>{$this->Formatacao->moeda($aHistorico['valor_liquidado'])}</td>";
      $sHtml .= "    <td align='right'>{$this->Formatacao->moeda($aHistorico['valor_pago'])}     </td>";
      $sHtml .= "  </tr>";
     }
    }
   }
  }
   
  $sHtml .= "</table>";

  return $sHtml;

 }


 function showDataAtualizacao($dtData='') {

  $sHtml  = "<div id='data_atualizacao'>";
  $sHtml .= "		  Dados atualizado até : {$this->Formatacao->data($dtData)}";
  $sHtml .= "</div>";

  return $sHtml;
 }

}
?>