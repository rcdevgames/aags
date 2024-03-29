<?php echo partial('shared/title', array('title' => 'users.password_reset.title', 'place' => 'users.password_reset.title')) ?>
<p><?php echo t('users.password_reset.text2') ?></p>
<hr />
<form method="post" role="form" id="reset-password-finish-form" action="<?php echo make_url('users#reset_password/' . $key) ?>" class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2"><?php echo t('users.password_reset.password') ?></label>
		<div class="col-sm-10">
			<input type="password" name="password" class="form-control" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2"><?php echo t('users.password_reset.password_confirmation') ?></label>
		<div class="col-sm-10">
			<input type="password" name="password_confirmation" class="form-control" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<input type="submit" value="<?php echo t('users.password_reset.reset_finish') ?>" class="btn btn-primary" />
		</div>
	</div>
</form>