jQuery(function($){
	$(document).ready(function(){
    var $j = jQuery.noConflict();
    $('.edit-slug-box').hide();
    $('#wp-content-editor-tools').css("display", "none");
    $('#kwf').on('submit', function(e) {
        e.preventDefault();
			   $.ajax({
				  url: kw.ajax_url,
                  type:"POST",
                  
                  data: $("#kwf").serialize(),  
                    success: function(response){ 
                    alert ('Data submitted sucessfully!')},
                    error: function (xhr, thrownError) {
                        alert(xhr.status);
                        alert(thrownError); }
            })
        })
    })
})