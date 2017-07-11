<?php 
use \App\helpers\Input;
use \App\helpers\Alert;	
 ?>

<section>
	<div class="container">
	<?php echo Alert::getFlash(); ?>
		<div class="col-md-6 col-md-offset-3">
			<h2 style="margin-bottom: 20px" class="text-center">Login</h2>
			<form action="/login" method="post">
				<div class="form-group has-feedback <?php if(isset($errors['username'])) { echo 'has-error';} else if(Input::exists('username')) { echo 'has-success'; }?>">
					<label class="control-label" for="InputUsername">Username</label>
					<input type="text" name="username" class="form-control" id="InputUsername" placeholder="Username" value="<?=Input::escape(Input::get('username'));?>">
					<span class="control-label"><?=@$errors['username'];?></span>
				</div>
				<div class="form-group has-feedback <?php if(isset($errors['password'])) { echo 'has-error';}?>">
					<label class="control-label" for="InputPassword">Password</label>
					<input type="password" name="password" class="form-control" id="InputPassword" placeholder="Password">
					<span class="control-label"><?=@$errors['password'];?></span>
				</div>
				<div class="checkbox">
					<label><input name="remember" type="checkbox">Remember me</label>
				</div>
				<input type="submit" name="login" style="width: 100%" class="btn btn-primary" value="Login">
			</form>
		</div>
	</div>
</section>