;(function($){

  $(function($) {

    var base = $('#base').attr('href');

    $('#servidores').jqGrid({
      url: base + '/folha_pagamentos/pesquisar',
      datatype: 'JSON',
      mtype: 'POST',
      postData: $.JSON.decode($('#parametro').val()),

      colNames:[
        'Matrícula',
        'Nome',
        'Cargo',
        'Lotação'
      ],

      colModel: [
        {name: 'matricula', index:'Servidor.id', align: 'center', width: '50px'},
        {name: 'nome', index: 'Servidor.nome'},
        {name: 'cargo', index: 'cargo'},
        {name: 'lotacao', index: 'lotacao'}
      ],

      sortname: 'Servidor.nome',
      autowidth: true,
      altRows: true,
      rowNum: 15,
      viewrecords: true,
      pager: '#pager',
      height: 'auto',
      width: 'auto',

      loadComplete: function(result) {
        if (result.total == 0) {
          $(".ui-jqgrid-bdiv").html('<h4 class="no-records">Nenhum Registro Encontrado.</h4>')
        }          
      },

      onCellSelect: function(rowid) {
        window.location.href = base + '/folha_pagamentos/view/' + rowid
      }
    })

  })

})(jQuery)