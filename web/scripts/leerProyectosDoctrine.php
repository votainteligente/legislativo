<?php
// Extrae proyecto del senado (http://sil.senado.cl)
include('simple_html_dom.php');
include('log.php');
require("class/cnx.php");
require("class/controlador.php");

$coneccion = coneccion();
$o = new Controlador();

$log = new Logging();
$arrayMeses = Array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$debug = true;
$write = false;

$sil_url = 'http://sil.senado.cl/cgi-bin/';
$fechas_url = $sil_url.'sil_ultproy.pl';
$proyectos_url = $sil_url.'sil_proyectos.pl?';
$autores_url = $sil_url.'sil_autores.pl?';

$desde = "30/04/2010";
$hasta = "09/05/2010";

$log->lwrite_ln();
$log->lwrite("Periodo: ".$desde."-".$hasta);
$log->lwrite_ln();
if ($debug) echo "<br>----------------Periodo: ".$desde."-".$hasta."-------------------<br>";
	
$fechas_post = file_get_contents_curl($fechas_url,$desde,$hasta);
$fechas_html = str_get_html($fechas_post);

$num=0;
foreach($fechas_html->find('td[class="TEXTpais"]') as $nro)
{
    $nro_boletin = trim(str_replace("&nbsp;","",$nro->plaintext));
    if($debug)
        echo "<h1>".$nro_boletin."</h1>";

    //FALTA: controlar si ya existe proyectoLey -> 2010-05-09 se incorporó validación Carlos Martínez
		
    //get proyectoLey
    $proyectos_html = file_get_html($proyectos_url.$nro_boletin);
    $titulo = $proyectos_html->find('span[class="TEXTpais"]');
    $titulo = $titulo[0]->plaintext;
    $sub_etapa=null; $ley="NULL"; $ley_bcn=null; $decreto="NULL"; $decreto_bcn=null; $fecha_publicacion="NULL";
    foreach($proyectos_html->find('tr[bgcolor="#FFFFFF"]') as $i=>$val)
    {
        $tits = $val->find('span[class="TITULAR"]');
        $tit = trim($tits[0]->plaintext);
        $texts = $val->find('span[class="TEXTarticulo"]');
        $text = trim($texts[0]->plaintext);
        echo $tit.'--'.$text.'<br>';
        if(strstr($tit,'Fecha de Ingreso') != FALSE)
        {
          $fechaText = $text;
          $fechaArray =  explode(' ', $fechaText);
          for($j=1; $j<=12; $j++)
          {
              if($arrayMeses[$j]==substr($fechaArray[3], 0, -1)){
                  (strlen($j)==1) ? $mes = "0".$j : $mes = $j;
                      break;
              }
          }
          $fecha_ingreso = $fechaArray[4]."-".$mes."-".$fechaArray[1];
          $desdeY = explode("/",$desde);
          if ($fechaArray[4] < $desdeY[2]){
              echo $num." proyectos insertadas<br>";
              $log->lwrite("----------".$num." proyectos insertadas----------");
              exit();
          }
        }
        else if (strstr($tit,'Iniciativa') != FALSE)
        {
          $iniciativa = $text;
        }
        else if (strstr($tit,'Tipo de proyecto') != FALSE)
        {
          $tipo = $text;
        }
        else if (strstr($tit,'C&aacute;mara de origen') != FALSE)
        {
          $camara_origen = $text;
        }
        else if (strstr($tit,'Urgencia') != FALSE)
        {
          $urgencia = $text;
        }
        else if (strstr($tit,'Etapa') != FALSE)
        {
            $etapa = $text;
            if ($etapa == 'Tramitación terminada')
            {
                $leytext = trim($text->next_sibling()->innertext);
                if (substr_count($leytext, "?idLey="))
                { // Ley
                    $leytext = explode('\'', $leytext);
                    $ley_bcn = trim($leytext[1]);
                    $ley = substr($ley_bcn,strpos($ley_bcn,"?idLey=")+7,strpos($ley_bcn,"&idVersion")-strpos($ley_bcn,"?idLey=")-7);
                    $fecha_publicacion = substr($ley_bcn,strpos($ley_bcn,"&idVersion")+11);
                }
                elseif(substr_count($leytext, "?idNorma="))
                { // Decreto
                  $decretotext = explode('\'', $leytext);
                  $decreto_bcn = trim($decretotext[1]);
                  $decreto = substr($decretotext[6],strpos($decretotext[6],"D.S. Nº")+10,strpos($decretotext[6],"</a> (D.Oficial:")-strpos($decretotext[6],"D.S. Nº")-10);
                  $decreto = str_replace(".", "", $decreto);
                  $fecha_publicacion = substr($decreto_bcn,strpos($decreto_bcn,"&idVersion")+11);
                }
            }
        }
        else if (strstr($tit,'Subetapa') != FALSE)
        {
            $sub_etapa = $text;
        }
        else if (strstr($tit,'Refundido con') != FALSE){
            $refundido = trim($val->next_sibling()->plaintext); //falta insertar a BBDD
        }
			
        //id proyecto en SIL para tramitaciones, indicaciones, etc.
        $id_proyecto_sil = $proyectos_html->find('a', -1)->href;
        $id_proyecto_sil = substr($id_proyecto_sil,strpos($id_proyecto_sil,"?")+1);
        $id_proyecto_sil = str_replace(",","",$id_proyecto_sil);
    }
    if ($debug) echo "Titulo:".$titulo."<br>Fecha:".$fecha_ingreso."<br>Ini:".$iniciativa."<br>Tipo:".$tipo."<br>Camara:".$camara_origen."<br>Urgencia:".$urgencia."<br>Etapa:".$etapa."<br>Subetapa:".$sub_etapa."<br>ley:".$ley."<br>ley_bcn:".$ley_bcn."<br>decreto:".$decreto."<br>decreto_bcn:".$decreto_bcn."<br>fecha_publicacion:".$fecha_publicacion."<br>idProyectoSIL:".$id_proyecto_sil;
		
    /* INSERT PROYECTO-LEY */
    $qry="SELECT if(count(*)=0,0,1) value1 FROM ProyectoLey WHERE nro_boletin='$nro_boletin'";
    $o->setQry($qry);
    if($o->selectQ()==0)
    {
        $qry = "INSERT INTO
                    ProyectoLey
                    (
                        nro_boletin,
                        titulo,
                        fecha_ingreso,
                        iniciativa,
                        tipo,
                        camara_origen,
                        urgencia,
                        etapa,
                        sub_etapa,
                        ley,
                        ley_bcn,
                        decreto,
                        decreto_bcn,
                        fecha_publicacion,
                        nro_interno,
                        created_at,
                        updated_at
                    ) VALUES (
                        '$nro_boletin',
                        '$titulo',
                        '$fecha_ingreso',
                        '$iniciativa',
                        '$tipo',
                        '$camara_origen',
                        '$urgencia',
                        '$etapa',
                        '$sub_etapa',
                        $ley,
                        '$ley_bcn',
                        $decreto,
                        '$decreto_bcn',
                        '$fecha_publicacion',
                        $id_proyecto_sil,
                        NOW(),
                        NOW()
                    )";
        //echo "<br><br><strong>".$qry."</strong><br>";
        $o->setQry($qry);
        $o->execute();

        $select="SELECT id_proyecto_ley value1 FROM ProyectoLey
                    WHERE nro_boletin='$nro_boletin' AND fecha_ingreso='$fecha_ingreso' ORDER BY id_proyecto_ley DESC LIMIT 1";
        $o->setQry($select);
        $idProyectoLey = $o->selectQ();

        $log->lwrite("nuevo proyecto:".$nro_boletin." | ".$idProyectoLey." | Nuevo");
        if ($debug)
            echo "<br>Proyecto Nuevo: [".$idProyectoLey."] NroBoletin: ".$nro_boletin." Etapa: ".$etapa;

        //AUTORES
        $autores_html = file_get_html($autores_url.$id_proyecto_sil);
        foreach($autores_html->find('span[class="TEXTarticulo"]') as $autor)
        {
            $autor = str_replace("&nbsp;","",$autor->plaintext);
            $autor = explode(",",$autor);
            $nombre = trim($autor[1]);
            $apellidos = trim($autor[0]);

            $qry = "SELECT if(count(id_autor)>0,id_autor,0) value1 FROM Autor WHERE nombre='$nombre' AND apellidos='$apellidos'";
            $o->setQry($qry);
            $idAutor = $o->selectQ();
            if($idAutor>0)
            {
                if($debug)
                    echo "<br>Autor ya existe: [".$idAutor."] ".$apellidos.", ".$nombre;
            }
            else
            {
                /* INSERT AUTOR */
                $qry = "INSERT INTO Autor (nombre, apellidos, created_at, updated_at) VALUES ('$nombre', '$apellidos', NOW(), NOW())";
                //echo "<br><br><strong>".$sql."</strong><br>";
                $o->setQry($qry);
                $o->execute();

                $qry = "SELECT id_autor value1 FROM Autor WHERE nombre='$nombre' AND apellidos='$apellidos' ORDER BY id_autor DESC LIMIT 1";
                $o->setQry($qry);
                $idAutor = $o->selectQ();
                
                $log->lwrite("Creado nuevo autor: [".$idAutor."] ".$apellidos.", ".$nombre);
                if($debug)
                    echo "<br>Creado nuevo autor: [".$idAutor."] ".$apellidos.", ".$nombre;
            }

            // busca si existe el la relacion Proyecto - autor
            $qry="SELECT if(count(id_autor)>0,1,0) value1 FROM AutorProyectoLey WHERE id_autor=$idAutor AND id_proyecto_ley=$idProyectoLey LIMIT 1";
            $o->setQry($qry);
            $value=$o->selectQ();
            if($value>0)
            {
                if($debug)
                    echo "<br>Ya existe la relación autor: $idAutor proyecto: $idProyectoLey";
            }
            else
            {
                /* INSERT AUTOR-PROYECTO-LEY*/
                $sql = "INSERT INTO AutorProyectoLey (id_autor, id_proyecto_ley) VALUES ($idAutor, $idProyectoLey)";
                //echo "<br><br><strong>".$sql."</strong><br>";
                $o->setQry($qry);
                $o->execute();
                
                $log->lwrite("Creada la relacion autor: ".$idAutor." proyecto: ".$idProyectoLey);
                if($debug)
                    echo "<br>Creada la relación autor: ".$idAutor." proyecto: ".$idProyectoLey;
            }
        }
        $proyectos_html->clear();
        unset($proyectos_html);
        $autores_html->clear();
        unset($autores_html);
        $num++;
    }else
    {
        echo "<br><br>Ya existe el proyecto de ley #$nro_boletin<br><br>";
    }
}
echo "$num proyectos insertadas<br>";
$log->lwrite("----------".$num." proyectos insertadas----------");
$fechas_html->clear();
unset($fechas_html);


function file_get_contents_curl($url,$desde,$hasta)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "desde=".$desde."&hasta=".$hasta."&buscar=%3E%3E+Buscar");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>
