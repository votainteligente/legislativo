<?php

/**
 * ParlamentarioEnComision filter form base class.
 *
 * @package    vota
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseParlamentarioEnComisionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'cargo'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'cargo'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('parlamentario_en_comision_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ParlamentarioEnComision';
  }

  public function getFields()
  {
    return array(
      'id_parlamentario' => 'Number',
      'id_comision'      => 'Number',
      'cargo'            => 'Text',
    );
  }
}
