;(function ($, window, undefined) {
  'use strict';

  var $doc = $(document),
      Modernizr = window.Modernizr;

  $(document).ready(function() {
    $.fn.foundationAlerts           ? $doc.foundationAlerts() : null;
    $.fn.foundationButtons          ? $doc.foundationButtons() : null;
    $.fn.foundationAccordion        ? $doc.foundationAccordion() : null;
    $.fn.foundationNavigation       ? $doc.foundationNavigation() : null;
    $.fn.foundationTopBar           ? $doc.foundationTopBar() : null;
    $.fn.foundationCustomForms      ? $doc.foundationCustomForms() : null;
    $.fn.foundationMediaQueryViewer ? $doc.foundationMediaQueryViewer() : null;
    $.fn.foundationTabs             ? $doc.foundationTabs({callback : $.foundation.customForms.appendCustomMarkup}) : null;
    $.fn.foundationTooltips         ? $doc.foundationTooltips() : null;
    $.fn.foundationMagellan         ? $doc.foundationMagellan() : null;
    $.fn.foundationClearing         ? $doc.foundationClearing() : null;

    $.fn.placeholder                ? $('input, textarea').placeholder() : null;
  });

  // UNCOMMENT THE LINE YOU WANT BELOW IF YOU WANT IE8 SUPPORT AND ARE USING .block-grids
  // $('.block-grid.two-up>li:nth-child(2n+1)').css({clear: 'both'});
  // $('.block-grid.three-up>li:nth-child(3n+1)').css({clear: 'both'});
  // $('.block-grid.four-up>li:nth-child(4n+1)').css({clear: 'both'});
  // $('.block-grid.five-up>li:nth-child(5n+1)').css({clear: 'both'});

  // Hide address bar on mobile devices (except if #hash present, so we don't mess up deep linking).
  if (Modernizr.touch && !window.location.hash) {
    $(window).load(function () {
      setTimeout(function () {
        window.scrollTo(0, 1);
      }, 0);
    });
  }

// sample code for orbit
/*  
	$(window).load(function() {
		$('#orbit').orbit({
			animation: 'fade',					// fade, horizontal-slide, vertical-slide, horizontal-push
			animationSpeed: 800,				// how fast animtions are
			timer: false,						// true or false to have the timer
			resetTimerOnClick: false,			// true resets the timer instead of pausing slideshow progress
			advanceSpeed: 16000,				// if timer is enabled, time between transitions 
			pauseOnHover: true,					// if you hover pauses the slider
			startClockOnMouseOut: true,			// if clock should start on MouseOut
			startClockOnMouseOutAfter: 16000,	// how long after MouseOut should the timer start again
			directionalNav: false,				// manual advancing directional navs
			captions: false,					// do you want captions?
			bullets: true,						// true or false to activate the bullet navigation
			bulletThumbs: false,				// thumbnails for the bullets
			bulletThumbLocation: '',			// location from this file where thumbs will be
			fluid: '16x6'
		});
	});   
*/

})(jQuery, this);
