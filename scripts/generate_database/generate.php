<?php

require_once 'libs/lib.php';
require_once 'libs/DB.class.php';
require_once 'libs/DatabaseVersioning.class.php';

DB::beginTransaction();

$sFileLog = "log/gera_database".date("Ymd_His").".log";

try {
  
  $sSchema = DB::getInstance()->getSchema();
  
  if ( trim($sSchema) != '' ) {

    
    $sSqlConsultaSchema  = " select exists ( select 1                                                        "; 
    $sSqlConsultaSchema .= "                   from information_schema.schemata                              "; 
    $sSqlConsultaSchema .= "                  where information_schema.schemata.schema_name = '{$sSchema}' ) ";
    
    $aDadosSchema        = DB::query($sSqlConsultaSchema);

    if ( is_bool($aDadosSchema) ) {
      throw new Exception("Falha ao consultar schemas SQL : {$sSqlConsultaSchema}");
    }                             
    
    if ( !$aDadosSchema->fetchColumn() ) {
    
      if ( is_bool(DB::exec("CREATE SCHEMA {$sSchema} ")) ) {
        throw new Exception("Falha ao criar schema {$sSchema} !");
      }
  
      if ( is_bool(DB::exec("ALTER DATABASE ".DB::getInstance()->getDataBase()." SET search_path TO {$sSchema} ")) ) {
        throw new Exception("Falha ao alterar schema atual para {$sSchema} !");
      }
      
    }    

    if ( is_bool(DB::exec("SET search_path TO {$sSchema} ")) ) {
      throw new Exception("Falha ao definir schema atual para {$sSchema} !");
    }
    
  }

  $oDataBaseVersioning = new DatabaseVersioning($sSchema);
  $oDataBaseVersioning->setFileLog($sFileLog);
  $oDataBaseVersioning->upgradeDatabase(); 
  
} catch ( Exception $eException ) {
  
  DB::rollBack();
  
  db_log($eException->getMessage()."\n",$sFileLog);
  
  exit;
  
}
  
DB::commit();

db_log("Processo Finalizado com Sucesso!\n",$sFileLog);
  
?>