<?php


class DatabaseVersioning {
  
  public $sSchema   = '';
  
  public $sFileLog  = '';
  
  public $iParamLog = 0;
  
  
  function __construct($sSchema='') {
    
    $this->sSchema  = $sSchema;
    $this->sFileLog = "log/gera_database".date("Ymd_His").".log";
  }
  
  function upgradeDatabase() {

    $this->loadScripts();
  
    db_log("AVISO: Verificando Scripts a serem aplicados na base de dados ... ",$this->sFileLog,$this->iParamLog);
  
    $sScriptsNotApplied = " select * 
                              from ".$this->getSchema()."database_version_sql 
                              where applied is false 
                              order by version, ord  ";
                              
    $aScriptsNotApplied = DB::query($sScriptsNotApplied);

    if (is_bool($aScriptsNotApplied)) {
      throw new Exception("ERRO: Ao executar SQL {$sScriptsNotApplied} ");
    }
  
    $iCount   = $aScriptsNotApplied->rowCount();
    $lApplied = false;
  
    db_log("AVISO: Existe(m) {$iCount} Script(s) para ser(em) aplicado(s) na base de dados ... ",$this->sFileLog,$this->iParamLog);
    
    foreach ( $aScriptsNotApplied as $aScript ) {
      
      db_log("AVISO: Aplicando script {$aScript['sql_name']} da versão {$aScript['version']} na ordem de execução {$aScript['ord']}",$this->sFileLog,$this->iParamLog);
      
      if ( is_bool(DB::exec($aScript['script'])) ) {
        throw new Exception("ERRO: Ao executar SQL {$aScript['script']} ");
      }
      
      $sUpdateApplied  = " update ".$this->getSchema()."database_version_sql ";
      $sUpdateApplied .= "    set applied  = true                       ";
      $sUpdateApplied .= "  where sql_name = '{$aScript['sql_name']}'   ";
      $sUpdateApplied .= "    and version  = {$aScript['version']}      ";
      $sUpdateApplied .= "    and ord      = {$aScript['ord']}          ";
  
      if ( is_bool(DB::query($sUpdateApplied)) ) {
        throw new Exception("ERRO: Ao executar SQL {$sUpdateApplied} ");
      }
  
      $lApplied = true;
      
    }
  
    if ($lApplied) {
      db_log("AVISO: Foi(ram) aplicado(s) {$iCount} script(s) na base de dados ...",$this->sFileLog,$this->iParamLog);
    } else {
      db_log("AVISO: Nenhum script foi aplicado na base de dados ...",$this->sFileLog,$this->iParamLog);
    }
  
    return true;
  }
  
  
  function createTable( $sTable='', $sDDL='' ) {
  
    $sExistsTable  = " select exists ( select 1                                            "; 
    $sExistsTable .= "                   from information_schema.tables                    "; 
    $sExistsTable .= "                  where table_schema = '{$this->sSchema}'            "; 
    $sExistsTable .= "                    and table_name   = '{$sTable}' )  as table_exist ";
    
    $oExistsTable  = DB::query($sExistsTable);
    
    if (!$oExistsTable) {
      throw new Exception("ERRO: Ao executar SQL $sExistsTable ");
    }
    
    if ( !$oExistsTable->fetchColumn()  ) {
      if (is_bool(DB::exec($sDDL))) {
        throw new Exception("ERRO: Ao executar SQL $sDDL ");
      }
    }
  
    return true;
  }
  
  
  function checkTablesVersioning() {
  
    $sExecute = " CREATE TABLE ".$this->getSchema()."database_version (
                      version integer   NOT NULL,
                      created timestamp DEFAULT now() NOT NULL
                  );
            
                  ALTER TABLE ONLY ".$this->getSchema()."database_version
                      ADD CONSTRAINT database_version_pk PRIMARY KEY (version); ";
                      
  
    if (!$this->createTable('database_version', $sExecute)) {
      return false;
    }
  
