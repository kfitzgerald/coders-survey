<script type="text/javascript" src="/js/jquery.ui-admin.js"></script>
<script type="text/javascript">
$( function() {
	$( ".x-sortable" ).sortable( {
		handle: '.x-sort-handler',
		update: function() {
			var pos = 1;
			$( '.x-position-value' ).each( function() {
				var input = $( this ).parent().find( 'input[type=text]' );
				if ( input.length > 0 && input.val().length == 0 )
					return;
				$( this ).val( pos++ );
			} );
		}
	});
	$( '.qgroup .delete' ).click( function() {
		if ( confirm( "Are you sure?\n\nAll collected results will be deleted as well!" ) )
			return true;
		return false;
	})
} );
</script>

<?php if ( !@empty( $_SESSION[ 'slim.flash' ] ) ) { ?>
	<?php foreach ( $_SESSION[ 'slim.flash' ] as $class => $msg ) { ?>
<div class="alert alert-<?= $class ?>">
	<?= $msg ?>
</div>
	<?php } ?>
<script type="text/javascript">
$( function() {
	setTimeout( function() {
		$( '.alert' ).slideUp();
	}, 8000 );
} );
</script>
<?php } ?>