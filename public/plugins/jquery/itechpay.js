function formatterNombre($montant){
	$montant.on( "keyup", function( event ) {
			// When user select text in the document, also abort.
			var selection = window.getSelection().toString();
			if ( selection !== '' ) {
				return;
			}
			// When the arrow keys are pressed, abort.
			if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
				return;
			}
			var $this = $( this );
			// Get the value.
			var input = $this.val();
			var input = input.replace(/[\D\s\._\-]+/g, "");
			input = input ? parseInt( input, 10 ) : -1;
			$this.val( function() {
				return ( input === -1 ) ? "" : input.toLocaleString( "fr" );
			} );
	});
};
function confirmEnvoie(titre,montant) {
    $question = titre + montant+' ?';
    var result = confirm($question);
    if (result === true) {
        $('#envoi_form').submit();
    }

};
function confirmFacture(titre,montant) {
    $question = titre + montant+' ?';
    var result = confirm($question);
    if (result === true) {
        $('#facture_form');
    }
};
