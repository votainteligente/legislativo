<?php echo input_tag('ctl00$mainPlaceHolder$txtFecha1', '06-05-2010') ?>
<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->get('http://www.camara.cl/trabajamos/sala_votaciones.aspx');
$browser->with('response')->begin();
$browser->end();

//$browser->checkElement('input ctl00_mainPlaceHolder_txtFecha1', '!/This is a temporary page/');

$browser->with('response')->begin();
//$browser->isStatusCode(200);
$browser->checkElement('body', '!/This is a temporary page/');
$browser->end();

//ctl00_mainPlaceHolder_txtFecha1
/*
$browser->
  get('/Sesion/index')->

  with('request')->begin()->
    isParameter('module', 'Sesion')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '!/This is a temporary page/')->
  end()
;*/