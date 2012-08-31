<?php
global $app;
$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/nav.php' );
?>
<article id="results" class="active">
	<?php $app->render( 'Snippets/results_container.php' ); ?>
</article>

<?php
$app->render( 'Snippets/about.php' );
$app->render( 'Snippets/footer.php' );
?>