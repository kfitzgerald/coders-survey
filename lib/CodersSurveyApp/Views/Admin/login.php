<?php
global $app;

$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/admin_header.php' );
?>
	<article class="active">
		<form method="post" action="/admin">
			<h1>
				Please Login before Admin
			</h1>
			<div class="qgroup">
				<label>
					<input type="text" name="data[username]" value="" placeholder="Username">
				</label>
				<label>
					<input type="password" name="data[password]" value="" placeholder="Password">
				</label>
			</div>
			<div class="actions">
				<input class="btn btn-large btn-inverse" type="submit" value="Enter">
			</div>
		</form>
	</article>
<?php
$app->render( 'Snippets/footer.php' );
?>