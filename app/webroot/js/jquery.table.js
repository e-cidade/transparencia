;(function($){

  /**
   * div.table-container[.fixed-header] > table.header
   * div.table-container[.fixed-header] > div.body-container > table.body
   */

  $.fn.table = function() {

    this.each(function(){
      var $this = $(this);
      
      if ($this.hasClass('fixed-header')) {
        applyFixedHeader.call($this)
      }
    })


  }

  function applyFixedHeader() {
    var _this = this;

    _this.find('.body-container').css({
      'overflow-y': 'auto',
      'max-height' : '500px'
    }).find('.body').width('100%')

    
    _this.find('.header th').each(function(k,v) {

      var $td = _this.find('.body tr:first td:eq('+k+')'),
          widthTh = $(this).width(),
          widthTd = $td.width()

      if (widthTh > widthTd) {
        $td.width(widthTh)
        $(this).width(widthTh)
      } else {
        $(this).width(widthTd)
        $td.width(widthTd)
      }

    })

    _this.find('table.header').width( _this.find('.body-container table').width() )
  }

  $(function() {
    $('.table-container').table()
  })

})(jQuery)