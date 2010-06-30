<?php use_helper('I18N') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
    <table>
      <tr>
        <th style="text-align: right;"><?php echo $form['username']->renderLabel('Usuario') ?></th>
        <td>
          <?php echo $form['username'] ?><?php echo $form['username']->renderError() ?>
        </td>
      </tr>
      <tr>
        <th style="text-align: right;"><?php echo $form['password']->renderLabel('Contraseña') ?></th>
        <td>
          <?php echo $form['password'] ?><?php echo $form['password']->renderError() ?>
        </td>
      </tr>
      <tr>
          <th style="text-align: right;"><?php echo $form['remember']->renderLabel('Recordar') ?></th>
          <td><?php echo $form['remember'] ?></td>
      </tr>
      <tr>
          <td style="text-align: center" colspan="2">
              <input type="submit" value="<?php echo __('Ingresar') ?>" /><br /><br />
                <a href="<?php echo url_for('@sf_guard_password') ?>"><?php echo __('Olvidó su contraseña?') ?></a>
          </td>
      </tr>
          <?php echo $form->renderHiddenFields()?>
    </table>
</form>