    $sExecute = " CREATE TABLE ".$this->getSchema()."database_version_sql (
                      sql_name character varying(100) NOT NULL,
                      version  integer                NOT NULL,
                      ord      integer DEFAULT 1      NOT NULL,
                      script   text                   NOT NULL,
                      applied  boolean DEFAULT false  NOT NULL
                  );
            
                  ALTER TABLE ONLY ".$this->getSchema()."database_version_sql
                      ADD CONSTRAINT database_version_sql_pk PRIMARY KEY (sql_name, version);
            
                  CREATE INDEX database_version_sql_version_idx ON ".$this->getSchema()."database_version_sql USING btree (version);
            
                  ALTER TABLE ONLY ".$this->getSchema()."database_version_sql
                      ADD CONSTRAINT database_version_sql_version_fk FOREIGN KEY (version) REFERENCES ".$this->getSchema()."database_version(version);";
  
    if (!$this->createTable('database_version_sql', $sExecute)) {
      return false;
    }
  
    return true;
  }
  
  
  function loadScripts() {
  
    $sDirectoryScripts = 'db';
  
    if (!is_dir($sDirectoryScripts)) {
      throw new Exception("Diretório {$sDirectoryScripts} não existe!");
    }
  
    if (!$this->checkTablesVersioning()) {
      return false;
    }
    
    $sLastVersion  = "   select version                           "; 
    $sLastVersion .= "     from ".$this->getSchema()."database_version "; 
    $sLastVersion .= " order by version desc limit 1              ";

    $oLastVersion  = DB::query($sLastVersion);
    
    if (is_bool($oLastVersion)) {
      throw new Exception("ERRO: Ao executar SQL $sLastVersion ");
    }
  
    if ($oLastVersion->rowCount() == 0) {
      $iLastVersion = 0;
    } else {
      $iLastVersion = $oLastVersion->fetchColumn();
    }
    
    $sDirectoryCheck = $sDirectoryScripts . '/' . $iLastVersion;
    
    while( is_dir($sDirectoryCheck) ) {
      
      db_log("AVISO: Verificando Scripts em {$sDirectoryCheck}",$this->sFileLog,$this->iParamLog);
  
      $aFiles     = scandir($sDirectoryCheck);
      
      $sSqlExists = " select * 
                        from ".$this->getSchema()."database_version 
                       where version = {$iLastVersion} ";
      $oExists = DB::query($sSqlExists);
      $iOrd    = 0;
      
      if( $oExists->rowCount() == 0 ) {
        
        db_log("AVISO: Inserindo versão {$iLastVersion} do schema da base de dados",$this->sFileLog,$this->iParamLog);
        
        $sInsert = "INSERT INTO ".$this->getSchema()."database_version(version) VALUES ({$iLastVersion})";
        
        if ( is_bool(DB::query($sInsert)) ) {
          throw new Exception("ERRO: Ao executar SQL $sInsert ");
        }
        
      } else {
        
        $sLastOrder = "SELECT coalesce(max(ord),0) FROM ".$this->getSchema()."database_version_sql WHERE version = {$iLastVersion}";
        $oLastOrder = DB::query($sLastOrder);
        $iOrd       = $oLastOrder->fetchColumn();
      }
  
      foreach($aFiles as $sFile) {
        
        if($sFile == '.' or $sFile == '..') {
          continue;
        }
        
        $aPathInfo = pathinfo($sDirectoryCheck.'/'.$sFile);
  
        if($aPathInfo['extension'] != 'sql') {
          continue;
        }
  
        $sSqlExists = " select * 
                          from ".$this->getSchema()."database_version_sql 
                         where sql_name = '{$sFile}' 
                           and version  = {$iLastVersion}";
                           
        $oLastOrder = DB::query($sSqlExists);
  
        if( $oLastOrder->rowCount() == 0 ) {
          
          $iOrd++;
          
          db_log("AVISO: Carregando script {$sFile} da versão {$iLastVersion} na ordem de execução {$iOrd}", $this->sFileLog,$this->iParamLog);
          
          $sScriptContent = file_get_contents($sDirectoryCheck.'/'.$sFile);
          $sScriptContent = addslashes($sScriptContent);
          
          $sInsert  = "INSERT INTO ".$this->getSchema()."database_version_sql (sql_name, version, ord, script) ";
          $sInsert .= "VALUES ('{$sFile}', {$iLastVersion}, {$iOrd}, '{$sScriptContent}')";
          
          if ( is_bool(DB::query($sInsert)) ) {
            throw new Exception("ERRO: Ao executar SQL $sInsert ");  
          }
        }
      }
  
      $iLastVersion++;
      $sDirectoryCheck = $sDirectoryScripts . '/'.$iLastVersion;
      
    }
  }
  
  function getSchema() {
    if ( trim($this->sSchema) != '' ) {
      return $this->sSchema.".";
    } else {
      return '';
    }
  }
  
  
  function setFileLog($sFileLog='') {
    $this->sFileLog = $sFileLog;
  }

  
  function setParamLog($iParam=0) {
    $this->iParamLog = $iParam;    
  }
  
  
  
}

?>