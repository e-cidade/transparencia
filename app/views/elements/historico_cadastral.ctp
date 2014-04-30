<?php if (empty($aAssentamentos)) : ?>

  <span>Nenhuma informação cadastral para este servidor.</span>

<?php else: ?>
  <div class="table-container fixed-header">
    <table class="header">
      <thead>
        <tr>
          <th>Assentamento</th>
          <th>Data Início</th>
          <th>Data Final</th>
          <th>Dias</th>
        </tr>
      </thead>
    </table>

    <div class="body-container">
      <table class="body">
        <tbody>
          <?php foreach($aAssentamentos as $aAssentamento): ?>
            <tr>
              <td><?php echo utf8_encode($aAssentamento['Assentamento']['descricao']); ?></td>
              <td align="center"><?php 
                  echo (!empty($aAssentamento['Assentamento']['data_concessao']) ? 
                      date("d/m/Y", strtotime($aAssentamento['Assentamento']['data_concessao'])) : ''); 
                ?></td>
              <td align="center"><?php 
                  echo (!empty($aAssentamento['Assentamento']['data_termino']) ? 
                      date("d/m/Y", strtotime($aAssentamento['Assentamento']['data_termino'])) : '');
                ?></td>
              <td align="center"><?php 
                  echo ($aAssentamento['Assentamento']['quantidade_dias'] ? 
                      $aAssentamento['Assentamento']['quantidade_dias'] : '&nbsp;'); 
                ?>&nbsp;&nbsp;</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>