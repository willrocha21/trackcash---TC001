<?php

/**
 * 
 */
class TrackCash
{
	// Parâmetros esperados no formulário de cadastro teste
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
	// Atributo para os dados recebidos do formulário que serão filtrados ao longo da classe.
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
						// Dados validados, agora vamos tratar os dados para inserção no DB
						if(self::tratarDados() === true){
							//Até aqui conseguimos saber que o usuário é honesto e está bem intensionado, MAS, vamos validar o dado para ver se não foi inserido dados maliciosos, scripts, sql inject.

							// Vamos verificar se o cliente já não existe na base de dados.
							//$s = $DB->query("SHOW COLUMNS FROM usuarios");
							//$r = $s->fetchAll(PDO::FETCH_OBJ);

							if(self::clienteExiste(self::$dadosRecebidos['email']) === false){
								// Ufa! Enfim vamos cadastrar este bendito!
								
								if(self::cadastrarCliente() === true){
									// Cliente cadastrado com sucesso!
									return true;
								}else{
									self::setWarning('Alguma falha ocorreu no processamento de seu cadastro. Entre em contato com nossa equipe.');
								}
								
							}else{ // Cliente já existe na base, então vamos encaminhar ele para o login.
								self::setWarning('Cliente já cadastrado em nossa empresa. <a href="login.php">Clique aqui</a> para logar.');
							}							
						}
					}else{ // Se algum dado errado vamos retornar false.
						return false;
					}
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
	protected static function cadastrarCliente(){
		global $DB;
		try{
			$sql = "INSERT INTO usuarios(nome,email,telefone,senha,cpf) VALUES (:nome,:email,:telefone,:senha,:cpf);";
			$q = $DB->prepare($sql);
			$i = $q->execute(
				array(
					':nome' => self::$dadosRecebidos['nome'],					
					':email' => self::$dadosRecebidos['email'],					
					':telefone' => self::$dadosRecebidos['telefone'],					
					':senha' => self::$dadosRecebidos['passwordVerify'],					
					':cpf' => self::$dadosRecebidos['cpfCnpj']
				)
			);
		}catch(PDOException $e){
			dump($e->getMessage());
		}
		
		if($q->rowCount() > 0){
			return true;
		}
		return false;
	}
	protected static function clienteExiste($email){
		global $DB;
		$sql = "SELECT u.email FROM usuarios u WHERE u.email = :email;";
		$q = $DB->prepare($sql);
		$q->bindParam(':email',$email);
		$q->execute();
		if($q->rowCount() > 0){ //Cliente já existe 
			return true; 
		}
		return false; // Cliente não existe, porque a contagem de linhas até aqui foi 0.
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
			//Vamos formatar o nome para evitar JoAO FRANcisCo ou emails com letras maíusculas
			if($k === 'passwordVerify'){
				self::$dadosRecebidos[$k] = encriptarSenhas($v);
			}
		}
		self::$dadosRecebidos['nome'] = filtroNomeProprio(self::$dadosRecebidos['nome']); // Função está em includes/functions.php

		//não vamos filtrar o email nos casos de emails digitados como em maiúsculo ou qualquer outra deformidade dos dados pois a validação feita pelo HTML5 já é eficaz.
		
		// Filtros aplicados em cada dado.
		$filtros = [
			'cpfCnpj' 				=> ['filter' => FILTER_SANITIZE_STRING|FILTER_SANITIZE_SPECIAL_CHARS],
			'nome' 						=> ['filter' => FILTER_SANITIZE_STRING|FILTER_SANITIZE_SPECIAL_CHARS],
			'email' 					=> FILTER_SANITIZE_EMAIL,
			'telefone' 				=> FILTER_DEFAULT,
			'passwordVerify' 	=> FILTER_DEFAULT
		];

		// Aplica dada filtro nos valores do Array self::$dadosRecebidos;
		$filtrados = filter_var_array(self::$dadosRecebidos, $filtros);
		
		//Após análise do banco de dados o campo que receberá este dado tem limitação de dados e foi pensado para comportar apenas números, então vamos filtrar o dado para permitir apenas números após a filtragem em massa, assim comandos perdidos que utilizam números não será desmontada.
		self::$dadosRecebidos['cpfCnpj'] = preg_replace('/[^0-9]/','',self::$dadosRecebidos['cpfCnpj']);

		// Tudo limpo vamos setar novamente o este atributo para ser utilizado na inserção do DB.
		self::$dadosRecebidos = $filtrados;
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
	protected static function vazaPilantra(){
		self::setWarning('REDIRECIONADO POR TENTATIVA DE FRAUDE.');
		header('Location: cadastro.php');
		exit();
	}
}
?>