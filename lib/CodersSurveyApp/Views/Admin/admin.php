<?php
global $app;
$app->render( 'Snippets/header.php' );
$app->render( 'Snippets/admin_header.php' );
$app->render( 'Snippets/admin_nav.php' );
?>
	<article id="overview" class="active">
		<form method="post" action="/admin/survey/togglestate">

			<?/* 

			// TITLE not needed when only one survey
			<h3>
				<?= $survey->getTitle() ?>
			</h3>
			*/?>
			<p>
				Survey is currently
				<?php if ( $survey->isClosed() ) { ?>
					 closed. <button class="btn" type="submit">Reopen Survey</button>
				<?php } else { ?>
					 open. <button class="btn" type="submit">Close Survey</button>
				<?php } ?>
			</p>
		</form>
		
		
		<form method="post" action="/admin/survey/save">
			<h1>
				Pages
			</h1>
			<div class="x-sortable qgroup">
				<?php
				$position = 1;
				foreach ( $survey->getAllPage() as $page ) { ?>
				<div class="label">
					<span class="x-sort-handler"></span>
					<input type="hidden" class="x-position-value" name="data[page][<?= $page->getId() ?>][position]" value="<?= $position++ ?>">
					<?= $page->getTitle() ?>
					<div class="btn-group">
						<a class="btn btn-small btn-success" href="/admin/page/<?= $page->getid() ?>">edit</a>
						<a class="btn btn-small btn-danger" href="/admin/page/delete/<?= $page->getid() ?>">delete</a>
					</div>
				</div>
				<?php } ?>
			</div>
			<p>
				<a class="btn btn-small" href="/admin/page/new">+ Create new Page</a>
			</p>
			<div class="actions">
				<input class="btn btn-large btn-inverse" type="submit" value="Save Pages">
			</div>
		</form>
	</article>
	<article id="generate">
		<h1>
			Select Group Topics
		</h1>
		<div id="x-generate-pre">
			<form method="post" action="/admin/survey/generate">
				<?php foreach ( $survey->getAllPage() as $page ) { ?>
				<section>
					<h3>
						<?= $page->getTitle() ?>
					</h3>
					
					<div class="qgroup">
					<?php foreach( $page->getAllTopic() as $topic ) { ?>
						<label>
							<input type="checkbox" name="<?= $topic->getReqName( true ) ?>" value="yes">
							<?= $topic->getTitle() ?>
						</label>
					<?php } ?>
					</div>
					
				</section>
				<?php } ?>
				<div class="actions">
					<input class="btn btn-large btn-inverse" type="submit" value="Generate Results now">
				</div>
			</form>
		</div>
		
		<div style="display: none" id="x-generate-while">
			<h2>
				Building
			</h2>
		</div>
		
		<div style="display: none"  id="x-generate-post">
			<h2>
				Done
			</h2>
			<p>
				Results generated. Redirecting ..
			</p>
		</div>
		
		<script type="text/javascript">
		$( function() {
			$( '#x-generate-pre form' ).submit( function() {
				$( '#x-generate-pre' ).slideUp();
				$( '#x-generate-while' ).slideDown();
				var data = {};
				$( this ).find( 'input[type=checkbox]:checked' ).each( function() {
					data[ $( this ).attr( 'name' ) ] = 'yes';
				} );
				$.post( $( this ).attr( 'action' ), data, function( json ) {
					console.debug( json );
					$( '#x-generate-while' ).slideUp();
					$( '#x-generate-post' ).slideDown();
					setTimeout( function() {
						document.location.href = '/admin?t='+ ( new Date() ).getTime()+ '#results';
					}, 1000 );
				}, 'json' );
				return false;
			} );
		})
		</script>
	</article>
	
	<article id="results">
		<?php $app->render( 'Snippets/results_container.php' ); ?>
	</article>
	<article id="help">
		<?php $app->render( 'Snippets/help.php' ); ?>
	</article>

<?php
$app->render( 'Snippets/footer.php' );
?>