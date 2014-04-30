<?php

function db_log($sLog="", $sArquivo="", $iTipo=0) {
  $aDataHora  = getdate();
  $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s","\n", $aDataHora["mday"], 
                                                                     $aDataHora["mon"], 
                                                                     $aDataHora["year"], 
                                                                     $aDataHora["hours"], 
                                                                     $aDataHora["minutes"], 
                                                                     $aDataHora["seconds"],
                                                                     $sLog);

  if ($iTipo == 0 || $iTipo == 1) {
    echo $sOutputLog;
  }

  if ($iTipo == 0 || $iTipo == 2) {
    
    if (!empty($sArquivo)) {
      
      $fd = fopen($sArquivo, "a+");
      
      if ($fd) {
        
        fwrite($fd, $sOutputLog);
        fclose($fd);
      }
    }
  }

  return $aDataHora;

}

?>