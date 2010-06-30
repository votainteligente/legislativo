<?php use_helper('I18N', 'Date') ?>
<?php include_partial('ProyectoLey/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('New ProyectoLey', array(), 'messages') ?></h1>

  <?php include_partial('ProyectoLey/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('ProyectoLey/form_header', array('proyecto_ley' => $proyecto_ley, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('ProyectoLey/form', array('proyecto_ley' => $proyecto_ley, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('ProyectoLey/form_footer', array('proyecto_ley' => $proyecto_ley, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
