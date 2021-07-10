<?php
if(isset($DB)){
	//Elimina a instancia do banco de dados.
	unset($DB);
}
if(isset($_SESSION['warning'])){
	unset($_SESSION['warning']);
}
?>
