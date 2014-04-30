<h2>Cadastro de Menus</h2>

<div class="menu">

	<table class="table table-hover table-striped table-condensed">
		<thead>
			<tr>
				<td colspan="4">
					<div class="add-button pull-right"><?php
						echo $this->Html->link('Adicionar Menu', array('action' => 'edit'), array('class' => 'btn btn-inverse'));
					?></div>
				</td>
			</tr>
			<tr>
				<th style="text-align: center">Id</th>
				<th style="text-align: center">Nome</th>
				<th style="text-align: center">Visível ao Usuário</th>
				<th style="text-align: right">Ações</th>
			</tr>
		</thead>

		<tbody>

			<?php if (empty($menus)) : ?>
				<tr>
					<td style="text-align: center" colspan="4"><em>Nenhum registro encontrado.</em></td>
				</tr>	
			<?php else:?>

				<?php $count = 0; ?>

				<?php foreach ($menus as $menu) :?>
					<tr>
						<td style="text-align: center"><?php echo $menu['Menu']['id']; ?></td>
						<td style="text-align: left"><?php echo $menu['Menu']['name']; ?></td>
						<td style="text-align: center"><?php echo ($menu['Menu']['visible'] ? 'Sim' : 'Não'); ?></td>
						<td class="action">
							<div class="btn-group">
								<?php 
									echo $this->Html->link(
										$this->Html->tag('i', '', array('class' => 'icon-arrow-up')),
										array('action' => 'moveUp', $menu['Menu']['id'], 1),
										array(
											'class' => 'btn',
											'title' => 'Mover para cima',
											'escape' => false,
											'disabled' => $count == 0
										)
									);
									echo $this->Html->link(
										$this->Html->tag('i', '', array('class' => 'icon-arrow-down')),
										array('action' => 'moveDown', $menu['Menu']['id'], 1),
										array(
											'class' => 'btn',
											'title' => 'Mover para baixo',
											'escape' => false,
											'disabled' => $count == count($menus)-1
										)
									);
								?>
							</div>
							<div class="btn-group">
								<?php
									echo $this->Html->link(
										$this->Html->tag('i', '', array('class' => 'icon-edit')), 
										array('action' => 'edit',$menu['Menu']['id']), 
										array(
											'class' => 'btn', 
											'escape' => false,
											'title' => 'Editar'
										)
									);

									echo $this->Html->link(
										$this->Html->tag('i', '', array('class' => 'icon-remove')), 
										array('action' => 'delete',$menu['Menu']['id']), 
										array(
											'confirm' => 'Tem certeza que deseja apagar o menu '. $menu['Menu']['name'], 
											'class' => 'btn', 
											'escape' => false,
											'title' => 'Apagar'
										)
									);
								?>
							</div>
						</td>
					</tr>	

					<?php $count++; ?>

				<?php endforeach; ?>

			<?php endif;?>

		</tbody>
	</table>

</div>