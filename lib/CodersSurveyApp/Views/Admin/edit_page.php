<?php
global $app;
$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/admin_header.php' );
$app->render( 'Snippets/admin_nav.php' );
?>
	<article id="overview" class="active">
		<h1>
			<?php if ( $survey_page[ 'id' ] == 'new' ) { ?>
			Create new page
			<?php } else { $page = $survey->indexedPage( $survey_page[ 'id' ] ); ?>
			Edit Page
			<?php } ?>
		</h1>
		<p>
			<a href="/admin">‹ Back</a>
		</p>
		<form method="post" action="/admin/page/<?= $survey_page[ 'id' ] ?>">
			<h3>
				Page Title
			</h3>
			<div class="qgroup">
				<label>
					<input type="text" name="data[page][title]" value="<?= $survey_page[ 'title' ] ?>" placeholder="Page Title">
				</label>
			</div>
			<?php if ( $survey_page[ 'id' ] != 'new' ) { ?>
			<h3>
				Topics
			</h3>
				<?php if ( $page ) { $position = 1; ?>
			<div class="x-sortable qgroup">
					<?php foreach ( $page->getAllTopic() as $topic ) { ?>
				<div class="label">
					<span class="x-sort-handler"></span>
					<input type="hidden" class="x-position-value" name="data[topic][<?= $topic->getId() ?>][position]" value="<?= $position++ ?>">
					<?= $topic->getTitle() ?>
					<div class="btn-group">
						<a class="btn btn-small btn-success" href="/admin/topic/<?= $topic->getId() ?>">edit</a>
						<a class="btn btn-small btn-danger" href="/admin/topic/delete/<?= $topic->getId() ?>">delete</a>
					</div>
				</div>
					<?php } ?>
			</div>
				<?php } ?>
			<p>
				<a class="btn btn-small" href="/admin/topic/create/<?= $page->getId() ?>">+ Create new Topic</a>
			</p>
			<?php } else { ?>
			<input type="hidden" name="data[page][parent_id]" value="<?= $survey_page[ 'parent_id' ] ?>">
			<?php } ?>
			<div class="actions">
				<input class="btn btn-large btn-inverse" type="submit" value="Save Page">
			</div>
		</form>
	</article>
<?php
$app->render( 'Snippets/footer.php' );
?>