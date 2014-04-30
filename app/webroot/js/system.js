alert('xx')

;(function($){

	$(function($){
		$('#navbar li.sub-menu').bind('click', function(e){
			var $self = $(this),
				target = $(e.target).parent()

			if ($self[0] === target[0]) {
				$self.toggleClass('active').find('ul:first').slideToggle()
			}

		}).find('>a').unbind('click')

		$('a[disabled=disabled]').live('click', function() {
			return false
		})

		var flash = $('#flashMessage')
		setTimeout(function(){
			flash.fadeOut('slow')
		}, 10000)

		$('form div.input.error').each(function() {
			$(this).addClass('controls').find('.error-message').addClass('help-inline')
		}).closest('form').addClass('control-group')

		$('a[href*="#"]').live('click', function() {
			var hash = this.href.split('#').pop()

			if (hash) $(document).scrollTop($('#'+hash).position().top)

			return false

		})

	})

})(jQuery)