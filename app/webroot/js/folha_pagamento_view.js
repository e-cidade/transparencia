;(function($) {
  $(document).ready(function() {

    $('#sTipoFolhaPagamento').change(function(){

      var sTipo = $(this).val();
      $('.tipo-folha').hide();
      $('.tipo-folha.' + sTipo).show();
    });

    $('#sTipoFolhaPagamento').trigger('change');

  })

})(jQuery);