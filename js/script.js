/* Author: Frank (esher) Laemmer */

$(document).ready(function() {
	// toggle active class on lables, when radio / checkbox changes
	$( '#questions input[type=radio]' ).change( function () {
		$( this ).parents( '.qgroup' ).find( '.active' ).removeClass( 'active' );
		if ( $( this ).is( ':checked' ) )
			$( this ).parents( 'label' ).addClass('active');
	});
	$( '#questions input[type=checkbox]' ).change( function () {
		if ( $( this ).is( ':checked' ) )
			$( this ).parents( 'label' ).addClass('active');
		else
			$( this ).parents( 'label' ).removeClass('active');
	});

	// 
	$(function() {
		var hash = window.location.hash;
		hash && $('ul.nav a[href="' + hash + '"]').tab('show');
	});
});





/*  Twitter */

!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

/*  Facebook */

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "https://connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// /* google+  */

(function() {
	var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	po.src = 'https://apis.google.com/js/plusone.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();