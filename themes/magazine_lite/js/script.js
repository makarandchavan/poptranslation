var Drupal = Drupal || {};

(function ($, Drupal, Bootstrap) {
	"use strict";

	var openNav = $('.open-nav'),
		closeNav = $('.closebtn'),
		navPanel = $('#mySidenav');

	
	$(openNav).on('click', function(e){
		e.preventDefault();
		$(navPanel).css('left', '0px');
	});

	$(closeNav).on('click', function(e){
		e.preventDefault();
		$(navPanel).css('left', '-250px');
	});

})(window.jQuery, window.Drupal, window.Drupal.bootstrap);
