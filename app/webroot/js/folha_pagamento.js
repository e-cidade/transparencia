(function($) {

  $(function($) {
    var sBaseUrl = $('#base').attr('href');

    $(document).ajaxSend(function() {
      $('#ajax-loader').show()
    })

    $(document).ajaxStop(function() {
      $('#ajax-loader').hide()
    })

    $('#FiltroMatricula').live('keyup', function() {
      $(this).val( $(this).val().replace(/[^0-9]/g, '') );
    })

    $('#FiltroIndexForm').live('submit', function() {
      var lPass = true;

      $('#FiltroMatricula').trigger('keyup');

      // Verifica se os campos obrigatórios foram preenchidos
      $('select.required').each(function() {

        if (this.value == '') {

          alert("Campo " + $(this).parent().find('label').text() + " é de preenchimento obrigatório.");

          lPass = false;
          return false;
        }
      })

      return lPass;
    })
  
    $('#FiltroInstituicao').live('change', function(e) {
      fCallback = function() {
        this.value = $(this).find('option:last').attr('value');
      }

      // Limpa o campo anos
      __preencheOptions($('#FiltroAno'), {});

      if (this.value == '') {
        return;
      }

      // Busca os Meses
      $.getJSON(sBaseUrl + '/folha_pagamentos/getAnos/' + this.value, function(data) {
        __preencheOptions( $('#FiltroAno'), data, fCallback);
      })
    })

    $('#FiltroAno').live('change', function(e, fCallback) {
      var iInstituicao = $('#FiltroInstituicao').val()

      if (fCallback) {
        fCallback.call(this)
      }

      fCallbackMes = function() {
        this.value = $(this).find('option:last').attr('value');
      }

      // Limpa o campo meses
      __preencheOptions($('#FiltroMes'), {});

      if (this.value == '') {
        return;
      }

      // Busca os Meses
      $.getJSON(sBaseUrl + '/folha_pagamentos/getMeses/' + this.value + '/' + iInstituicao, function(data) {
        __preencheOptions( $('#FiltroMes'), data, fCallbackMes );
      })
    })

    $('#FiltroMes').live('change', function(e, fCallback) {
      var iAno         = $('#FiltroAno').val(),
          iInstituicao = $('#FiltroInstituicao').val();

      if (fCallback) {
        fCallback.call(this);
      }

      // Limpa os campos que dependem da instituição
      __preencheOptions($('#FiltroCargo'), {});
      __preencheOptions($('#FiltroLotacao'), {});
      __preencheOptions($('#FiltroVinculo'), {});

      if (iAno == '' || iInstituicao == '' || this.value == '') {
        return;
      }

      // Busca os cargos
      $.getJSON(sBaseUrl + '/folha_pagamentos/getCargos/' + iAno + '/' + this.value + '/' + iInstituicao, function(data) {
        __preencheOptions( $('#FiltroCargo'), data)
      })

      // Busca os Lotes
      $.getJSON(sBaseUrl + '/folha_pagamentos/getLotacoes/' + iAno + '/' + this.value + '/' + iInstituicao, function(data) {
        __preencheOptions( $('#FiltroLotacao'), data)
      })

      // Busca os Vinculos
      $.getJSON(sBaseUrl + '/folha_pagamentos/getVinculos/' + iAno + '/' + this.value + '/' + iInstituicao, function(data) {
        __preencheOptions( $('#FiltroVinculo'), data)
      })
    })
    

    /**
     * Preenche o select passado com as options
     * 
     * @param Object oSelectDest - Objeto a ser preenchido
     * @param Object oOptions - Options
     * @param Function fCallback - Callback
     */
    function __preencheOptions(oSelectDest, oOptions, fCallback) {
      oSelectDest.find('option:not(:first)').remove()

      $.each(oOptions, function(iValor, sLabel) {
        oSelectDest.append('<option value="' + iValor + '">' + sLabel + '</option>').trigger('change');
      })
      
      // Used to reflow DOM - (ie7)
      oSelectDest.css('width', oSelectDest.css('width'))
      oSelectDest.trigger('change', [fCallback])
    }

    $(document).ready(function() {
      iInstituicao = $('#FiltroInstituicao')
      
      if (iInstituicao.find('option').length == 2 && !$('#FiltroMes').val()) {
        iInstituicao.val(iInstituicao.find('option:not(:first)').val()).trigger('change')
      }

      if (!$('#FiltroMes').val()) {
        $('#FiltroMes').trigger('change')
      }
    })
  });

})(jQuery);