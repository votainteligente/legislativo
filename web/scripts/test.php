<?php
if(isset($_GET['f'])){
//proxy:
echo '<base href="http://camara.cl/trabajamos/sala_votaciones.aspx" />';
echo file_get_contents('http://camara.cl/trabajamos/sala_votaciones.aspx');
exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>test</title>
<script>
function reloading(){
    alert(document.getElementById('ctl00_mainPlaceHolder_ddlBuscarPor').value);
}
</script>
</head>

<body onload="reloading();">
<form method="post" name="aspnetForm" action="" target="ifra">
<input type="hidden" name="ctl00_mainPlaceHolder_ddlBuscarPor" value="fecha" />
<input type="hidden" name="ctl00$mainPlaceHolder$ddlBuscarPor" value="fecha" id="ctl00_mainPlaceHolder_ddlBuscarPor" />
<input type="hidden" name="ctl00_mainPlaceHolder_txtFecha1" value="19-05-2010" />
<input type="hidden" name="ctl00$mainPlaceHolder$txtFecha1" value="19-05-2010" />
<input type="hidden" name="ctl00_mainPlaceHolder_txtFecha2" value="19-05-2010" />
<input type="hidden" name="ctl00$mainPlaceHolder$txtFecha2" value="19-05-2010" />

<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKMTY2MTg2NzIxMQ9kFgJmD2QWAgIDD2QWAgIHD2QWBAIDD2QWAmYPZBYIAgEPEGRkFgFmZAIDDw8WAh4HVmlzaWJsZWdkZAIFDw8WAh8AaGQWAgIBDxBkZBYAZAIHDw8WAh8AaGRkAgcPDxYCHwBnZBYIAgEPDxYCHgRUZXh0BQExZGQCAw8PFgIfAQUBN2RkAgUPDxYCHwEFATdkZAIJDxYCHgtfIUl0ZW1Db3VudAIBFgJmD2QWCgIBD2QWAgIBDw8WAh4PQ29tbWFuZEFyZ3VtZW50BQEwZGQCAw8WAh8AZxYCZg8VAQExZAIFD2QWAgIBDw8WAh8DBQEwZBYCZg8VAQExZAIHD2QWAgIBDw8WAh8DBQEwZGQCCQ9kFgICAQ8PFgIfAwUBMGRkGAEFHl9fQ29udHJvbHNSZXF1aXJlUG9zdEJhY2tLZXlfXxYCBR9jdGwwMCRtYWluUGxhY2VIb2xkZXIkYnRuRmVjaGExBR9jdGwwMCRtYWluUGxhY2VIb2xkZXIkYnRuRmVjaGEy3sRI177T94KqQae5ePKDrMt7Lx8=" />


<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="/wEWDAL4/rSLBALUy+bBDQLe65DBBwLu6vbmAQKO7LW8CwKOsbrjBwKKwL62DQLAtfu3AQKwjrPBDAKlzN2iBwKByoyBCwKa8PjxD5HXRNGtuDESAqbijATC9XBefA5U" />

<input type="submit" name="ctl00$mainPlaceHolder$btnBuscar" value="Buscar" id="ctl00_mainPlaceHolder_btnBuscar" />

</form>
<div id="externa">

</div>
</body>
</html>