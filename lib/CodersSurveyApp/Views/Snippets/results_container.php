<div id="filter">
	<select id="x-choose-result">
		<?php
		$last_topic = 0;
		foreach ( $survey->getResults() as $result ) {
			if ( $result->isMain() ) {
		?>
		<option value="<?= $result->getId() ?>">
			Filter Off
		</option>
			<?php
			} else {
				if ( ! $result->getOption() ) continue;
				$topic_id = $result->getTopic()->getId();
				if ( $last_topic && $topic_id != $last_topic ) {
			?>
			</optgroup>
			<?php
				}
				if ( ! $last_topic || $topic_id != $last_topic ) {
					$last_topic = $topic_id;
			?>
			<optgroup label="<?= $result->getTopic()->getTitle() ?>">
			<?php
				}
			?>
				<option value="<?= $result->getId() ?>">
					<?= $result->getOption()->getTitle() ?>
				</option>
			<?php
			}
			?>
		<?php
		}
		if ( $last_topic ) {
		?>
			</optgroup>
		<?php
		}
		?>
	</select>
</div>

<div id="x-result-out"></div>

<script type="text/javascript">
$( function() {
	$( "#x-choose-result" ).change( function() {
		if ( ! $( this ).val() )
			return $( '#x-result-out' ).empty();
		$( '#x-result-out' ).empty().append( 'Loading..' );
		$.get( '/pregzilla/polls/load-result/'+ $( this ).val(), function( html ) {
			$( '#x-result-out' ).empty().append( html );
		} );
	} ).trigger( 'change' );
} );
</script>
