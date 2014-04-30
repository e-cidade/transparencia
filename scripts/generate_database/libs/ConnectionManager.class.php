<?php

 class ConnectionManager {
  
  private $oPDO      = '';
  private $sDriver   = '';
  private $sHost     = '';
  private $sDataBase = '';
  private $sUser     = '';
  private $sPort     = '';
  private $sPassWord = '';
  private $sSchema   = '';

  function __construct() {

    require_once '../../app/config/database.php';
    
    $oDataBase = new DATABASE_CONFIG();
    
    $this->setDriver($oDataBase->default['driver']);
    $this->sHost     = $oDataBase->default['host'];
    $this->sDataBase = $oDataBase->default['database'];
    $this->sUser     = (isset($oDataBase->default['login']   )?$oDataBase->default['login']   :'');
    $this->sPort     = (isset($oDataBase->default['port']    )?$oDataBase->default['port']    :'');
    $this->sPassWord = (isset($oDataBase->default['password'])?$oDataBase->default['password']:'');
    $this->sSchema   = (isset($oDataBase->default['schema']  )?$oDataBase->default['schema']  :'');    
    $this->oPDO      = new PDO($this->getDataSourceName(),$this->sUser,$this->sPassWord); 
    
  }
  
  
  public function setDriver($sDriver='') {
    
    $aDBConfig = array('pgsql','mysql','sqlite','mssql','oci','firebird','odbc');
    
    if ( $sDriver == 'postgres') {
      $sReturnDriver = 'pgsql';  
    } else if ( $sDriver == 'mysqli' ) {
      $sReturnDriver = 'mysql';
    } else if ( $sDriver == 'oracle' ) {
      $sReturnDriver = 'oci';           
    } else if ( in_array($sDriver,$aDBConfig) ) {
      $sReturnDriver = $sDriver;
    } else {      
      throw new Exception('Driver de conexão não configurado!');
    }    
    
    $this->sDriver = $sReturnDriver;
    
  }
    
  private function getDataSourceName() {

    $sDSN  = $this->sDriver.":";
    $sDSN .= "dbname=". $this->sDataBase . ";";
    $sDSN .= "host="  . $this->sHost     . ";";
    
    if ( trim($this->sPort) != '' ) {
      $sDSN .= "port=". $this->sPort     . ";";
    }    
    
    return $sDSN;
  }
  
  public function getPDO() {
    return $this->oPDO; 
  }
  
  public function getHost() {
    return $this->sHost;
  }  
  
  public function getDataBase() {
    return $this->sDataBase;
  }  

  public function getPort() {
    return $this->sPort;
  }    

  public function getSchema() {
    return $this->sSchema;
  }    
  
}

?>