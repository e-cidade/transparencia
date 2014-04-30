<?php

class Menu extends CmsAppModel {
	
	public $name = 'Menu';

	public $primaryKey = 'id';

	public $actsAs = array('Tree');

  /**
   * Restaura as configurações dos menus
   *
   * @param Array $aSnap -- Configurações a serem restauradas
   * @return Boolean
   */
  public function restauraBackup( $aSnap ) {
    
    $this->begin();

    $aSnap = Set::sort($aSnap, '{n}.id', 'asc');

    $this->Behaviors->detach('Tree');

    if ($this->deleteAll("1 = 1") && (empty($aSnap) || $this->saveAll( $aSnap )) ) {

      $this->Behaviors->attach('Tree');

      $aRetorno  = $this->query("select max(id) as last from cms.menus;");
      $last = $aRetorno[0][0]['last']+1;



      $sSql = "alter sequence cms.menus_id_seq restart with $last;";
      $this->query($sSql);

      $this->commit();

      return true;
    }

    $this->Behaviors->attach('Tree');
    $this->rollback();
    
    return false;
  }
}

?>