<?php 
  use_javascript('jquery.min.js'); 
  use_javascript('ui/jquery.ui.core.min.js');
  use_javascript('ui/jquery.ui.widget.min.js');
  use_javascript('ui/jquery.ui.accordion.min.js');
  use_javascript('jquery-accordion.js');
  use_stylesheet('tweet.css');
?>

<?php slot('breadbrumb') ?>
  <li>> <a href="<?php echo url_for('Parlamentario/index') ?>">Perfiles Parlamentarios</a></li>
  <li class="actual">> <?php echo $parlamentario->getNombre().' '.$parlamentario->getApellidos() ?></li>
<?php end_slot() ?>

<div id="top">
  <div class="topperfil">
    <img class="fotoperfil" src="/images/parlamentarios/<?php echo $parlamentario->getIdParlamentario() ?>.png">
  </div>

  <div class="datospersonales">
    <h1 class="perfil"><a href="#"><?php echo $parlamentario->getNombre() ?> <?php echo $parlamentario->getApellidos() ?></a></h1>
    <p><?php 
      if($parlamentario->getSenadorDiputado()=='S') echo "Senador por Circunscipción ".$parlamentario->getCircunscripcion();
      else echo "Diputado por Distrito ".$parlamentario->getDistrito();
    ?></p>
    <p>Sexo: <?php echo $parlamentario->getSexo() ?></p>
    <p>Fecha nacimiento: <?php echo $parlamentario->getDateTimeObject('fecha_nacimiento')->format('d/m/Y') ?></p>
  </div>

  <div class="block">
    <div class="blockdata">
      <table class="enlacespersonales">
        <tbody>
          <tr><td>
                <p class="enunciado">mail: <a class="linkazul" href="mailto:<?php echo $parlamentario->getMail() ?>"><?php echo $parlamentario->getMail() ?></a></p>
                <p class="enunciado">web: <a class="linkazul" href="http://<?php echo $parlamentario->getWeb() ?>"><?php echo $parlamentario->getWeb() ?></a></p>
                <p class="enunciado">twitter: <a class="linkazul" href="http://twitter.com/<?php echo $parlamentario->getTwitter() ?>"><?php echo $parlamentario->getTwitter() ?></a></p>
                <p class="enunciado">facebook: <a class="linkazul" href="http://www.facebook.com/<?php if (is_numeric($parlamentario->getFacebook())) echo 'profile.php?id='; echo $parlamentario->getFacebook(); ?>"><?php echo $parlamentario->getFacebook() ?></a></p>
          </td></tr>
        </tbody>
      </table>
      <table class="datoelectorales">
        <tbody>
          <tr><th>Nº votos</th><th>% votos</th></tr>
          <tr><td><?php echo $parlamentario->getVotoNro() ?></td><td><?php echo $parlamentario->getVotoPorcentaje() ?></td></tr>
        </tbody>
      </table>
      <table class="datoelectorales">
        <tbody>
          <tr><th>Pacto</th><th>Partido</th></tr>
          <tr><td class="pacto"><?php echo $parlamentario->getPacto() ?></td><td class="partido"><a href="<?php echo url_for('Partido/show?id_partido='.$parlamentario->getIdPartido()) ?>"><img src="/images/partidos/<?php echo $parlamentario->getPartido()->getIdPartido() ?>_ch.png"></a></td></tr>
        </tbody>
      </table>
    </div>
  </div>

