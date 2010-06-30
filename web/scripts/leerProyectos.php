<?php
// Extrae proyecto del senado (http://sil.senado.cl)
include('simple_html_dom.php');
include('log.php');
//$cn = mysql_connect("localhost", "vota_p", "vota_754");
//$db = mysql_select_db("votainteligente_proyectos", $cn);
$cn = mysql_connect("localhost", "vota", "inteligente");
$db = mysql_select_db("vota", $cn);
/*
$cn = mysql_connect("localhost", "root", "Apollo13");
$db = mysql_select_db("vota", $cn);
*/
$log = new Logging();
$arrayMeses = Array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
$debug = true;
$write = false;

$sil_url = 'http://sil.senado.cl/cgi-bin/';
$fechas_url = $sil_url.'sil_ultproy.pl';
$proyectos_url = $sil_url.'sil_proyectos.pl?';
$autores_url = $sil_url.'sil_autores.pl?';

//periodos
//$desdeA = array("11/03/1990","11/03/1994","11/03/1998","11/03/2002","11/03/2006");
//$hastaA = array("10/03/1994","10/03/1998","10/03/2002","10/03/2006","10/03/2010");
//años
//$desdeA = array("01/01/1991","01/01/1992","01/01/1993","01/01/1994","01/01/1995","01/01/1996","01/01/1997","01/01/1998","01/01/1999","01/01/2000","01/01/2001","01/01/2002","01/01/2003","01/01/2004","01/01/2005","01/01/2006","01/01/2007","01/01/2008","01/01/2009","01/01/2010");
//$hastaA = array("31/12/1991","31/12/1992","31/12/1993","31/12/1994","31/12/1995","31/12/1996","31/12/1997","31/12/1998","31/12/1999","31/12/2000","31/12/2001","31/12/2002","31/12/2003","31/12/2004","31/12/2005","31/12/2006","31/12/2007","31/12/2008","31/12/2009","10/03/2010");
//1 año
$desdeA = array("30/04/2010");
$hastaA = array("07/05/2010");

