<?php
//Vamos iniciar a sessão de forma simples.
	session_start();
//Exibir todos os erros do PHP
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
//Incluir o composer apenas para algumas ferramentas do symfony
	include_once('vendor/autoload.php');
//Incluir funções do projeto
	include_once('functions.php');

//Conexões com o DB após submeter formulário, apenas via POST.
if($_SERVER["REQUEST_METHOD"] === "POST"){
	//Inclue conexão DB pela class PDO
	include_once('db.php');
	//Produz uma variável global para todo o projeto
	global $DB;
	//Instancia a classe de Conexao com o servidor.
	$DB = Conexao::getInstance();
	dump($DB);

}else{ //Outros métodos indesejados vão ser redirecionado para a página de cadastro novamente, mas não vamos exibir erros para o invasor não ter retorno positivo de qualquer tentativa
	header('Location:cadastro.php');
	exit();
}
?>