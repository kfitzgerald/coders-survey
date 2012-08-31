<?php
global $app;
$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/nav.php' );
?>
<style type="text/css">
#topic-241, #topic-title-241 { display: none;}
</style>
<article id="questions" class="active">
	<h3>
		Thank You so much for wasting your time with us!
	</h3>
	<p>
		Don't forget to come back when the results will be published. In the meanwhile, please spread the word. Tell your mates about this. More data more better.
	</p>

	<?php while ( $page = $survey->nextPage() ) { ?>
	<section>
		<h1>
			<?php echo $page->getTitle() ?>
		</h1>
		<?php while( $topic = $page->nextTopic() ) { ?>
		<h3 id="topic-title-<?= $topic->getId() ?>">
			<?php echo $topic->getTitle() ?>
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
					disabled
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
					disabled
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
</article>
<?php
$app->render( 'Snippets/about.php' );
$app->render( 'Snippets/footer.php' );
?>