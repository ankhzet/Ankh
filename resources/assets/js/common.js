$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

	$('.log .stack').click(function () {
		var expanded = 'expanded', has = $(this).hasClass(expanded);

		$('.log .stack').animate({height: '50px', 'overflow': 'hidden'}, {duration:100})
		.removeClass(expanded);

		if (has)
			return;

		$(this).animate({height: '400px', 'overflow': 'auto'}, {duration:100}).addClass(expanded);
	});
});

(function($) {

	var laravel = {
		initialize: function() {
			this.methodLinks = $('a[data-method]');

			this.registerEvents();
		},

		registerEvents: function() {
			this.methodLinks.click(this.handleMethod);
		},

		handleMethod: function(e) {
			var link = $(this);
			var httpMethod = link.data('method').toUpperCase();
			var form;

			// If the data-method attribute is not PUT or DELETE,
			// then we don't know what to do. Just ignore.
			if ( $.inArray(httpMethod, ['PUT', 'DELETE']) === - 1 ) {
				return;
			}

			// Allow user to optionally provide data-no-confirm
			if ( !!link.data('no-confirm') || !laravel.verifyConfirm(link) )
				return false;

			form = laravel.createForm(link);
			form.submit();

			e.preventDefault();
		},

		verifyConfirm: function(link) {
			return confirm(link.data('confirm') || "Are you sure?");
		},

		createForm: function(link) {
			var form =
			$('<form>', {
				'method': 'POST',
				'action': link.attr('href')
			});

			var hiddenInput =
			$('<input>', {
				'name': '_method',
				'type': 'hidden',
				'value': link.data('method').toUpperCase()
			});
			var token =
			$('<input>', {
				'name': '_token',
				'type': 'hidden',
				'value': $('meta[name="_token"]').attr('content')
			});

			return form.append(token, hiddenInput)
								 .appendTo('body');
		}
	};

	laravel.initialize();

})(jQuery);
