
<nav>
	<!-- <h3><?= $_SERVER[ 'REQUEST_URI' ] ?></h3> -->
	<!-- <pre><?php print_r( $_SERVER ) ?></pre> -->
	<ul>
	<?php if ( preg_match( '#^/admin(?:\?.+)?$#', $_SERVER[ 'REQUEST_URI' ] ) ) { ?>
		<li class="active">
			<a href="#overview" data-toggle="tab">Edit Survey</a>
		</li>
		<li>
			<a href="#generate" data-toggle="tab">Generate Results</a>
		</li>
		<li>
			<a href="#results" data-toggle="tab">See Results</a>
		</li>
		<li>
			<a href="#help" data-toggle="tab">Help</a>
		</li>
		<script type="text/javascript">
		$( function() {
			setTimeout( function() {
				if ( ! document.location.hash ) return;
				$( 'nav li' ).removeClass( 'active' );
				//console.debug( "ASD", $( 'nav a[href='+ document.location.hash+ ']' ) );
				$( 'nav a[href='+ document.location.hash+ ']' ).tab( 'show' );
			}, 100 );
		});
		</script>
	<?php } else { ?>
		<li>
			<a href="#overview">
				Edit Survey
			</a>
		</li>
		<li>
			<a href="#generate">
				Generate Results
			</a>
		</li>
		<li>
			<a href="#results">
				See Results
			</a>
		</li>
		<li>
			<a href="#help">
				Help
			</a>
		</li>
	<?php } ?>
	</ul>
	<menu>
		<a href="/pregzilla/polls/admin/change-password">
			Change Password
		</a>
		<a href="/pregzilla/polls/logout">
			Log Out ›
		</a>
	</menu>
</nav>
