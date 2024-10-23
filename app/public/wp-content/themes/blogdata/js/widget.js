jQuery( document ).ready( function( $ ) {
    // console.log('js enqueue');
    // Function to check if all elements matching the selector exist
function areElementsExists(selector) {
    const elements = document.querySelectorAll(selector);
    return elements.length > 0;
  }
  
  // Function to execute when the elements are found
  function elementsFoundCallback() {
    console.log("Elements found!");
    jQuery( '.colorpicker' ).wpColorPicker();
    jQuery('.iris-square-handle').on( "click", function() {
        console.log('yes CIRCLE');
        jQuery(this).parents('.wp-picker-active').find('.colorpicker ').trigger( "change" );
    } );
    jQuery('.ui-slider-handlele').on( "click", function() {
        console.log('yes SLIDER');
        jQuery(this).parents('.wp-picker-active').find('.colorpicker ').trigger( "change" );
    } );
    jQuery('.iris-square-inner.iris-square-vert').on( "click", function() {
        console.log('yes INNER');
        jQuery(this).parents('.wp-picker-active').find('.colorpicker ').trigger( "change" );
    } );
    // Do something with the elements here
  }
  
  // Target elements selector
  const targetSelector = ".wp-block-legacy-widget__edit-preview";
  
  // Check if the elements already exist
  if (areElementsExists(targetSelector)) {
    elementsFoundCallback();
  } else {
    // Set up a MutationObserver to watch for changes
    const observer = new MutationObserver(() => {
      if (areElementsExists(targetSelector)) {
        
        elementsFoundCallback();
      }
    });
  
    // Start observing changes in the DOM
    observer.observe(document, { childList: true, subtree: true });
  }
  
    
      
    //   jQuery('.iris-square-handle').on( "keyup", function($) {
    //     console.log('yes t');
    //     jQuery(this).parents('.wp-picker-active').children('.wp-picker-input-wrap > .colorpicker ').trigger( "change" );
    //   } );
} );
