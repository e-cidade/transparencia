<?php //echo $this->Html->css('../cms/css/dashboard/dashboard'); ?>

<div class="dashboard well well-large">

	<ul class="item-list">

		<?php foreach($items as $item) : ?>

			<li class="item">
				<?php echo $this->Html->link(
					$this->Html->image($item['image'], array('class' => 'item-image img-polaroid', 'width' => '100%', 'height' => '100%')) . 
					$this->Html->tag('span',$item['title'], array('class' => 'item-text')), 
					$item['url'], 
					$item
				); ?>
			</li>

		<?php endforeach; ?>

	</ul>

</div>