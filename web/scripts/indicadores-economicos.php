<?php
/**
 * @package Indicadores Economicos
 * @author Andres Molina
 * @version 1.0
 */
/*
Plugin Name: Indicadores Economicos
Plugin URI: http://andresmolina.net/
Description:Muestra los Indicadores Economicos para Chile
Author: Andres Molina
Version: 1.0
Author URI: http://andresmolina.net/
*/
function getData($tipo,$limite, $fin,$fuente)
{
	$domain = strstr($fuente, $limite);
	$domain = $domain;
	switch ($tipo)
	{
		case "fecha":
			$domain=split('\n',$domain);
       			return strip_tags(str_replace("al ","",str_replace($fin,"",$domain[0])));
		        break;
		case "uf":
			$domain=strip_tags($domain);
			$domain=split('\n',$domain);
        		return str_replace(array("\r\n", "\n", "\r", "\t","&","UF"," ","$"),"",$domain[0]);
        		break;
		case "utm":
			$domain=strip_tags($domain);
			$domain=split('\n',$domain);
			return str_replace(array("\r\n", "\n", "\r", "\t","&","UTM"," ","$"),"",$domain[0]);
        		break;
		case "dolar":
			$domain=strip_tags($domain);
			$domain=split('\n',$domain);
			return str_replace(array("\r\n", "\n", "\r", "\t","&","lar Observado"," ","$"),"",$domain[0]);
			break;
	}
}
function buildWidget()
{
    $url="http://camara.cl/trabajamos/sala_votaciones.aspx";
	$fuente  = file_get_contents($url);
        //ctl00$mainPlaceHolder$ddlBuscarPor
        $actual='action="sala_votaciones.aspx"';
        $nueo='action="#"';
        $fuente=str_replace('action="sala_votaciones.aspx"','action="'.$url.'"',$fuente);
        $f=explode('name="ctl00$mainPlaceHolder$txtFecha1"',$fuente);
        echo "<pre>";
        print_r($f);
        die("</pre>");
        echo $fuente;

	$fecha = getData('fecha','al ', ")</fo",$fuente);
	$uf= getData('uf','UF</a></font></td>', "z",$fuente);
	$utm= getData('utm','UTM</a></font></td>', "z",$fuente);
	$dolar= getData('dolar','lar Observado</a></font></td>', "z",$fuente);
 
	echo '
		<div class="am_wpie" style="float:right">
		<dl>
			<dd class="today">
				<span class="condition">Indicadores</span>
				<span class="temperature">Al: '.$fecha.'</span>
			</dd>
			<dd class="today"  style="height:20px;">
				<span class="condition">UF: '.$uf.'</span>
			</dd>
			<dd class="today"  style="height:20px;">
				<span class="temperature">UTM: '.$utm.'</span>
			</dd>
			<dd class="today"  style="height:20px;">
				<span class="condition">Dolar: '.$dolar.'</span>
				 
			</dd>
		';	
		
		 
	echo '
		</dl>
		</div>
		<div style="clear: both;"></div>
		';
}
if($_GET['mod']==1){
    /*echo "<pre>";
    print_r($_POST);
    die("</pre>");*/
}
echo buildWidget();
?>
