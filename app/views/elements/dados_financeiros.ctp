<?php
 if (empty($aFolhaPagamento)) { ?>

  <span>Nenhuma informação financeira para este servidor.</span>

<?php 
 } else { 

  $aFolhas = array();
  foreach (array_keys($aFolhaPagamento) as $sFolha) {
    $aFolhas[$sFolha] = $aLegendaFolhaPagamento[$sFolha];
  }

  echo $this->Form->input('sTipoFolhaPagamento', array(
                                                  'type'    => 'select',
                                                  'options' => $aFolhas,
                                                  'div'     => 'pull-right',
                                                  'default' => 'salario',
                                                  'label'   => '<b>Tipo de Folha: </b>'
                                                )
                        );

  foreach ($aFolhaPagamento as $sFolha => $aFolha) {
    echo "<div class='tipo-folha {$sFolha}'>";
      echo $this->element('tabela_dados_financeiros', array(
          'aFolhaPagamento' => $aFolha, 
          'sTipoFolha' => $aLegendaFolhaPagamento[$sFolha]
        )
      );
    echo "</div>";
  }

 } ?>