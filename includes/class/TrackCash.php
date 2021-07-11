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
	private static $dadosRecebidos = false;

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
						// Dados validados, agora vamos tratar os dados para inserção no DB
						if(self::tratarDados() === true){
							//Até aqui conseguimos saber que o usuário é honesto e está bem intensionado, MAS, vamos validar o dado para ver se não foi inserido dados maliciosos, scripts, sql inject.
						}
					}else{ // Se algum dado errado vamos retornar false.
						return false;
					}


					//dump($DB);



				}else{
					// Se chegamos aqui é porque foi encontrado algum dado extra passado por algum invasor, nestes caso para encaminhar para a página principal.
					self::vazaPilantra();
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
						self::setWarning('Por favor confira seu CPF/CNPJ.');
						return false;
					}
					// Setamos no parâmetro $dados
					self::$dadosRecebidos[$k] = $v;
				break;				
				case $k === 'nome':
					//Existem nomes pequenos como ED. Se menor que este é porque pode estar errado
					if(strlen($v) < 2){
						self::setWarning('Por favor confira o seu nome, pode estar errado.');
						return false;
					}	
					self::$dadosRecebidos[$k] = $v;			
				break;			
				case 'email':
					if(!filter_var($v, FILTER_VALIDATE_EMAIL)){
					    self::setWarning('Por favor confira seu E-mail.');
						return false;
					}
					self::$dadosRecebidos[$k] = $v;
				break;				
				case 'telefone':
					// Removeremos todos os caracteres especiais
					$n = preg_replace('/[^0-9]/', '', $v);
					// A quantidade de caracteres mínimas de um telefone fixo é de 10 dígitos. Menor que isso está errado.
					if(strlen($n) < 10){
						self::setWarning('Por favor verifique seu número de telefone');
						return false;
					}
					self::$dadosRecebidos[$k] = $v;
				break;				
				case 'password':
					// Como a validação do campo de senha está no Front-end através do campo do tipo password e regex pattern=".{8,}"
					// Não vamos setar a senha, vamos fazer isso na senha após a verificação abaixo
				break;				
				case 'passwordVerify':
					if($dados['password'] !== $dados[$k]){
						self::setWarning('Por favor verifique sua senha e digite ela igualmente nos dois campos.');
						return false;
					}
					self::$dadosRecebidos[$k] = $v;
				break;
				case 'cadastrar':
					if($v !== 'true'){ 
						// Não é esperado que um usuário bem intencionado mude este campo, pois está oculto. Se tiver um dado diferente deste é porque estão tentando fraudar o formulário, e para evitar um retorno positivo para o fraudador não vamos exibir qualquer erro, apenas direciona-lo novamente para a página de cadastro.
						return false;
					}
				break;
			}
		}
		// Chegamos até aqui é porque está tudo dentro do esperado.
		return true;
	}
	private static function tratarDados(){

		foreach (self::$dadosRecebidos as $k => $v){
			// Já vamos remover tags HTML.
			self::$dadosRecebidos[$k] = strip_tags($v);
			//Vamos formatar o nome para evitar JoAO FRANcisCo
			if($k === 'nome'){
				self::$dadosRecebidos[$k] = filtroNomeProprio($v); // Função está em includes/functions.php
			}
		}
		// Filtros aplicados em cada dado.
		$filtros = [
			'cpfCnpj' 				=> FILTER_DEFAULT,
			'nome' 						=> 
				[	'filter' => FILTER_SANITIZE_STRING|FILTER_SANITIZE_SPECIAL_CHARS ],
			'email' 					=> FILTER_SANITIZE_EMAIL,
			'telefone' 				=> FILTER_DEFAULT,
			'passwordVerify' 	=> FILTER_DEFAULT
		];
		// Aplica dada filtro nos valores do Array self::$dadosRecebidos;
		$filtrados = filter_var_array(self::$dadosRecebidos, $filtros);


		dump($filtrados);
		//dump(self::$dadosRecebidos);
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
	protected static function vazaPilantra(){
		self::setWarning('REDIRECIONADO POR TENTATIVA DE FRAUDE.');
		header('Location: cadastro.php');
		exit();
	}
}
?>