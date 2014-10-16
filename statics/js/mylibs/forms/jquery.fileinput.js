/**
 * --------------------------------------------------------------------
 * jQuery customfileinput plugin
 * Author: Scott Jehl, scott@filamentgroup.com
 * Copyright (c) 2009 Filament Group 
 * licensed under MIT (filamentgroup.com/examples/mit-license.txt)
 * --------------------------------------------------------------------
 */
 
// EDITED
 
$.fn.fileInput = function(){
	return $(this).each(function(){
	//apply events and styles for file input element
	var fileInput = $(this)
		.addClass('customfile-input-hidden') //add class for CSS
		// .mouseover(function(){ upload.addClass('customfile-hover'); })
		.mouseout(function(){ upload.removeClass('customfile-hover'); })
		.hover(function(){ upload.addClass('customfile-hover'); }, function(){ upload.removeClass('customfile-hover'); })
		.focus(function(){
			upload.addClass('customfile-focus'); 
			fileInput.data('val', fileInput.val());
		})
		.blur(function(){ 
			upload.removeClass('customfile-focus');
			$(this).trigger('checkChange');
		 })
		 .bind('disable',function(){
		 	fileInput.attr('disabled',true);
			upload.addClass('customfile-disabled');
		})
		.bind('enable',function(){
			fileInput.removeAttr('disabled');
			upload.removeClass('customfile-disabled');
		})
		.bind('checkChange', function(){
			if(fileInput.val() && fileInput.val() != fileInput.data('val')){
				fileInput.trigger('change');
			}
		})
		.bind('change',function(){
			//get file name
			var fileName = $(this).val().split(/\\/).pop();
			//get file extension
			var fileExt = 'customfile-ext-' + fileName.split('.').pop().toLowerCase();
			//update the feedback
			uploadFeedback
				.text(fileName) //set feedback text to filename
				.removeClass(uploadFeedback.data('fileExt') || '') //remove any existing file extension class
				.addClass(fileExt) //add file extension class
				.data('fileExt', fileExt) //store file extension for class removal on next change
				.addClass('customfile-feedback-populated'); //add class to show populated state
			//change text of button	
			uploadButton.text($.fn.fileInput.lang.change);
			uploadFeedback.ellipsis(true);
			upload.removeClass('customfile-focus');
			// Validation
			if ($.validator) {
				var v = upload.parents('form').data('validator');
				if (v) {
					v.element(fileInput);
					// TODO: test this
				}
			}
		})
		.click(function(){ //for IE and Opera, make sure change fires after choosing a file, using an async callback
			fileInput.data('val', fileInput.val());
			setTimeout(function(){
				fileInput.trigger('checkChange');
			},100);
			upload.removeClass('customfile-focus');
		});
		
	//create custom control container
	var upload = $('<div class="customfile"></div>');
	var uploadInput = $('<div class="customfile-input"></div>').appendTo(upload);
	//create custom control button
	var uploadButton = $('<span class="customfile-button" aria-hidden="true">' + $.fn.fileInput.lang.browse + '</span>').appendTo(uploadInput);
	//create custom control feedback
	var uploadFeedback = $('<span class="customfile-feedback" aria-hidden="true">' + $.fn.fileInput.lang.nofile + '</span>').appendTo(uploadInput);
	
	//match disabled state
	if(fileInput.is('[disabled]')){
		fileInput.trigger('disable');
	}
	
	if (fileInput.val() != '') {
		fileInput.data('val', fileInput.val());
		fileInput.trigger('change');
	}
		
	
	//on mousemove, keep file input under the cursor to steal click
	upload
		.mousemove(function(e){
			fileInput.css({
				'left': e.pageX - upload.offset().left - fileInput.width() + 20, //position right side 20px right of cursor X)
				'margin': 0
			});	
		})
		.insertBefore(fileInput); //insert after the input
	
	fileInput.prependTo(upload);
		
	});
};

$.fn.fileInput.lang = {
	change: 'Change',
	browse: 'Browse',
	nofile: 'No file selected...'
}