<?php

class VotacionTable extends Doctrine_Table
{
  static public $discusion = array(
    'particular' => 'particular',
    'general' => 'general',
    'ambas' => 'ambas',
    'única' => 'única',
  );
  static public $resultados = array(
    'Aprobado' => 'Aprobado',
    'Rechazado' => 'Rechazado',
  );
  static public $camara = array(
    'C.Diputados' => 'C.Diputados',
    'Senado' => 'Senado',
  );
  static public $enSala = array(
    '1' => 'Sala',
    '0' => 'Comsión',
  );
  static public $visible = array(
    '1' => 'si',
    '0' => 'no',
  );
  
  public function getDiscusiones()
  {
    return self::$discusion;
  }
  public function getResultados()
  {
    return self::$resultados;
  }
  public function getCamaras()
  {
    return self::$camara;
  }
  public function getEnSala()
  {
    return self::$enSala;
  }
  public function getVisible()
  {
    return self::$visible;
  }
}
