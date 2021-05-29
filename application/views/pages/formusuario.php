<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	main {
		width: 100%;
	}
</style>

<main class="container d-flex flex-column p-4">
	<h1>
		<?php
		if (isset($isEdit))
			echo 'Editar Usuário';
		elseif (isset($isEditLogged))
			echo 'Editar Meus Dados';
		else
			echo 'Cadastrar Usuario';
		?>
	</h1>
	<hr>
	<form action="" method="post">
		<div class="form-group col-md-6 mb-2">
			<label for="nome" class="form-label">Nome</label>
			<input type="text" class="form-control <?= !empty($errors['nome']) ? 'is-invalid' : '' ?>" name="nome" id="nome" value="<?= !empty($form['nome']) ? $form['nome'] : '' ?>">
			<?php if (!empty($errors['nome'])) : ?>
				<div class="invalid-feedback"><?= $errors['nome'] ?></div>
			<?php endif; ?>
		</div>
		<div class="form-group col-md-6 mb-2">
			<label for="email" class="form-label">Email</label>
			<input type="text" class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" name="email" id="email" value="<?= !empty($form['email']) ? $form['email'] : '' ?>">
			<?php if (!empty($errors['email'])) : ?>
				<div class="invalid-feedback"><?= $errors['email'] ?></div>
			<?php endif; ?>
		</div>
		<div class="form-group col-md-6 mb-2">
			<label for="login" class="form-label">Login</label>
			<input type="text" class="form-control <?= !empty($errors['login']) ? 'is-invalid' : '' ?>" name="login" id="login" value="<?= !empty($form['login']) ? $form['login'] : '' ?>">
			<?php if (!empty($errors['login'])) : ?>
				<div class="invalid-feedback"><?= $errors['login'] ?></div>
			<?php endif; ?>
		</div>
		<div class="form-group col-md-6 mb-2">
			<label for="senha" class="form-label">Senha</label>
			<input type="password" class="form-control <?= !empty($errors['senha']) ? 'is-invalid' : '' ?>" name="senha" id="senha">
			<?php if (!empty($errors['senha'])) : ?>
				<div class="invalid-feedback"><?= $errors['senha'] ?></div>
			<?php endif; ?>
		</div>
		<div class="row mb-3">
			<div class="form-group col-md-3">
				<label for="" class="form-label">Status</label>
				<select class="form-select <?= !empty($errors['status']) ? 'is-invalid' : '' ?>" id="status" name="status"
				<?=$this->session->userdata("loggedUser")->admin ? "" : "disabled" ?>>
					<option value="1" <?php if (isset($form['status'])) {
											if ($form['status'] === "1") {
												echo "selected";
											}
										}
										?>>
						Ativo
					</option>
					<option value="0" <?php if (isset($form['status'])) {
											if ($form['status'] === "0") {
												echo "selected";
											}
										}
										?>>
						Inativo
					</option>

				</select>
				<?php if (!empty($errors['status'])) : ?>
					<div class="invalid-feedback"><?= $errors['status'] ?></div>
				<?php endif; ?>
			</div>
			<div class="form-group col-md-3">
				<label for="" class="form-label">Permissão</label>
				<select class="form-select <?= !empty($errors['admin']) ? 'is-invalid' : '' ?>" id="admin" name="admin" 
					<?=$this->session->userdata("loggedUser")->admin ? "" : "disabled" ?>>
					<option value="1" <?php if (isset($form['admin'])) {
											if ($form['admin'] === "1") {
												echo "selected";
											}
										}
										?>>
						Administrador
					</option>
					<option value="0" <?php if (isset($form['admin'])) {
											if ($form['admin'] === "0") {
												echo "selected";
											}
										}
										?>>
						Usuario comum
					</option>
				</select>
				<?php if (!empty($errors['admin'])) : ?>
					<div class="invalid-feedback"><?= $errors['admin'] ?></div>
				<?php endif; ?>
			</div>
		</div>
		<div class="form-group mb-4">
			<button type="submit" class="btn btn-success">Salvar</button>
			<a href="<?= base_url() ?>usuarios" class="btn btn-secondary">Voltar</a>
		</div>
	</form>
</main>