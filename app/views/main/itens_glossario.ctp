<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div class="itens_glossario">
	<ul>
		<?php 
		
		  foreach ($aListaItens as $iInd => $aItem ) {
		  	 $iId = ($iInd+1); 
		     echo "<li><a href='#item_glossario_{$iId}' > {$iId} - ".$aItem[0]['descricao']."</a></li>";  
		  }
		  
		?>
	</ul>
</div>
<br>
<br>
<br>
<div class="itens_glossario">
  <ul>
<?php 

  foreach ($aListaItens as $iInd => $aItem ) {
     $iId = ($iInd+1);
     echo "<br>";  
     echo "<h4 id='item_glossario_{$iId}'>".$aItem[0]['descricao']."</h4>";
     echo "<p>".$aItem[0]['resumo']."</p>";
     echo "<br>";  
     echo "<li><center><a href='#general_content'>Topo</a></center></li>";
     echo "<br>";
     
  }
?>
  </ul>
</div>  