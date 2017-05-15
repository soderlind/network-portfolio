
//IE fix for console.log
var console=console||{"log":function(){}};

/**
 * multisie porfolio ajax status change handling
 *
 *
 * Uses ajax_change_portfolio_status() in multisite-portfolio.php
 *
 * @author Per Soderlind <per@soderlind.no>
 */

jQuery(document).ready(function($){

	$(".ms-portfolio").css('cursor','pointer');

	// Ajax	vote
	$(document).on("click", ".ms-portfolio",  function( event ){

		event.preventDefault();

		var self = $(this);
		var orgBackgroundColor = self.css('background-color');
		var data = {
			action:     "change_portfolio_status",
			site_id:    self.data('siteid'),
            change_to:  self.data('changeto'),
            security:   network_portfolio.nonce
		};

		$.ajax({
			url:          network_portfolio.ajaxurl + '?now='  +escape(new Date().getTime().toString())
			, type:       'post'
			, dataType:   'json'
			, cache:      false
			, data:       data
			, beforeSend: function() {
				//self.css('box-shadow','none');
				self.animate({backgroundColor: '#0073aa'}, 'slow');
			}
			, complete: function(){
				self.animate({backgroundColor: orgBackgroundColor}, 'fast');
	        }
			, success: function(data) {
				if( 'success' == data.response ) {
                    self.text(data.text);
                    self.data('changeto',data.change_to);
				} else if( 'failed' == data.response ) {
					console.log(data);
				}
			}
            , error: function(e, x, settings, exception) {
                // Generic debugging

                var errorMessage;
                var statusErrorMap = {
                    '400' : "Server understood request but request content was invalid.",
                    '401' : "Unauthorized access.",
                    '403' : "Forbidden resource can't be accessed.",
                    '500' : "Internal Server Error",
                    '503' : "Service Unavailable"
                };
                if (x.status) {
                    errorMessage = statusErrorMap[x.status];
                    if (!errorMessage) {
                        errorMessage = "Unknown Error.";
                    } else if (exception == 'parsererror') {
                        errorMessage = "Error. Parsing JSON request failed.";
                    } else if (exception == 'timeout') {
                        errorMessage = "Request timed out.";
                    } else if (exception == 'abort') {
                        errorMessage = "Request was aborted by server.";
                    } else {
                        errorMessage = "Unknown Error.";
                    }
                    $this.parent().html(errorMessage);
                    console.log("Error message is: " + errorMessage);
                } else {
                    console.log("ERROR!!");
                    console.log(e);
            	}
            }
		});

	});
});
