<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
	<main class="container" style="margin-top: 60px;">
		<div class="row">
			<div class="login-form col-md-6 offset-md-3">
				<h2 class="text-center">Login</h2>
				<hr>
				<form method="post" action="<?=base_url('auth/login')?>">
					<div class="mt-2">
						<label for="login" class="form-label">Login:</label>
						<input type="text" class="form-control" name="login" id="login" placeholder="Login" >
					</div>
					<div class="mt-2">
						<label for="senha" class="form-label">Senha:</label>
						<input type="password" class="form-control" name="senha" id="senha" placeholder="Senha" >
					</div>
					<div class="mt-2 d-grid gap-2">
						<button type="submit" class="btn btn-primary">Login</button>
					</div>
					<div class="mt-2 mb-2">
						<a href="<?=base_url('recuperar')?>" class="text-decoration-none">Esqueci minha senha</a>
					</div>
				</form>
				<?= isset($_SESSION['danger']) ? $_SESSION['danger'] : ''?>
				<?= isset($errors) ? $errors : '' ?>
			</div>
		</div>
	</main>