<?php
class Assentamento extends AppModel {
  public $useTable = 'assentamentos';

  public $belongsTo = array(
    'Servidor' => array(
      'className'  => 'Servidor',
      'foreignKey' => 'servidor_id'
    )
  );

  /**
   * Retorna os Assentamentos para o servidor passado
   *
   * @param Integer $iServidorId
   * @return array
   */
  public function getAssentamento($iServidorId) {

    return $this->find('all', array(
      'recursive' => -1,
      'conditions' => array(
        'Assentamento.servidor_id' => $iServidorId
      )
    ));
  }
}
?>