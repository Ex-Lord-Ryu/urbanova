/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

/**
 * Custom JS file for application specific functionality
 */

// Add additional functionality for sidebar toggle
$(document).ready(function() {
  // Listen for sidebar toggle click
  $("[data-toggle='sidebar']").on('click', function() {
    // Check if window width is desktop size
    if($(window).outerWidth() > 1024) {
      // Toggle sidebar-mini class based on current state
      if($('body').hasClass('sidebar-mini')) {
        // If sidebar is minimized, ensure main content adjusts
        $('.main-content').css({
          'margin-left': '20px',
          'width': 'calc(100% - 20px)',
          'transition': 'all 0.5s ease'
        });
      } else {
        // If sidebar is expanded, ensure main content adjusts
        $('.main-content').css({
          'margin-left': '150px',
          'width': 'calc(100% - 150px)',
          'transition': 'all 0.3s ease'
        });
      }
    }
  });

  // Initialize on page load to ensure correct state
  if($('body').hasClass('sidebar-mini') && $(window).outerWidth() > 1024) {
    $('.main-content').css({
      'margin-left': '20px',
      'width': 'calc(100% - 20px)'
    });
  }

  // Handle window resize events
  $(window).resize(function() {
    if($(window).outerWidth() <= 1024) {
      // Mobile view
      $('.main-content').css({
        'margin-left': '0',
        'width': '100%'
      });
    } else {
      // Desktop view - check sidebar state
      if($('body').hasClass('sidebar-mini')) {
        $('.main-content').css({
          'margin-left': '20px',
          'width': 'calc(100% - 20px)'
        });
      } else {
        $('.main-content').css({
          'margin-left': '150px',
          'width': 'calc(100% - 150px)'
        });
      }
    }
  });
});
