<?php

/**
 * Sesion module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage Sesion
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSesionGeneratorConfiguration extends sfModelGeneratorConfiguration
{
  public function getActionsDefault()
  {
    return array();
  }

  public function getFormActions()
  {
    return array(  '_delete' => NULL,  '_list' => NULL,  '_save' => NULL,  '_save_and_add' => NULL,);
  }

  public function getNewActions()
  {
    return array();
  }

  public function getEditActions()
  {
    return array();
  }

  public function getListObjectActions()
  {
    return array(  '_edit' => NULL,  '_delete' => NULL,);
  }

  public function getListActions()
  {
    return array(  '_new' => NULL,);
  }

  public function getListBatchActions()
  {
    return array(  '_delete' => NULL,);
  }

  public function getListParams()
  {
    return '%%nro%% - %%=fecha%% - %%camara%%';
  }

  public function getListLayout()
  {
    return 'tabular';
  }

  public function getListTitle()
  {
    return 'Gestión de Sesiones';
  }

  public function getEditTitle()
  {
    return 'Edit Sesion';
  }

  public function getNewTitle()
  {
    return 'New Sesion';
  }

  public function getFilterDisplay()
  {
    return array();
  }

  public function getFormDisplay()
  {
    return array();
  }

  public function getEditDisplay()
  {
    return array();
  }

  public function getNewDisplay()
  {
    return array();
  }

  public function getListDisplay()
  {
    return array(  0 => 'nro',  1 => '=fecha',  2 => 'camara',);
  }

  public function getFieldsDefault()
  {
    return array(
      'id_sesion' => array(  'is_link' => true,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'fecha' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'camara' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'nro' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
      'created_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'updated_at' => array(  'is_link' => false,  'is_real' => true,  'is_partial' => false,  'is_component' => false,  'type' => 'Date',),
      'proyecto_ley_list' => array(  'is_link' => false,  'is_real' => false,  'is_partial' => false,  'is_component' => false,  'type' => 'Text',),
    );
  }

  public function getFieldsList()
  {
    return array(
      'id_sesion' => array(),
      'fecha' => array(),
      'camara' => array(),
      'nro' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'proyecto_ley_list' => array(),
    );
  }

  public function getFieldsFilter()
  {
    return array(
      'id_sesion' => array(),
      'fecha' => array(),
      'camara' => array(),
      'nro' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'proyecto_ley_list' => array(),
    );
  }

  public function getFieldsForm()
  {
    return array(
      'id_sesion' => array(),
      'fecha' => array(),
      'camara' => array(),
      'nro' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'proyecto_ley_list' => array(),
    );
  }

  public function getFieldsEdit()
  {
    return array(
      'id_sesion' => array(),
      'fecha' => array(),
      'camara' => array(),
      'nro' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'proyecto_ley_list' => array(),
    );
  }

  public function getFieldsNew()
  {
    return array(
      'id_sesion' => array(),
      'fecha' => array(),
      'camara' => array(),
      'nro' => array(),
      'created_at' => array(),
      'updated_at' => array(),
      'proyecto_ley_list' => array(),
    );
  }


  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return 'SesionForm';
  }

  public function hasFilterForm()
  {
    return false;
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getFilterFormClass()
  {
    return 'SesionFormFilter';
  }

  public function getPagerClass()
  {
    return 'sfDoctrinePager';
  }

  public function getPagerMaxPerPage()
  {
    return 20;
  }

  public function getDefaultSort()
  {
    return array('fecha', 'desc');
  }

  public function getTableMethod()
  {
    return '';
  }

  public function getTableCountMethod()
  {
    return '';
  }
}
