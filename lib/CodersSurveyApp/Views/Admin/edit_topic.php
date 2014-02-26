<?php

global $app;
$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/admin_header.php' );
$app->render( 'Snippets/admin_nav.php' );
?>
	<article id="overview" class="active">
		<h1>
			<?php if ( $survey_topic[ 'id' ] == 'new' ) { $topic = null; ?>
			Create new topic
			<?php } else { $topic = $survey->indexedTopic( $survey_topic[ 'parent_id' ], $survey_topic[ 'id' ] ); ?>
			Edit topic
			<?php } ?>
		</h1>
		<p>
			<a href="/pregzilla/polls/admin/page/<?= $survey_topic[ 'parent_id' ] ?>">‹ Back</a>
		</p>
		<form method="post" action="/pregzilla/polls/admin/topic/<?= $survey_topic[ 'id' ] ?>">
			<div class="qgroup">
				<h3>
					Title
				</h3>
				<label>
					<input type="text" name="data[topic][title]" value="<?= $survey_topic[ 'title' ] ?>" placeholder="Topic Title">
				</label>
				<h3>
					Settings
				</h3>
				<?/*
				<div class="label">
					Type: 
					<select name="data[topic][data][type]">
						<option value="radio"
							<?php if ( $survey_topic[ 'data' ][ 'type' ] == 'radio' ) echo ' selected="selected"'?>
						>
							Radio
						</option>
						<option value="checkbox"
							<?php if ( $survey_topic[ 'data' ][ 'type' ] == 'checkbox' ) echo ' selected="selected"'?>
						>
							Checkbox
						</option>
					</select>
				</div>
				*/?>
				<label>
					<input name="data[topic][data][type]" type="radio" value="radio" <?php if ( $survey_topic[ 'data' ][ 'type' ] == 'radio' ) echo ' checked'?>>
					Radios
				</label>
				<label>
					<input name="data[topic][data][type]" type="radio" value="checkbox" <?php if ( $survey_topic[ 'data' ][ 'type' ] == 'checkbox' ) echo ' checked'?>>
					Checkboxes
				</label>
				<label>
					<input type="checkbox" name="data[topic][data][mandatory]" value="1" <?php if ( $survey_topic[ 'data' ][ 'mandatory' ] ) echo ' checked="checked"'?>>
					Mandatory
				</label>
				<!-- <label>
					<input type="checkbox" name="data[topic][data][other]" value="1" <?php if ( $survey_topic[ 'data' ][ 'other' ] ) echo ' checked="checked"'?>>
					"Other" field
				</label> -->
			</div>
			<h3>
				Options
			</h3>
			<div class="qgroup x-sortable">
				<?php
				$position = 1;
				if ( $topic ) {
					foreach ( $topic->getAllOption() as $option ) {
				?>
				<label>
					<span class="x-sort-handler"></span>
					<input type="text" name="data[option][<?= $option->getId() ?>][title]" value="<?= $option->getTitle() ?>" placeholder="Option Title">
					<input type="hidden" class="x-position-value" name="data[option][<?= $option->getId() ?>][position]" value="<?= $position++ ?>">
				</label>
				<?php
					}
				}
				?>
				<?php foreach ( range( 1, 20 ) as $idx ) { ?>
				<label class="x-new-option">
					<span class="x-sort-handler"></span>
					<input type="text" name="data[option][new:<?= $idx ?>][title]" value="" placeholder="New Option">
					<input type="hidden" class="x-position-value" name="data[option][new:<?= $idx ?>][position]" value="<?= $position++ ?>">
				</label>
				<?php } ?>
			</div>
			<script type="text/javascript" src="/pregzilla/polls/js/jquery.ui-admin.js"></script>
			<script type="text/javascript">
			$( function() {
				$( $( 'label.x-new-option' ).hide().get(0) ).show();
				$( 'label input[type=text]' ).keyup( function() {
					var next = $( this ).parent().next();
					if ( ! next.length ) return;
					var empty = $( this ).val().length == 0;
					if ( empty && ! next.find( 'input' ).val().length && ! next.is( ':hidden' ) )
						next.hide();
					else if ( ! empty && next.is( ':hidden' ) )
						next.show();
				} );
			} );
			</script>
			<?php if ( $survey_topic[ 'id' ] == 'new' ) { ?>
			<input type="hidden" name="data[topic][parent_id]" value="<?= $survey_topic[ 'parent_id' ] ?>">
			<?php } ?>
			<div class="actions">
				<input class="btn btn-large btn-inverse" type="submit" value="Save">
			</div>
		</form>
	</article>
<?php
$app->render( 'Snippets/footer.php' );
?>
