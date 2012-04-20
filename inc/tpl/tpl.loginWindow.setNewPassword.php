<div class="w50" id="loginWindow">
	<form action="login.php?chpw=<?php echo $_GET['chpw']; ?>" method="post">

<?php $errors = AccountHandler::tryToSetNewPassword(); ?>
<?php $user   = AccountHandler::getUsernameForChangePasswordHash(); ?>

		<fieldset>
			<legend>Neues Passwort setzen</legend>
		<?php if ($user): ?>
			<input type="hidden" name="chpw_hash" value="<?php echo $_GET['chpw']; ?>" />
			<input type="hidden" name="chpw_username" value="<?php echo $user; ?>" />
			<div class="w100">
				<label for="chpw_name">Benutzer</label>
				<input id="chpw_name" name="chpw_name" class="middleSize" type="text" value="<?php echo $user; ?>" disabled="disabled" />
			</div>
			<div class="w100">
				<label for="new_pw">Neues Passwort</label>
				<input id="new_pw" name="new_pw" class="middleSize" type="password" />
			</div>
			<div class="w100 clear">
				<label for="new_pw_again">Wiederholung</label>
				<input id="new_pw_again" name="new_pw_again" class="middleSize" type="password" />
			</div>
		<?php else: ?>
			<?php echo Html::error('Der Link ist nicht mehr g&uuml;ltig'); ?>
		<?php endif; ?>
		<?php if (is_array($errors) && !empty($errors)) foreach ($errors as $error) echo Html::error($error); ?>
		</fieldset>

		<div class="c">
			<input type="submit" value="&Auml;ndern" name="submit" />
		</div>
	</form>
</div>