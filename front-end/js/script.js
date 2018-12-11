$(function(){
	"use strict";

	var openNav = $('.open'),
		closeNav = $('.closebtn'),
		navPanel = $('#mySidenav'),
		parters = $('.logo-slider'),
		bannerSlider= $('.banners'),
		dropzone = $('#dropzone'),
		topicSlider = $('.topics');

	// Quote page validation -
	$('#quote').validate({
		rules: {
			email: 'required',
			entreprise: 'required'
		}
	});
	// 

	$('.form-control').on("blur", function(){
		if ($('#quote').valid()) {
			$('#submit-quote').removeAttr('disabled');
		} else {
			$('#submit-quote').attr("disabled", "disabled");
		}
	 });

	$('[data-toggle="popover"]').popover();
	
	$(openNav).on('click', function(e){
		e.preventDefault();
		$(navPanel).css('left', '0px');
	});

	$(closeNav).on('click', function(e){
		e.preventDefault();
		$(navPanel).css('left', '-250px');
	});

	if ($('#wizard').length) {
		$('#wizard').steps({
	        headerTag: 'h3',
	        bodyTag: 'section',
	        transitionEffect: 'slideLeft',
	        onStepChanged: function (event, currentIndex, priorIndex){
		        if (currentIndex === 5) {
		        	var previewNode = document.querySelector("#template");
						previewNode.id = "";

					var previewTemplate = previewNode.parentNode.innerHTML;
						previewNode.parentNode.removeChild(previewNode);

					var myDropzone = new Dropzone(document.body, {
					  url: "/target-url", // Set the url
					  thumbnailWidth: 40,
					  thumbnailHeight: 40,
					  parallelUploads: 20,
					  maxFilesize: 20, // MB
					  previewTemplate: previewTemplate,
					  //autoQueue: false, // Make sure the files aren't queued until manually added
					  previewsContainer: "#previews" // Define the container to display the previews
					  // clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
					});
		        }

		        if (currentIndex === 6) {
		        	$('.actions').hide();
		        }
		    }
	    });
	}
	
	if (topicSlider.length) {
		topicSlider.slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			infinite: false,
			nextArrow: '<button type="button" class="slick-next ti-arrow-circle-right"></button>',
			prevArrow: '<button type="button" class="slick-prev ti-arrow-circle-left"></button>'
		});
	}

	if (bannerSlider.length) {
		bannerSlider.slick({
			arrows: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 3000
		});
	}

	if (parters.length) {
		parters.slick({
			arrows: false,
			slidesToShow: 2,
			slidesToScroll: 1,
			autoplay: true,
  			autoplaySpeed: 1000,
  			responsive: [
			    {
			      breakpoint: 1024,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll: 1
			      }
			    },
			    {
			      breakpoint: 600,
			      settings: {
			        slidesToShow: 2,
			        slidesToScroll: 1
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

var dropWrapper = document.getElementsByClassName("dzone");

if (dropWrapper.length) {
	
	var previewNode = document.querySelector("#template");
		previewNode.id = "";

	var previewTemplate = previewNode.parentNode.innerHTML;
		previewNode.parentNode.removeChild(previewNode);

	var myDropzone = new Dropzone(document.body, {
	  url: "/target-url", // Set the url
	  thumbnailWidth: 40,
	  thumbnailHeight: 40,
	  parallelUploads: 20,
	  maxFilesize: 20, // MB
	  previewTemplate: previewTemplate,
	  //autoQueue: false, // Make sure the files aren't queued until manually added
	  previewsContainer: "#previews", // Define the container to display the previews
	  clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
	});

	// File size -
	var totalSizeLimit = 20*1024*1024; // 20MB
	myDropzone.on("uploadprogress", function(file, progress, bytesSent) {
		var alreadyUploadedTotalSize = getTotalPreviousUploadedFilesSize();

	    if((alreadyUploadedTotalSize + bytesSent) > totalSizeLimit) {
	      this.disable();
	    }
	});

	function getTotalPreviousUploadedFilesSize(){
	   var totalSize = 0;
	   myDropzone.getFilesWithStatus(Dropzone.SUCCESS).forEach(function(file){
	      totalSize = totalSize + file.size;
	   });
	   console.log(totalSize);
	   return totalSize;
	}
	// 
}

// if (cvUpload.length) {
	
// 	var previewNode = document.querySelector("#template");
// 		previewNode.id = "";

// 	var previewTemplate = previewNode.parentNode.innerHTML;
// 		previewNode.parentNode.removeChild(previewNode);

// 	var myDropzone = new Dropzone(document.body, {
// 	  url: "/target-url", // Set the url
// 	  thumbnailWidth: 40,
// 	  thumbnailHeight: 40,
// 	  parallelUploads: 20,
// 	  maxFilesize: 20, // MB
// 	  previewTemplate: previewTemplate,
// 	  //autoQueue: false, // Make sure the files aren't queued until manually added
// 	  previewsContainer: "#previews" // Define the container to display the previews
// 	  // clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
// 	});

// }



// Slide In Panel
	
	var panelTriggers = document.getElementsByClassName('js-cd-panel-trigger');
	if( panelTriggers.length > 0 ) {
		for(var i = 0; i < panelTriggers.length; i++) {
			(function(i){
				var panelClass = 'js-cd-panel-'+panelTriggers[i].getAttribute('data-panel'),
					panel = document.getElementsByClassName(panelClass)[0];
				// open panel when clicking on trigger btn
				panelTriggers[i].addEventListener('click', function(event){
					event.preventDefault();
					addClass(panel, 'cd-panel--is-visible');
				});
				//close panel when clicking on 'x' or outside the panel
				panel.addEventListener('click', function(event){
					if( hasClass(event.target, 'js-cd-close') || hasClass(event.target, panelClass)) {
						event.preventDefault();
						removeClass(panel, 'cd-panel--is-visible');
					}
				});
			})(i);
		}
	}
	
	//class manipulations - needed if classList is not supported
	//https://jaketrent.com/post/addremove-classes-raw-javascript/
	function hasClass(el, className) {
	  	if (el.classList) return el.classList.contains(className);
	  	else return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
	}
	function addClass(el, className) {
	 	if (el.classList) el.classList.add(className);
	 	else if (!hasClass(el, className)) el.className += " " + className;
	}
	function removeClass(el, className) {
	  	if (el.classList) el.classList.remove(className);
	  	else if (hasClass(el, className)) {
	    	var reg = new RegExp('(\\s|^)' + className + '(\\s|$)');
	    	el.className=el.className.replace(reg, ' ');
	  	}
	}

