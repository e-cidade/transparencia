<?php
class Glossario extends AppModel {


  
 
  function getTipos() {
   
     $sSqlTipos = " select * 
                      from glossarios_tipos 
                  order by id";

     return $this->query($sSqlTipos);
   
  }
 
  
  
  function getItensGlossarioByTipo($iTipoGlossario='') {
   
     $sSqlItens = " select * 
                      from glossarios
                     where glossario_tipo_id = {$iTipoGlossario}";

     return $this->query($sSqlItens);
   
  } 
 
}
?>