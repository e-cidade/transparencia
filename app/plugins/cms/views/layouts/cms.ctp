<?php echo $this->Html->docType('xhtml-trans');?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?php

echo $this->Html->tag('link', null,array('href' => $this->base, 'id' => 'base'));

echo $this->Html->css(array(
	'style.min'
));

if (!isset($appendBootstrap) || $appendBootstrap) :
	echo $this->Html->css('../cms/vendor/bootstrap/css/bootstrap.min');
endif;

if (!empty($styles)) :
	echo $this->Html->css($styles);
endif;

echo $this->Html->script(array(
	'jquery.min', 
	'jquery.json', 
	'system.min'
));

?>
<title>Portal da Transparência</title>
</head>
<body>
<div id="general_content">
	<div id="main_header">
		<?php 
			if ($this->action != 'principal' && $this->Session->read('Auth.User')) : 
				echo $this->element('logged');
			else:

				echo $this->element('admin');

			endif;
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


	  <?php echo $this->Session->flash(); ?>
	  <?php echo $content_for_layout; ?>
	  <div id="ajax-loader" style="display: none;"></div> 
	</div>

	<div id="main_footer">
	  <div id="footer">
		   DBSeller Serviços de Informática Ltda. - Porto Alegre - RS | <a  href="http://www.dbseller.com.br">www.dbseller.com.br</a><br>
	  </div> 
	</div>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>