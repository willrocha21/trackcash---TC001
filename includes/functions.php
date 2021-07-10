<?php
function gerarTokenParaForms($formulario) {
  // Função para gerar um token para identificar unicamente cada formulário por usuário

  // gerar um token a partir de um valor único, transformado em Hash pela função md5(); Pode-se utilizar outras.
	$token = md5(uniqid(microtime(), true));  

	// Grave o token gerado na variável de sessão para compará-lo com o campo oculto quando o formulário for enviado via post
	$_SESSION[$formulario.'_token'] = $token; 
	
	return $token;
}
function verifyFormToken($formulario) {
  // Vamos verificar se existe a global de sessão. Se não existir é porque a sessão não foi iniciada ou quebrada pelo servidor.
  if(isset($_SESSION)){
    // Vamos verificar se existe o token para este formulário, Se não já retorna FALSE e o script não segue.
    if (!isset($_SESSION[$formulario.'_token'])) { 
      return false;
    }
  }
  
  // Vamos verificar se o formulário transmitiu o Token. Assim evitamos que algum usário malicioso venha excluir o <input> token do DOM.
  if(!isset($_POST['token'])){
    return false;
  }
  
  // Por fim, compara se há alguma diferença entre o Token na sessão e o transmitido.
  if ($_SESSION[$formulario.'_token'] !== $_POST['token']) {
    return false;
  }
  // Passado todos os passos retornamos TRUE como validação do formulário.
  return true;
}
?>