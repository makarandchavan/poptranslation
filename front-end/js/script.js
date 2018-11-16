$(function(){
	"use strict";

	var openNav = $('.open'),
		closeNav = $('.closebtn'),
		navPanel = $('#mySidenav'),
		parters = $('.logo-slider'),
		dropzone = $('#dropzone');

	// $(dropzone).dropzone({ 
	// 	url: "/file/post" 
	// });

	$('[data-toggle="popover"]').popover();
	
	$(openNav).on('click', function(e){
		e.preventDefault();
		$(navPanel).css('left', '0px');
	});

	$(closeNav).on('click', function(e){
		e.preventDefault();
		$(navPanel).css('left', '-250px');
	});

	if (parters.length) {
		parters.slick({
			arrows: false,
			slidesToShow: 3,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 1000,
  			responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 3,
			        slidesToScroll: 3
			      }
			    },
			    {
			      breakpoint: 600,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll: 2
			      }
			    },
			    {
			      breakpoint: 480,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1
			      }
			    }
			  ]
		});
	}

});

// File Upload with Dropzone

var previewNode = document.querySelector("#template");
	previewNode.id = "";

var previewTemplate = previewNode.parentNode.innerHTML;
	previewNode.parentNode.removeChild(previewNode);

var myDropzone = new Dropzone(document.body, {
  url: "/target-url", // Set the url
  thumbnailWidth: 40,
  thumbnailHeight: 40,
  parallelUploads: 20,
  previewTemplate: previewTemplate,
  //autoQueue: false, // Make sure the files aren't queued until manually added
  previewsContainer: "#previews", // Define the container to display the previews
  clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
});


