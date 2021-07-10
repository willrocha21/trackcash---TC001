<?php

/**
 * 
 */
class TrackCash
{
	
	private static $parametrosValide = [
		'cpfCnpj',
		'nome',
		'email',
		'token',
		'telefone',
		'password',
		'passwordVerify',
		'cadastrar'
	];

	public static function Cadastrar($action, array $dados){
		global $DB; // Importa a global $DB.

		//Como o segundo parametro da classe já tem a flag Array, caso os dados passados não sejam neste formato um erro será gerado, ñ requerendo que seja verificado a tipagem deste dado.

		//Switch para dar rota a função
		switch ($action) {
			case 'NovoUsuario': // Cadastro de novo usuário
				
				// Vamos validar os dados para ver se é o esperado.
				if(self::validarParametros($dados) === true){
					// Vamos validar a qualidade dos dados.
					if(self::validarDados($dados) === true){
						dump('Dados validados!');
					}else{ // Se algum dado errado vamos retornar false.
						return false;
					}


					//dump($DB);



				}else{
					// Se chegamos aqui é porque foi encontrado algum dado extra passado por algum invasor, nestes caso para encaminhar para a página principal.
					header('Location:cadastro.php');
					exit();
				}


			break;
			case 'RemoveUsuario': // Remoção de usuários
				// code...
			break;
			case 'EditarUsuario': // Edição de dados de usuários
				// code...
			break;			
			default: // Caso não tenha rota definida ou fora do esperado já retorna false.
				return false;
			break;
		}
		return $dados;
	}
	private static function validarParametros(array $dados){
		foreach ($dados as $k => $v){
			// Se encontrar algum parâmetro fora do esperado retorna false. Houve tentativa de inserção de dados extras no formulário.
			if(!in_array($k, self::$parametrosValide, true)){
				return false;
			}			
		}
		// Tudo conforme o esperado.
		return true;
	}
	private static function validarDados(array $dados){
		foreach ($dados as $k => $v) {
			switch ($k) {
				case $k === 'cpfCnpj':
					// Removeremos todos os caracteres especiais
					$n = preg_replace('/[^0-9]/', '', $v);
					// Se o cliente tiver digitado 1 dígito menor que um CPF padrão vamos emitir um aviso e não prosseguir com o script
					if(strlen($n) < 11){
					dump(strlen($n));
						self::setWarning('Por favor confira seu CPF/CNPJ.');
						return false;
					}
				break;				
				case $k === 'nome':
					//Existem nomes pequenos como ED. Se menor que este é porque pode estar errado
					if(strlen($v) < 2){
						self::setWarning('Por favor confira o seu nome, pode estar errado.');
						return false;
					}				
				break;			
				case 'email':
					if (!filter_var($v, FILTER_VALIDATE_EMAIL)) {
					    self::setWarning('Por favor confira seu E-mail.');
						return false;
					}
				break;				
				case 'telefone':
					// Removeremos todos os caracteres especiais
					$n = preg_replace('/[^0-9]/', '', $v);
					// A quantidade de caracteres mínimas de um telefone fixo é de 10 dígitos. Menor que isso está errado.
					if(strlen($n) < 10){
						self::setWarning('Por favor verifique seu número de telefone');
						return false;
					}
				break;				
				case 'password':
					// code...
				break;				
				case 'passwordVerify':
					// code...
				break;
				case 'cadastrar':
					// code...
				break;				
				
				default:
					// code...
					break;
			}
		}
		return true;
	}
	private static function setWarning($m){
		if($m === false) unset($_SESSION['warning']);
		$_SESSION['warning'] = $m;
	}
	public static function getWarning(){
		if(isset($_SESSION['warning'])){
			return $_SESSION['warning'];
		}
		return false;
	}
}


?>