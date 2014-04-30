<?php

/**
 * Caminho do diretório do arquivo 
 */
define ('PATH', dirname(__FILE__) . '/'); 

require_once PATH . 'libs/lib.php';
require_once PATH . 'libs/DBDataBaseMigration.model.php';

/**
 * Arquivo de log  
 */
$sFileLog = PATH . "log/update_database" . date("Ymd_H") . ".log";
$pConnection = false;

try {
  
  db_log("Processo inicializado", $sFileLog);

  /**
   * Arquivo com configuracoes de acesso ao banco 
   */
  require_once PATH . '../../app/config/database.php';

  $oDatabase = new DATABASE_CONFIG();
  $aConfig   = $oDatabase->default;

  db_log("Conectando a base", $sFileLog);
  $pConnection = @pg_connect(
    "host={$aConfig['host']} dbname={$aConfig['database']} port={$aConfig['port']} user={$aConfig['login']} password={$aConfig['password']}"
  );

  if ( !$pConnection ) {
    throw new Exception('Erro ao conectar ao banco de dados.');
  }

  pg_query($pConnection, 'begin');

  /**
   * Caminho com arquivos de atualizacao da base 
   */
  DBDataBaseMigration::$sPathScrips = PATH . "db";

  /**
   * Atualiza base caso exista alguem arquivo sql nao executado ainda
   */
  db_log("Atualizando base", $sFileLog);
  DBDataBaseMigration::upgradeDatabase($pConnection);

  pg_query($pConnection, 'commit');
  
  db_log("Processo Finalizado com Sucesso!", $sFileLog);
  
} catch ( Exception $eException ) {

  if ($pConnection) {
    pg_query($pConnection, 'rollback');
  }
  db_log($eException->getMessage(), $sFileLog);
}
