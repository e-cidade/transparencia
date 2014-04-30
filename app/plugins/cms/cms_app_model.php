<?php

class CmsAppModel extends AppModel {

  public function __construct($id = false, $table = null, $ds = null) {

    $oDatabase = new DATABASE_CONFIG();

    $config = $oDatabase->default;

    $config["schema"] = "cms";

    $ds = ConnectionManager::create("cms", $config);

    $this->useDbConfig = "cms";
    
    parent::__construct($id, $table, $ds);

  }

}

?>