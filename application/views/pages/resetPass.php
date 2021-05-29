<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
	<main class="container" style="margin-top: 60px;">
		<div class="row">
			<div class="login-form col-md-6 offset-md-3">
				<h2 class="text-center">Recuperar Senha</h2>
				<hr>
				<form method="post">
					<div class="mt-2">
						<label for="login" class="form-label">Email:</label>
						<input type="text" class="form-control" name="email" id="email" placeholder="Email">
						<div class="form-text">Será enviado um email com um link para troca de senha para o email digitado.</div>
					</div>
					<div class="mt-2">
						<button type="submit" class="btn btn-primary">Enviar email</button>
						<a href="<?=base_url('auth/login')?>" class="btn btn-secondary">Voltar</a>
					</div>
				</form>
				<?= isset($errors) ? $errors : '' ?>
			</div>
		</div>
	</main>