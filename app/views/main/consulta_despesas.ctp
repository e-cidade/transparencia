<div id="breadcrumb"><?php
$this->Html->addCrumb('Consulta Dados', '/main/consulta_dados') ;
$this->Html->addCrumb('Despesas'      , '/main/consulta_despesas') ;
 
echo $html->getCrumbs('  > ', 'Principal');
?></div>
<br>
<div><label>Exercíco :</label> <select>
	<option>2009</option>
	<option>2010</option>
</select>
<table id="list"></table>
<div id="pager"></div>
</div>
<script type="text/javascript">
  
  $(document).ready(
  
    function(){

	    jQuery("#list").jqGrid({
		    url:'teste',
			  datatype: "json",
			    colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
			    colModel:[
			      {name:'id'     ,index:'id',                width:55},
			      {name:'invdate',index:'invdate',           width:90,  align:"center",formatter:"date"},
			      {name:'name'   ,index:'name asc, invdate', width:100},
			      {name:'amount' ,index:'amount',            width:80,  align:"right"},
			      {name:'tax'    ,index:'tax',               width:80,  align:"right"},    
			      {name:'total'  ,index:'total',             width:80,  align:"right"},   
			      {name:'note'   ,index:'note',              width:150, sortable:false}   
			    ],
			    rowNum:10,
			    rowList:[10,20,30],
	        autowidth: true,
			    pager: '#pager',
			    sortname: 'id',
			    viewrecords: true,
			    sortorder: "desc",
			    altRows: true,
			    height: '250px',
			    altRows:true,
			    ondblClickRow: function (id) { alert(id);}
		  });

		  jQuery("#list").jqGrid('navGrid','#pager',{edit:false,add:false,del:false});
		
    }
  );
 
</script>
