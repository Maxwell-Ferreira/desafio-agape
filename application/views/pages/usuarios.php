<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	main{
		width: 100%;
	}

	table{
		background-color: #fff;
	}

	.link-active{
		background-color: #4287f5 !important;
		color: #fff !important;
	}

</style>

<main class="container d-flex flex-column p-4 align-itens-center">
	<div class="d-flex justify-content-between">
		<h1>Usuários</h1>
		<?php if($this->session->userData("loggedUser")->admin):?>
			<a href="<?=base_url()?>usuarios/novo" class="btn btn-primary">Cadastrar Usuário</a>
		<?php endif;?>
	</div>
	
	<hr>
<?php if(empty($usuarios)):?>
	<h3>Não há usuários cadastrados</h3>
<?php else: ?>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th scope="col">#ID</th>
				<th scope="col">Nome</th>
				<th scope="col">Email</th>
				<th scope="col">Login</th>
				<th scope="col">Status</th>
				<th scope="col">Permissão</th>
				<?= $this->session->userData("loggedUser")->admin ? '<th scope="col">Ações</th>' : '' ?>
			</tr>
		</thead>
		<tbody>
		<?php foreach($usuarios as $usuario): ?>
			<tr>
				<th scope="row"><?=$usuario->id?></th>
				<td><?=$usuario->nome ?></td>
				<td><?=$usuario->email ?></td>
				<td><?=$usuario->login ?></td>
				<td><?=$usuario->status ? 'Ativo' : 'Inativo' ?></td>
				<td><?=$usuario->admin ? 'Adm' : 'Comum' ?></td>
				<?=($this->session->userData("loggedUser")->admin) ?
				'<td>
					<a href="'.base_url().'usuarios/update/'.$usuario->id.'" class="btn btn-warning"><i class="bi bi-pen-fill"></i></a>
					<a href="'.base_url().'usuarios/delete/'.$usuario->id.'" class="btn btn-danger"><i class="bi bi-trash-fill"></i></a>
				</td>' : '' ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php
	echo $pagination;
	endif; 
?>
</main>