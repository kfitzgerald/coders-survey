<?php
global $app;
$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/nav.php' );
?>
<style type="text/css">
#topic-241, #topic-title-241 { display: none;}
</style>
<script type="text/javascript">
$( function() {
	if ( document.location.search && document.location.search.match( /\?([^?&=]+)/ ) ) {
		var ref = RegExp.$1.toLowerCase().replace( /[^a-z0-9]/, '' );
		$( '#topic-241 label' ).each( function() {
			var name = $.trim( $( this ).text() ).toLowerCase().replace( /[^a-z0-9]/, '' );
			if ( ref == name )
				$( this ).find( 'input' ).attr( 'checked', true );
		} );
	}
} );
</script>
<article id="questions" class="active">
	<form action="/pregzilla/polls/complete" method="post">
		<?php while ( $page = $survey->nextPage() ) { ?>
		<section>
			<h1>
				<?php echo $page->getTitle() ?>
			</h1>
			<?php while( $topic = $page->nextTopic() ) { ?>
			<h3 id="topic-title-<?= $topic->getId() ?>">
				<?php echo $topic->getTitle() ?>
				<?php if ( $topic->hasError() ) { ?>
				Error: Missing Value
				<?php } ?>
			</h3>
			<div class="qgroup" id="topic-<?= $topic->getId() ?>">
				<?php while ( $option = $topic->nextOption() ) { ?>
				<label for="opt-<?php echo $option->getId() ?>" onclick="">
					<input
						type="<?php echo $topic->getType() ?>"
						name="<?php echo $option->getReqName() ?>"
						id="opt-<?php echo $option->getId() ?>"
						value="<?php echo $option->getId() ?>"
						<?php if ( $option->isSelected() ) echo ' checked="checked"'; ?>
					>
					<?php echo $option->getTitle() ?>
				</label>
				<?php } ?>
				<?php if ( $topic->hasOther() ) { ?>
				<label for="opt-<?php echo $topic->getId() ?>" onclick="">
					<input
						type="<?php echo $topic->getType() ?>"
						name="<?php echo $topic->getReqName() ?>"
						id="opt-<?php echo $topic->getId() ?>"
						value="__other__"
						<?php if ( $topic->getOtherValue() ) echo ' checked="checked"'; ?>
					>
					<input
						type="text"
						placeholder="other…"
						name="<?php echo $topic->getReqOtherName() ?>"
						value="<?php echo $topic->getOtherValue() ?>"
					>
				</label>
				<?php } ?>
			</div>
			<?php } ?>
		</section>
		<?php } ?>
		<div class="actions">
			<input class="btn btn-large btn-inverse" type="submit" value="Submit it !#">
		</div>
	</form>
</article>

<?php
$app->render( 'Snippets/about.php' );
$app->render( 'Snippets/footer.php' );
?>
