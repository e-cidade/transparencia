<?php

 class DB {
   
  
  private static $oInstance = '';

  
  public static function getInstance() {
    
    require_once 'ConnectionManager.class.php';
    
    if ( !self::$oInstance ) {
      self::$oInstance = new ConnectionManager();   
    }
    
    return self::$oInstance;
  }
  
  
  public static function __callStatic( $sMethod, $aArguments ) {

    $oInstance = self::getInstance();

    return call_user_func_array( array( $oInstance->getPDO(), $sMethod ), $aArguments );
  } 
  
}

?>