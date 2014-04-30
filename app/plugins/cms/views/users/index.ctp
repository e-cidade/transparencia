<h2>Cadastro de Usuários</h2>

<div class="">

	<table class="table table-hover table-striped table-condensed">
		<thead>
			<tr>
				<td colspan="4">
					<div class="add-button pull-right"><?php
						echo $this->Html->link('Adicionar Usuário', array('action' => 'edit'), array('class' => 'btn btn-inverse'));
					?></div>
				</td>
			</tr>
			<tr>
				<th>Id</th>
				<th>Nome</th>
				<th>Login</th>
				<th style="text-align: right;">Ações</th>
			</tr>
		</thead>

		<tbody>

			<?php if (empty($users)) : ?>
				<tr>
					<td colspan="4"><em>Nenhum registro encontrado.</em></td>
				</tr>	
			<?php else:?>

				<?php foreach ($users as $user) :?>
					<tr>
						<td><?php echo $user['User']['id']; ?></td>
						<td><?php echo $user['User']['name']; ?></td>
						<td><?php echo $user['User']['login']; ?></td>
						<td class="action">
							<div class="btn-group">
								<?php
									echo $this->Html->link(
										$this->Html->tag('i', '', array('class' => 'icon-edit')), 
										array('action' => 'edit',$user['User']['id']), 
										array(
											'class' => 'btn', 
											'escape' => false,
											'title' => 'Editar'
										)
									);

									echo $this->Html->link(
										$this->Html->tag('i', '', array('class' => 'icon-remove')), 
										array('action' => 'delete',$user['User']['id']), 
										array(
											'confirm' => 'Tem certeza que deseja apagar o usuário '. $user['User']['name'], 
											'class' => 'btn', 
											'escape' => false,
											'title' => 'Apagar'
										)
									);
								?>
							</div>
						</td>
					</tr>	

				<?php endforeach; ?>

			<?php endif;?>

		</tbody>
	</table>

</div>