<div id="left">
  <div id="accordion">
    <h3><a href="#">INFO PARLAMENTARIO</a></h3>
    <div>
      <?php if ($parlamentario->getMesaDirectiva() != null): ?><p><span class="enunciado">Mesa directiva:</span> <?php echo $parlamentario->getMesaDirectiva() ?></p><?php endif; ?>
      <p><span class="enunciado">Períodos legislativos:</span> <?php echo ($parlamentario->getSenadorDiputado()=='S') ? $parlamentario->getPeriodosSenadorDesc() : $parlamentario->getPeriodosDiputadoDesc() ?></p>
      <p><span class="enunciado">Primera vez:</span> <?php echo $parlamentario->getPrimeraVez() ?></p>
      <p><span class="enunciado">Comisiones anteriores:</span> <?php echo $parlamentario->getComisionesAnteriores() ?></p>
      <p><span class="enunciado">Comisiones actuales:</span> <?php echo ($parlamentario->getSenadorDiputado()=='S' && $parlamentario->getComisionesActuales() == null) ? 'El senado no las ha definido. Se resolverá el 6 de abril.' : $parlamentario->getComisionesActuales() ?></p>
      <p><span class="enunciado">Comité parlamentario:</span> <?php echo $parlamentario->getComiteParlamentario() ?></p>
    </div>
    <h3><a href="#">GASTOS Y FINANCIAMIENTO ELECTORAL</a></h3>
    <div>
      <?php if ($parlamentario->getGastoElectoral2005() != NULL && $parlamentario->getGastoElectoral2005() != 0 && $parlamentario->getGastoElectoral2005() != ''): ?>
        <p><span class="enunciado">Gasto electoral 2005:</span> <?php echo '$ '.number_format($parlamentario->getGastoElectoral2005(), 0, ',', '.'); ?></p>
      <?php endif; ?>
      <?php if ($parlamentario->getFinanciamientoElectoral2005() != NULL && $parlamentario->getFinanciamientoElectoral2005() != 0  && $parlamentario->getFinanciamientoElectoral2005() != ''): ?>
        <p><span class="enunciado">Financiamiento electoral 2005:</span> <?php echo '$ '.number_format($parlamentario->getFinanciamientoElectoral2005(), 0, ',', '.') ?></p>
      <?php endif; ?>
      <?php if ($parlamentario->getGastoElectoral2005() == NULL || $parlamentario->getGastoElectoral2005() == 0 || $parlamentario->getGastoElectoral2005() == ''): ?>
        <p><span class="enunciado">Gasto electoral 2009:</span> <?php echo ($parlamentario->getGastoElectoral2009() == 0) ? 'No presentó su declaración al SERVEL.' : '$ '.number_format($parlamentario->getGastoElectoral2009(), 0, ',', '.'); ?></p>
      <?php endif; ?>
      <?php if ($parlamentario->getFinanciamientoElectoral2005() == NULL || $parlamentario->getFinanciamientoElectoral2005() == 0 || $parlamentario->getFinanciamientoElectoral2005() == ''): ?>
        <p><span class="enunciado">Financiamiento electoral 2009:</span> <?php echo ($parlamentario->getFinanciamientoElectoral2009() == 0) ? 'No presentó su declaración al SERVEL.' : '$ '.number_format($parlamentario->getFinanciamientoElectoral2009(), 0, ',', '.'); ?></p>
      <?php endif; ?>
      <p><span class="enunciado">Dieta parlamentaria:</span> <?php echo ($parlamentario->getSenadorDiputado()=='D') ? '$ 5.161.415' : number_format($parlamentario->getDietas(), 0, ',', '.'); ?></p>
    </div>
    <h3><a href="#">DECLARACIONES DE INTERESES Y PATRIMONIO</a></h3>
    <div>
      <p>
          <span class="enunciado">Declaración de intereses:</span>
          <?php echo ($parlamentario->getDeclaracionInteres() != NULL) ? '<a class="linkazul" target="_blank" href="'.$parlamentario->getDeclaracionInteres().'">Pincha acá la DECLARACIÓN</a>' : 'No ha presentado su declaración 2010.' ?>
      </p>
      <p>
          <span class="enunciado">Declaración de patrimonio:</span>
          <?php echo ($parlamentario->getDeclaracionPatrimonio() != NULL) ? '<a class="linkazul" target="_blank" href="'.$parlamentario->getDeclaracionPatrimonio().'">Pincha acá la DECLARACIÓN</a>' : 'No ha presentado su declaración 2010.' ?>
      </p>
    </div>
    <h3><a href="#">TRAYECTORIA</a></h3>
    <div>
      <p><span class="enunciado">Educación universitaria:</span> <?php echo $parlamentario->getEducacionUniversitaria() ?></p>
      <p><span class="enunciado">Educación postgrado:</span> <?php echo $parlamentario->getEducacionPostgrado() ?></p>
      <p><span class="enunciado">Cargos de gobierno:</span> <?php echo $parlamentario->getCargosGobierno() ?></p>
      <p><span class="enunciado">Cargos de elección:</span> <?php echo $parlamentario->getCargosEleccion() ?></p>
      <p><span class="enunciado">Experiencia política:</span> <?php echo $parlamentario->getExperienciaPolitica() ?></p>
      <p><span class="enunciado">Experiencia laboral:</span> <?php echo $parlamentario->getExperienciaLaboral() ?></p>
    </div>
  </div>
</div>

<div id="midcontent" ><p>Conoce las mociones presentadas por tu parlamentario según materia y estado del proyecto de ley para los periódos legislativos <span class="p2006">2006-2010</span> y <span class="p2010">2010-2014</span>.</p>

  <div id="mociones">
    <?php include_partial('Parlamentario/mociones', array('parlamentario' => $parlamentario)) ?>
  </div>
  
  <?php if ($parlamentario->getSenadorDiputado() == 'D'): ?>
    <div id="votaciones">
      <?php include_partial('Parlamentario/votaciones', array('parlamentario' => $parlamentario)) ?>
      <div class="voto_semanas"<img src="/images/votacion/votacion_s.png" /><a href="<?php echo url_for('Parlamentario/votaciones?id_parlamentario='.$parlamentario->getIdParlamentario()) ?>">ver votaciones anteriores</a></div>
    </div>
  <?php endif; ?>

  <table>
    <tr>
      <td class="tb_twitter">
        <div id="twitter">
          <?php include_partial('Parlamentario/twitter', array('parlamentario' => $parlamentario)) ?>
        </div>
      </td>
      <td class="tb_twitter">
        <div id="news">
          <?php include_partial('Parlamentario/news', array('parlamentario' => $parlamentario)) ?>
        </div>
      </td>
    </tr>
  </table>
</div>
