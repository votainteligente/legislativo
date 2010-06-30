<?php

/**
 * ParlamentarioEnComision form base class.
 *
 * @method ParlamentarioEnComision getObject() Returns the current form's model object
 *
 * @package    vota
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseParlamentarioEnComisionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id_parlamentario' => new sfWidgetFormInputHidden(),
      'id_comision'      => new sfWidgetFormInputHidden(),
      'cargo'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id_parlamentario' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id_parlamentario', 'required' => false)),
      'id_comision'      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id_comision', 'required' => false)),
      'cargo'            => new sfValidatorString(array('max_length' => 1, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('parlamentario_en_comision[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ParlamentarioEnComision';
  }

}
