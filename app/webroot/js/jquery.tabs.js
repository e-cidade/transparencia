;(function($){

  /**
   * ul[data-tab] > li.active > a[data-target||href]
   * ul[data-tab] ~ div[tab-pane=true].active
   */

  $.fn.tabs = function() {

    var $self = this;

    $self.find('>li >a').bind('click', function(e) {
      e.preventDefault();

      var $anchor = $(this)
          target  = $anchor.attr('data-target') || $anchor.attr('href')

      $anchor.parent().siblings().removeClass('active')
      $anchor.parent().addClass('active')

      $(target).addClass('active').show().siblings('[tab-pane]').removeClass('active').hide()

      return false;
    }).end().find('>li.active >a').click()

  }

  $(function($){
    $('ul[data-tab]').tabs()
  })

})(jQuery)