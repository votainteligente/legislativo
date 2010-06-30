<?php
include('simple_html_dom.php');
include('log.php');

//BBDD votainteligente.cl
$cn = mysql_connect("localhost", "root", "Apollo13");
$db = mysql_select_db("vota2", $cn);

//descargar lista autores 
$autores = 'http://sil.senado.cl/cgi-bin/sil_porautor.pl?1,';
$html = file_get_html($autores);

// inicializar archivo de logging
$log = new Logging();

//sacar Nombre,Cargo,Periodos
$i=0;
foreach($html->find('td[class="azu"]') as $e){
	if ($i==0){
		//Nombre
		$nombre = explode(", ",$e->plaintext);
		$nombres = trim($nombre[1]);
		$apellidos = trim($nombre[0]);
	}
	else if ($i==1){
		//Cargo 
		$cargo = trim($e->plaintext);
	}
	else if ($i==2){
		//Periodos
		$periodos = trim($e->plaintext);
		$i=-1;
		
		//guardar datos en BBDD
		$sql = "INSERT INTO Autor (nombre, apellidos, cargo, periodos, created_at, updated_at) VALUES ('".$nombres."', '".$apellidos."',  '".$cargo."', '".$periodos."', '".date("Y-m-d H:m:s")."', '".date("Y-m-d H:m:s")."')";
		mysql_query($sql, $cn);
		$idAutor = mysql_insert_id($cn);
		echo $idAutor.'|'.$nombres.' '.$apellidos.'|'.$cargo.'|'.$periodo.'<br>';
		$log->lwrite($idAutor.'|'.$nombres.' '.$apellidos.'|'.$cargo.'|'.$periodo);
	}
	$i++;
}

?>
