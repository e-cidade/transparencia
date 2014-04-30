;(function($){

	$(function($){

		var base = $('#base').attr('href')

		$('.content-type').bind('click', function(e) {
			var $self = $(this),
				divPar = 'div',
				compar = $self.attr('checked')

			$(divPar+'.static').toggle(compar)
			$(divPar+'.non-static').toggle(!compar)

		}).triggerHandler('click')

		$('.redactor').redactor({
			fixed: true,
			css: base + '/css/style.css',
			iframe: true,
			lang: 'ptbr',
			convertDivs: false,
			callback: function(obj) {
				obj.$el.bind('change', function(){
					adjustSize.call(obj);
				}).triggerHandler('change')
			}
		})

		$('.method').bind('click', function(e) {
			var $self = $(this),
				divPar = 'div.input:has',
				compar = $self.attr('checked')

			$(divPar+'(.upload)').toggle(compar)
			$(divPar+'(.non-upload)').toggle(!compar)

		}).triggerHandler('click')

	})

	function adjustSize() {
		var size = this.$content.contents().find('body')[0].offsetHeight
		this.$content.height((size-250) + 'px');
	}

})(jQuery)