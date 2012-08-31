<?php foreach ( $survey->getAllPage() as $page ) { ?>
<section>
	<h1>
		<?= $page->getTitle() ?>
	</h1>
	
	<?php foreach( $page->getAllTopic() as $topic ) {
		if ( ! is_null( $survey_result ) && ! $survey_result->isMain() && $survey_result->getTopic()->getId() == $topic->getId() )
			continue;
	?>
	<h3>
		<?= $topic->getTitle() ?>
		<small><?= $topic->getCount() ?> Answers</small>
	</h3>
	
	<div class="qgroup">
		<?php foreach ( $topic->getAllOption() as $option ) { ?>
		<div class="label">
			<?= $option->getTitle() ?>
			<small><?= $option->getCount() ?></small>
			<div class="value" style="width: <?= $option->getPercent() ?>%">
				<?= (int)( $option->getPercent() ) ?>%
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	
</section>
<?php } ?>