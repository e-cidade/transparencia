<?php echo $this->Html->docType('xhtml-trans');?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
	header('Content-Type: text/html; charset=utf-8');

	echo $this->Html->tag('link', null,array('href' => $this->base, 'id' => 'base'));

	echo $this->Html->css('style.min');
	echo $this->Html->css('jquery-ui');
	echo $this->Html->css('ui.jqgrid');

	echo $this->Html->script('jquery');
	echo $this->Html->script('jquery.json');
	echo $this->Html->script('grid.locale-pt-br');
	echo $this->Html->script('jqGrid');
?>
<title>Portal da Transparência</title>
</head>
<body>
<div id="general_content">
	<div id="main_header">
		<?php 
			echo $this->element('admin', array("plugin" => "cms"));
		?>

		<?php

			echo $this->Html->link(
				$this->Html->image('top_header.png', array('width' => '100%', 'height' => '100%')),
				'/',
				array('escape' => false, 'class' => 'home')
			);


		?>

	</div>
	<div id="main_content">
	  <?php echo $content_for_layout; ?>
	</div>
	<div id="main_footer">
	  <div id="footer">
		   DBSeller Serviços de Informática Ltda. - Porto Alegre - RS | <a  href="http://www.dbseller.com.br">www.dbseller.com.br</a><br>
	  </div> 
	</div>

	<div id="ajax-loader" style="display: none">
    <div class="ajax-loader-mask"></div>
    <?php echo $this->Html->image('loader.gif'); ?>
  </div>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>
