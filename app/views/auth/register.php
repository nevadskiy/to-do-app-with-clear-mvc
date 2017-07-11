<?php 
use \App\helpers\Input;
?>

<section>
	<div class="container">
		<div class="col-md-6 col-md-offset-3">
			<h2 style="margin-bottom: 20px" class="text-center">Register</h2>
			<form action="/register" method="post">
				<div class="form-group has-feedback <?php
				if(isset($errors['username'])) { 
					echo 'has-error';
				} else if(Input::exists('username')) { 
					echo 'has-success';
				}?>">
				<label class="control-label" for="InputUsername">Username</label>
				<input type="text" name="username" class="form-control" id="InputUsername" placeholder="Username" value="<?=Input::escape(Input::get('username'));?>">
				<span class="control-label"><?=@$errors['username'];?></span>
			</div>
			<div class="form-group has-feedback <?php if(isset($errors['password'])) { echo 'has-error';}?>">
				<label class="control-label" for="InputPassword">Password</label>
				<input type="password" name="password" class="form-control" id="InputPassword" placeholder="Password">
				<span class="control-label"><?=@$errors['password'];?></span>
			</div>
			<div class="form-group has-feedback <?php if(isset($errors['repassword'])) { echo 'has-error';}?>">
				<label class="control-label" for="InputRepassword">Repeat password</label>
				<input type="password" name="repassword" class="form-control" id="InputRepassword" placeholder="Password">
				<span class="control-label"><?=@$errors['repassword'];?></span>
			</div>
			<input type="submit" name="register" style="width: 100%" class="btn btn-primary" value="Register">
		</form>
	</div>
</div>
</section>