jQuery( function( $ ) {

    $( '.sitecare-report-score-accordion' ).on( 'click', '.sitecare-report-score-accordion-trigger', function() {
        var isCollapsed = ( 'true' === $( this ).attr( 'aria-expanded' ) );
        var $accordionItem = $(this).closest('.sitecare-report-score-accordion-item');

        if ( isCollapsed ) {
            $( this ).attr( 'aria-expanded', 'false' );
            $( '#' + $( this ).attr( 'aria-controls' ) ).attr( 'hidden', true );
            $accordionItem.removeClass('active');
        } else {
            $( this ).attr( 'aria-expanded', 'true' );
            $( '#' + $( this ).attr( 'aria-controls' ) ).attr( 'hidden', false );
            $accordionItem.addClass('active');
        }
    } );

} );
