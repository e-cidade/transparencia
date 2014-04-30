<div class="table-container fixed-header">
  <h2><?php echo mb_strtoupper($sTipoFolha); ?></h2>

  <table class="header">
    <thead>
      <tr>
        <th>Rubrica</th>
        <th>Quantidade</th>
        <th>Valor</th>
        <th>Tipo</th>
      </tr>
    </thead>
  </table>

  <div class="body-container">

    <table class="body">
      <tbody>
        <?php 
          $lPutLine = true; 
          foreach ($aFolhaPagamento as $rubrica) : 
            if (in_array($rubrica['FolhaPagamento']['rubrica'], array('Z777', 'Z888', 'Z999')) && $lPutLine): 
          ?>
              <tr>
                <td colspan="4">&nbsp;</td>
              </tr>
          <?php 
              $lPutLine = false;
            endif; 
          ?>
        <tr>
          <td>
            <?php if (!$lPutLine): ?>
              <strong>
            <?php endif; ?>
            <?php echo utf8_encode($rubrica['FolhaPagamento']['descr_rubrica']); ?>
            <?php if (!$lPutLine): ?>
              </strong>
            <?php endif; ?>
          </td>
          <td align="right">
            <?php echo $lPutLine ? number_format($rubrica['FolhaPagamento']['quantidade'], 2, ',', '.') : ''; ?>
          </td>
          <td align="right">
            <?php if (!$lPutLine): ?>
              <strong>
            <?php endif; ?>
            <?php echo number_format($rubrica['FolhaPagamento']['valor'], 2, ',', '.'); ?>
            <?php if (!$lPutLine): ?>
              </strong>
            <?php endif; ?>
          </td>
          <td align="center"><?php echo $lPutLine ? ucfirst( utf8_encode($rubrica['FolhaPagamento']['tiporubrica']) ) : ''; ?></td>
        </tr>

      <?php endforeach; ?>

      </tbody>

    </table>
  </div>
</div>