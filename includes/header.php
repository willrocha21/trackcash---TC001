<?php
// Vamos iniciar a sessão de forma simples.
	session_start();
// Exibir todos os erros do PHP
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
// Incluir o composer apenas para algumas ferramentas do symfony
	include_once('vendor/autoload.php');
// Incluir funções do projeto
	include_once('functions.php');

	global $nameForm;
	$nameForm = 'cadastroClientes';

// Conexões com o DB após submeter formulário, apenas via POST.
if($_SERVER["REQUEST_METHOD"] === "POST"){

	// Antes de Instanciar o banco de dados para ter a certeza que a tokenização está de acordo.
	if(verifyFormToken($nameForm) === true){ //Função em /includes/functions.php
		// Inclue conexão DB pela class PDO
		include_once('db.php');
		// Produz uma variável global para todo o projeto
		global $DB;
		// Instancia a classe de Conexao com o servidor.
		$DB = Conexao::getInstance();
		// Inclue a Classe TrackCash com todas as funções para a aplicação.
		include_once('class/TrackCash.php');
		// Chama o método Cadastrar() sendo o primeiro parametro a rota dentro do método e o segundo com o array com os dados enviados.
		$cadastrar = TrackCash::Cadastrar('NovoUsuario',$_POST);
		
		//dump($cadastrar);

	}else{
		// Se chegamos até aqui é porque algo de errado foi feito no formulário para quebrar o token.
		// Neste caso podemos emitir um relatório de erro para salvar em um log, ou enviar todos os dados por e-mail para o administrador.
		// Para não dar retorno positivo para o invasor vamos apenas redirecionar para a página de cadastro.
		//header('Location:cadastro.php');
		//exit();
	}
}
?>