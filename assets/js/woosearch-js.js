jQuery(document).ready(function($) {		


	// Jquery AJAX Search
    $('.woosearch-search-input').on('blur change paste keyup ', function (e) {
        var $that = $(this);
        var raw_data = $that.val(), // item container
            category = $("#searchtype").val(),
            number = $that.data("number"),
            keypress = $that.data("keypress");
            
            if(typeof category == 'undefined'){
                category = '';
            }

        if(raw_data.length >= keypress ){
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {action:'woosearch_search',raw_data: raw_data,category:category,number:number},
                beforeSend: function(){
                    //console.log('beforeSend');
                    
                    if ( !$('#woosearch-search .search-icon .moview-search-icons .fa-spinner').length ){
                        $('#woosearch-search .search-icon .moview-search-icons').addClass('spinner');
                        $('<i class="fa fa-spinner fa-spin"></i>').appendTo( "#woosearch-search .search-icon .moview-search-icons" ).fadeIn(100);
                       // $('#moview-search .search-icon .themeum-moviewsearch').remove();
                    }
                    
                },
                complete:function(){
                    //console.log('complete');
                   $('#woosearch-search .search-icon .moview-search-icons .fa-spinner ').remove();    
                    $('#woosearch-search .search-icon .moview-search-icons').removeClass('spinner');
                }
            })
            .done(function(data) {
                //console.log( data );
                if(e.type == 'blur') {
                   $( ".woosearch-results" ).html('');
                }else{
                    $( ".woosearch-results" ).html( data );
                }
            })
            .fail(function() {
                console.log("fail");
            });
        }
    });
    
    // Redirect On off
    $('#woosearch-search').on('submit', function (e) {
        if( $(this).data('redirect') == 1 ){
            e.preventDefault();    
        }
    });




});