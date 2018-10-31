$(function(){
	"use strict";

	var openNav = $('.open'),
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

});