$(document).ready(function(){
	
	
	//$('#search_filters').hide();
	$('.bigtext').bigtext({minfontsize: 24, maxfontsize: 50 });
	
	sliderInit.call();
	
	/* --------------------------------------------------------- */
	
    var total_found = $('meta#total_found').attr('value');
    var first_load = $('meta#first_load').attr('value');
    $('body').data('total_found', total_found);
    $('body').data('record_counter', first_load);	
    
	/* --------------------------------------------------------- */
	
	$('a.toggle').click(function(){
		var target = $(this).attr('rel');
		
		$('#' + target).slideToggle();
		
		$(this).toggleClass('opened');
		
		if(target = 'search_filters'){
				sliderInit.call();

		}
		
	});
	

});


/* ========================================== */
/* Window Resize */
/* ========================================== */

$(window).resize(function(){
	sliderInit.call();
});


/* ========================================== */
/* Window Scroll */
/* ========================================== */


$(window).scroll(function()
{
    if($(window).scrollTop() == $(document).height() - $(window).height())
    {
    	
    	var pre_load_count = Number($('body').data('record_counter'));
    	var total_found = Number($('body').data('total_found'));
    	
    	var orderby =  $('meta#orderby').attr('value');
    	var order =  $('meta#order').attr('value');
    	var query_vars =  $('meta#query_vars').attr('value');

    	
    	if ( pre_load_count < total_found ){
	    	
	    	$('body').data('record_counter', Number(pre_load_count) + 36);

	    	
	        $('div#loadmoreajaxloader').show();
	        $.ajax({
	        url: "loadmore.php",
	        data: {
	        	'number':'36',
	        	'offset': pre_load_count,
	        	'orderby': orderby,
	        	'order': order,
	        	'query_vars': query_vars
	        },
	        success: function(html)
	        {
	            if(html)
	            {	
	                $("#albums").append(html);
	                $('div#loadmoreajaxloader').hide();
	            }else
	            {
	                $('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
	            }
	        }
	        });
        
        }
    }
});



/* ========================================== */
/* JS Reveal */
/* ========================================== */

function revealAlbum(id, itc_root_url){
	
		
	$.ajax({
		type: 'POST',
		url: itc_root_url + '/inc/album-modal.php',
		data: {
			"itunes_key": id
			},
			success: function(data){
				
				$("#album-modal .modal-content").html(data);
				var theTextColor = $('#album-modal .modal-content .desc .year').css('color');
				$('#album-modal .close-reveal-modal').css('color', theTextColor);
				//$(window).resize();
				$('#album-modal .modal-content .bigtext').bigtext({ minfontsize: 32, maxfontsize: 60  });
				$("#album-modal").reveal();
			}
	});

	return false;
	
	
	
}


/* ========================================== */
/* slider init */
/* ========================================== */

var sliderInit = function(){

	var stepSetting = false;
	// the step setting is based on whether or not a checkbox is checked.		
	if ($("#valueInput input[type='checkbox']").is(':checked')){
		stepSetting=20;
	}
	
	var theYearNow = new Date().getFullYear();
	
	var startYear = $('input#display_options-start_year').attr('value');
	var endYear = $('input#display_options-end_year').attr('value');
	
	if (startYear == ''){ startYear = 1900; }
	if (endYear == ''){ endYear = theYearNow; }
	
	// clear the $("#noUiSlider") div, then initialize.
	$("#noUiSlider").empty().noUiSlider( 'init', {
		step: stepSetting,
		scale: [1900,theYearNow],
		start: [startYear, endYear],
		change:
			function(){
				// the noUiSlider( 'value' ) method returns an array.
				var values = $(this).noUiSlider( 'value' );
				
				$(this).find('.noUi-lowerHandle .infoBox').text(values[0]);
				$('input#display_options-start_year').attr('value', values[0]);
				
				$(this).find('.noUi-upperHandle .infoBox').text(values[1]);
				$('input#display_options-end_year').attr('value', values[1]);
			},
		end:
			function(type){
				// 'type' can be 'click', 'slide' or 'move'
			
			}
	// the number displays aren't noUiSliders default, so we need to add elements for those.
	// index is a counter that counts all objects .each() has passed.
	}).find('.noUi-handle div').each(function(index){
		// appending the element, calling the 'value' function each time to get the value for the knob.
		$(this).append('<span class="infoBox">'+$(this).parent().parent().noUiSlider( 'value' )[index]+'</span>');

	});
};

