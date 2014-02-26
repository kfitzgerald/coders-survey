<?php
global $app;
$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/admin_header.php' );
$app->render( 'Snippets/admin_nav.php' );
?>
	<article class="active">
		<form method="post" action="/pregzilla/polls/admin/change-password">
			<h3>
				Username
			</h3>
			<div class="qgroup">
				<label>
					<input type="text" name="data[username_new]" value="<?= $username ?>" placeholder="New Username">
				</label>
			</div>
			<h3>
				Change Password
			</h3>
			<div class="qgroup">
				<label>
					<input type="password" name="data[password_new]" value="" placeholder="New Password">
				</label>
				<label>
					<input type="password" name="data[password_repeat]" value="" placeholder="Password repeat">
				</label>
			</div>
			<div class="actions">
				<input class="btn" type="submit" value="Save new password">
			</div>
		</form>
	</article>
<?php
$app->render( 'Snippets/footer.php' );
?>