foreach($desdeA as $pos=>$desde){
	$hasta = $hastaA[$pos];
	$log->lwrite_ln();
	$log->lwrite("Periodo: ".$desde."-".$hasta);
	$log->lwrite_ln();
	if ($debug) echo "<br>----------------Periodo: ".$desde."-".$hasta."-------------------<br>";
	
	$fechas_post = file_get_contents_curl($fechas_url,$desde,$hasta);
	$fechas_html = str_get_html($fechas_post);
	//echo $fechas_html;

	//para cada Proyecto en la lista de fechas:
	$num=0;
	foreach($fechas_html->find('td[class="TEXTpais"]') as $nro){
		$nro_boletin = trim(str_replace("&nbsp;","",$nro->plaintext));
		if ($debug) echo "<h1>".$nro_boletin."</h1>";
		
		//FALTA: controlar si ya existe proyectoLey
		
		//get proyectoLey
		$proyectos_html = file_get_html($proyectos_url.$nro_boletin);
		$titulo = $proyectos_html->find('span[class="TEXTpais"]');
		$titulo = $titulo[0]->plaintext;
		$sub_etapa=null; $ley="NULL"; $ley_bcn=null; $decreto="NULL"; $decreto_bcn=null; $fecha_publicacion="NULL";
		$id_materia = getMateria($nro_boletin,$cn);
		
		//para cada elemento en la ficha del proyecto:
		foreach($proyectos_html->find('tr[bgcolor="#FFFFFF"]') as $i=>$val){
			/*switch ($i){
			case 0: 
				//fechaIngreso
				$fechaText = $val->plaintext; 
				$fechaArray =  explode(' ', $fechaText);
				for($j=1;$j<=12;$j++){
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
				break;
			case 1: $iniciativa = $val->plaintext; break;	// FALTA : arreglar fallo -> algunos tienen td "Refundido con:" (por ejemplo: http://sil.senado.cl/cgi-bin/sil_proyectos.pl?2526-07)
			case 2: $tipo = $val->plaintext; break;
			case 3: $camara_origen = trim($val->plaintext); break;
			case 4: $urgencia = $val->plaintext; break;
			case 5: $etapa = $val->plaintext; break;
			case 6: 
				//subetapa
				$v = trim($val->plaintext);
				if ($v == 'Rechazado' || $v == 'Inconstitucional' || $v == 'Inadmisible' || $v == 'Rechazado' || $v == 'Retirado' || $v == 'En espera de promulgación') {
					$sub_etapa = $val->plaintext;
				}
				//ley o decreto
				else{
					$leytext = $val->innertext;
					if (substr_count($leytext, "?idLey=")){ // Ley
						$leytext = explode('\'', $leytext);
						$ley_bcn = trim($leytext[1]);
						$ley = substr($ley_bcn,strpos($ley_bcn,"?idLey=")+7,strpos($ley_bcn,"&idVersion")-strpos($ley_bcn,"?idLey=")-7);
						$fecha_publicacion = substr($ley_bcn,strpos($ley_bcn,"&idVersion")+11);
					}
					elseif(substr_count($leytext, "?idNorma=")){ // Decreto
						$decretotext = explode('\'', $leytext);
						$decreto_bcn = trim($decretotext[1]);
						$decreto = substr($decretotext[6],strpos($decretotext[6],"D.S. Nº")+10,strpos($decretotext[6],"</a> (D.Oficial:")-strpos($decretotext[6],"D.S. Nº")-10);
						$decreto = str_replace(".", "", $decreto);
						$fecha_publicacion = substr($decreto_bcn,strpos($decreto_bcn,"&idVersion")+11);
					}
				}
			}*/
			$tits = $val->find('span[class="TITULAR"]');
			$tit = trim($tits[0]->plaintext);
			$texts = $val->find('span[class="TEXTarticulo"]');
			$text = trim($texts[0]->plaintext);
			echo $tit.'--'.$text.'<br>';
			
			
			if (strstr($tit,'Fecha de Ingreso') != FALSE){
			  $fechaText = $text;
			  $fechaArray =  explode(' ', $fechaText);
			  for($j=1;$j<=12;$j++){
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
			else if (strstr($tit,'Iniciativa') != FALSE){ 
			  $iniciativa = $text;
			}
			else if (strstr($tit,'Tipo de proyecto') != FALSE){
			  $tipo = $text;
			}
			else if (strstr($tit,'C&aacute;mara de origen') != FALSE){
			  $camara_origen = $text;
			}
			else if (strstr($tit,'Urgencia') != FALSE){
			  $urgencia = $text;
			}
			else if (strstr($tit,'Etapa') != FALSE){
		    $etapa = $text;
		    if ($etapa == 'Tramitación terminada'){
		      $leytext = trim($text->next_sibling()->innertext);
				  if (substr_count($leytext, "?idLey=")){ // Ley
					  $leytext = explode('\'', $leytext);
					  $ley_bcn = trim($leytext[1]);
					  $ley = substr($ley_bcn,strpos($ley_bcn,"?idLey=")+7,strpos($ley_bcn,"&idVersion")-strpos($ley_bcn,"?idLey=")-7);
					  $fecha_publicacion = substr($ley_bcn,strpos($ley_bcn,"&idVersion")+11);
				  }
				  elseif(substr_count($leytext, "?idNorma=")){ // Decreto
					  $decretotext = explode('\'', $leytext);
					  $decreto_bcn = trim($decretotext[1]);
					  $decreto = substr($decretotext[6],strpos($decretotext[6],"D.S. Nº")+10,strpos($decretotext[6],"</a> (D.Oficial:")-strpos($decretotext[6],"D.S. Nº")-10);
					  $decreto = str_replace(".", "", $decreto);
					  $fecha_publicacion = substr($decreto_bcn,strpos($decreto_bcn,"&idVersion")+11);
				  }
				}
			}
			else if (strstr($tit,'Subetapa') != FALSE){
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
		if ($debug) echo "Titulo:".$titulo."<br>Fecha:".$fecha_ingreso."<br>Ini:".$iniciativa."<br>Tipo:".$tipo."<br>Camara:".$camara_origen."<br>Urgencia:".$urgencia."<br>Etapa:".$etapa."<br>Subetapa:".$sub_etapa."<br>ley:".$ley."<br>ley_bcn:".$ley_bcn."<br>decreto:".$decreto."<br>decreto_bcn:".$decreto_bcn."<br>fecha_publicacion:".$fecha_publicacion."<br>idMateria:".$id_materia."<br>idProyectoSIL:".$id_proyecto_sil;
		$sql = "INSERT INTO ProyectoLey (nro_boletin, titulo, fecha_ingreso, iniciativa, tipo, camara_origen, urgencia, etapa, sub_etapa, ley, ley_bcn, decreto, decreto_bcn, fecha_publicacion, id_materia, nro_interno, created_at, updated_at) VALUES ('".$nro_boletin."', '".$titulo."', '".$fecha_ingreso."', '".$iniciativa."', '".$tipo."', '".$camara_origen."', '".$urgencia."', '".$etapa."', '".$sub_etapa."', ".$ley.", '".$ley_bcn."', ".$decreto.", '".$decreto_bcn."', '".$fecha_publicacion."', ".$id_materia.", ".$id_proyecto_sil.", '".date("Y-m-d H:m:s")."', '".date("Y-m-d H:m:s")."')";
		echo "<br><strong>".$sql."</strong><br>";
		/*if ($write) mysql_query($sql, $cn);*/
		$idProyectoLey = mysql_insert_id($cn);
		
		$log->lwrite("nuevo proyecto:".$nro_boletin." | ".$idProyectoLey." | Nuevo");
		if ($debug) echo "<br>Proyecto Nuevo: [".$idProyectoLey."] NroBoletin: ".$nro_boletin." Etapa: ".$etapa;

		//AUTORES
		$autores_html = file_get_html($autores_url.$id_proyecto_sil);
		foreach($autores_html->find('span[class="TEXTarticulo"]') as $autor){
			$autor = str_replace("&nbsp;","",$autor->plaintext);
			$autor = explode(",",$autor);
			$nombre = trim($autor[1]);
			$apellidos = trim($autor[0]);

			$sql = "SELECT id_autor FROM Autor WHERE nombre='".$nombre."' AND apellidos='".$apellidos."'";
			$rs = mysql_query($sql, $cn);
			
			// Si existe obtiene el id
			if($rs){
				$fila = mysql_fetch_array($rs);
				$idAutor = $fila[0];
				if ($debug) echo "<br>Autor ya existe: [".$idAutor."] ".$apellidos.", ".$nombre;
			}
			else{
				$sql = "INSERT INTO Autor (nombre, apellidos, created_at, updated_at) VALUES ('".$nombre."', '".$apellidos."', '".date("Y-m-d H:m:s")."', '".date("Y-m-d H:m:s")."')";
				/*if ($write) mysql_query($sql, $cn);*/
				$idAutor = mysql_insert_id($cn);
				
				$log->lwrite("Creado nuevo autor: [".$idAutor."] ".$apellidos.", ".$nombre);
				if ($debug) echo "<br>Creado nuevo autor: [".$idAutor."] ".$apellidos.", ".$nombre;
			}
			
			// busca si existe el la relacion Proyecto - autor
			$sql = "SELECT * FROM AutorProyectoLey WHERE id_autor = ".$idAutor." AND id_proyecto_ley = ".$idProyectoLey;
			$rs = mysql_query($sql, $cn);
			
			// Si existe obtiene el id
			if($rs){
				if ($debug) echo "<br>Ya existe la relación autor: ".$idAutor." proyecto: ".$idProyectoLey;
			}else{
				$sql = "INSERT INTO AutorProyectoLey (id_autor, id_proyecto_ley) VALUES (".$idAutor.", ".$idProyectoLey.")";
				/*if ($write) mysql_query($sql, $cn);*/
				
				$log->lwrite("Creada la relacion autor: ".$idAutor." proyecto: ".$idProyectoLey);
				if ($debug) echo "<br>Creada la relación autor: ".$idAutor." proyecto: ".$idProyectoLey;
			}
		}
		
		$proyectos_html->clear();
		unset($proyectos_html);
		$autores_html->clear();
		unset($autores_html);
		$num++;
	}
	echo $num." proyectos insertadas<br>";
	$log->lwrite("----------".$num." proyectos insertadas----------");
	$fechas_html->clear();
	unset($fechas_html);
}

function file_get_contents_curl($url,$desde,$hasta) {
	/*
	This is a file_get_contents replacement function using cURL
	This is necessary to set POST variables. 
	*/
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

// Obtenemos la materia del proyecto
function getMateria($nroBoletin=null,$cn){
	// Primero buscamos en la tabla ProyectoLey_tmp
	if ($nroBoletin != null){
		$materia = null;
		$idMateria = "NULL";
		$sql = "SELECT materia FROM ProyectoLey_tmp WHERE nro_boletin='".$nroBoletin."'";
		$rs = mysql_query($sql, $cn);

		// Si existe obtiene el id
		if(!$rs){
			// Si no existen registros se busca en la tabla ProyectoLey_publicado_tmp
			$sql = "SELECT materia FROM ProyectoLey_publicado_tmp WHERE nro_boletin='".$nroBoletin."'";
			$rs = mysql_query($sql, $cn);
			
			if($rs){
				$fila = mysql_fetch_array($rs);
				$materia = trim($fila[0]);
			}
		}else{
			$fila = mysql_fetch_array($rs);
			$materia = trim($fila[0]);
		}
		
		if($materia){
			$sql = "SELECT id_materia FROM Materia WHERE nombre = '".$materia."'";
			$rs = mysql_query($sql, $cn);
			
			if($rs){
				$fila = mysql_fetch_array($rs);
				$idMateria = $fila[0];
			}
		}
		//echo "<br>Materia:".$materia." - id:".$idMateria."<br>";
		return $idMateria;
	}
	else return "NULL";
}
?>
