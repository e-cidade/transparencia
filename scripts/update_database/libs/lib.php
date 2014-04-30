<?php

function db_log($sLog="", $sArquivo="", $iTipo=0) {

  $aDataHora  = getdate();
  $sPrefixo   = sprintf("[%02d/%02d/%04d %02d:%02d:%02d] ", $aDataHora["mday"], $aDataHora["mon"], $aDataHora["year"], $aDataHora["hours"], $aDataHora["minutes"], $aDataHora["seconds"]);
  $sLog       = str_replace("\n", "\n{$sPrefixo}", $sLog);
  $sOutputLog = $sPrefixo . $sLog . "\n"; 

  if ($iTipo == 0 || $iTipo == 1) {
    echo toUTF8($sOutputLog);
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

function toUTF8($sText) {
  return mb_convert_encoding($sText, "UTF-8", mb_detect_encoding($sText, "UTF-8, ISO-8859-1, ISO-8859-15", true));
}
