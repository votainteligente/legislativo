<?php

/**
 * BaseMateria
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id_materia
 * @property string $nombre
 * @property string $super_materia
 * @property Doctrine_Collection $ProyectoLey
 * 
 * @method integer             getIdMateria()     Returns the current record's "id_materia" value
 * @method string              getNombre()        Returns the current record's "nombre" value
 * @method string              getSuperMateria()  Returns the current record's "super_materia" value
 * @method Doctrine_Collection getProyectoLey()   Returns the current record's "ProyectoLey" collection
 * @method Materia             setIdMateria()     Sets the current record's "id_materia" value
 * @method Materia             setNombre()        Sets the current record's "nombre" value
 * @method Materia             setSuperMateria()  Sets the current record's "super_materia" value
 * @method Materia             setProyectoLey()   Sets the current record's "ProyectoLey" collection
 * 
 * @package    vota
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7021 2010-01-12 20:39:49Z lsmith $
 */
abstract class BaseMateria extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('Materia');
        $this->hasColumn('id_materia', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('nombre', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('super_materia', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('ProyectoLey', array(
             'local' => 'id_materia',
             'foreign' => 'id_materia'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}