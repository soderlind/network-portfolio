(function($) {
    wp.customize('networkportfolio[width]', function(value) {
        value.bind(function(to) {
            $('#networkportfolio').css('width', to + 'px');
        });
    });
    wp.customize('networkportfolio[height]', function(value) {
        value.bind(function(to) {
            $('#networkportfolio').css('height', to + 'px');
        });
    });
	wp.customize('networkportfolio[border_radius]', function(value) {
        value.bind(function(to) {
            $('#networkportfolio').css('border-radius', to + 'px');
        });
    });
	wp.customize('networkportfolio[border_color]', function(value) {
        value.bind(function(to) {
			console.log( to );
            $('#networkportfolio').css('boxShadowColor', to ); // using css hook, see boxshadow.js
        });
    });
	wp.customize('networkportfolio[border_width]', function(value) {
        value.bind(function(to) {
			$('#networkportfolio').css('boxShadowSpread', to + 'px'); // using css hook, see boxshadow.js
        });
    });
}(jQuery));
