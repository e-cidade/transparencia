<?php header('Content-Type: text/html; charset=utf-8'); ?>
<div id="breadcrumb">
  <?php
  
    $aOptions = array('update' =>'main_content',
                      'url'    => array('action' => 'loadView','credores'),
                      'data'   => $this->JavaScript->object($aParametros));
      
    $this->Crumb->addPage($aParametros['iNivel'],'Credores',null,$aOptions) ;
    
    echo $this->Crumb->getHtml();
  ?>
</div>
<br>
<h2 align="center">Despesas por Credor<?php echo " / ".$aParametros['iExercicio'];?></h2>
<br>
<? echo $this->Transparencia->showHistoricoDespesa($aParametros['aHistorico']); ?>
<br>
<div>
<table id="list"></table>
<div id="pager"></div>
<br>
<?php echo $this->Transparencia->showDataAtualizacao($aParametros['dtDataImportacao']);?>
</div>
<script type="text/javascript">

  $(document).ready(
  
    function(){  
    
      var oParametros = $.JSON.decode('<?php echo $this->JavaScript->object($aParametros);?>');  
      
	    jQuery("#list").jqGrid({
		    url: '<?php echo $this->base?>/despesas/getCredores',
			  datatype: "json",
			  mtype: "POST",
        postData:{aParametros:$.JSON.encode(oParametros)}, 
			    colNames:['CPF/CNPJ','Credor','Empenhado','Anulado','Liquidado','Pago'],
			    colModel:[
			      {name:'cpfcnpj'         ,index:'cpfcnpj'        ,width:'120px', align:'left' , formatter:formatarCpfCnpj},
			      {name:'nome'            ,index:'nome'           ,width:'300px', align:'left'},
			      {name:'valor_empenhado' ,index:'valor_empenhado',width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_anulado'   ,index:'valor_anulado'  ,width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_liquidado' ,index:'valor_liquidado',width:'100px', align:'right', search:false, formatter:'currency'},
			      {name:'valor_pago'      ,index:'valor_pago'     ,width:'100px', align:'right', search:false, formatter:'currency'}
			    ],
			    rowNum:10,
			    rowList:[10,20,30],
			    pager: '#pager',
	        autowidth: true,
          gridview: true,
          shrinkToFit:false,
			    sortname: 'nome',
			    viewrecords: true,
			    sortorder: "asc",
			    height: '240px',
			    altRows:true,
			    onCellSelect: function (id) {
			         
              var oDados = jQuery("#list").jqGrid('getRowData',id);
              
              oDados.descricao = formatarCpfCnpj(oDados.cpfcnpj,null,null)+' - '+oDados.nome;
              oParametros.aHistorico.push(oDados) 			         
			        oParametros.iCredor = id;
              oParametros.iNivel++;
			     
			        $.ajax( { async:true, 
			                  type:'post', 
			                  dataType: 'json',
                        data: oParametros,  
			                  beforeSend:function(request) { 
			                               $('#ajax-loader').show();
			                             }, 
			                  complete:  function(request, json) { 
			                               $('#main_content').html(request.responseText); 
			                               $('#ajax-loader').hide();
			                             }, 
			                  url:'<?php echo $this->base ?>/despesas/loadLink/'+oParametros.iIdLink
			                 }) 
			    }
		  });
		 
		  jQuery("#list").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		 
			
			function formatarCpfCnpj(sCellValue, sOptions, oRowObject) {
				          		  
			  var sCpfCnpj = new String(sCellValue);
							 
			  var vrc = new String(sCpfCnpj);
			      vrc = vrc.replace(".", "");
			      vrc = vrc.replace(".", "");
				    vrc = vrc.replace("/", "");
				    vrc = vrc.replace("-", "");
							                
				var tamString = vrc.length; 
				var nCpfCnpj  = new Number(vrc);
							   
				if (!isNaN(nCpfCnpj)) {
					   
				  if (tamString == 11 ){       
					      
				    var vr = new String(sCpfCnpj);
					      vr = vr.replace(".", "");
				 	      vr = vr.replace(".", "");
					      vr = vr.replace("-", "");
			      
			      var iTam = vr.length;
			
            if (iTam > 3 && iTam < 7)
               sCpfCnpj = vr.substr(0, 3) + '.' + 
                          vr.substr(3, iTam);
            if (iTam >= 7 && iTam <10)
               sCpfCnpj = vr.substr(0,3) + '.' + 
                          vr.substr(3,3) + '.' + 
                          vr.substr(6,iTam-6);
            if (iTam >= 10 && iTam < 12)
               sCpfCnpj = vr.substr(0,3) + '.' + 
                          vr.substr(3,3) + '.' + 
                          vr.substr(6,3) + '-' + 
                          vr.substr(9,iTam-9);
			                       
	        } else if (tamString > 11){
	                        
            var vr = new String(sCpfCnpj);
                vr = vr.replace(".", "");
                vr = vr.replace(".", "");
	              vr = vr.replace("/", "");
	              vr = vr.replace("-", "");
			
	          var iTam = vr.length;
	              sCpfCnpj = vr.substr(0,2) + '.' + 
			                     vr.substr(2,3) + '.' + 
			                     vr.substr(5,3) + '/' + 
			                     vr.substr(8,4)+ '-' + 
			                     vr.substr(12,iTam-12);
			                       
		      }   
 	      }
			      
			   return sCpfCnpj;
		  }
    }
  );

</script>
