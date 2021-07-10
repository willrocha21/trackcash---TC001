<?php 
	include_once('includes/header.php');
	global $nameForm;
	$nameForm = 'cadastroClientes';
	$token = gerarTokenParaForms($nameForm);
	dump($token);
?>
<!DOCTYPE html>
<html lang="pt">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Cadastro TrackCash</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet"> 
		<link rel="stylesheet" href="css/estilo.css">
		<script src="js/j.js"></script>	
	</head>
	<body>
		<div class="container-fluid padding-main">
			<div class="row">
				<div class="callFrame col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<img class="callFrameImg" src="/images/scott-graham-5fNmWej4tAA-unsplash1.jpg" alt="">
					<h1 class="display-4">Muito mais que um conciliador financeiro!</h1>
					<p class="lead">A melhor ferramenta no mercado e a única com processo automatizado, que compara as informações entre Plataformas, Marketplaces, Transportadoras, Meios de Pagamento e Instituições Financeiras!</p>
				</div>
				<div class="actionForm grayBg col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="row">
						<div class="centerElement col-xs-12 col-sm-12 col-md-6 col-lg-7">
							<figure class="figure">
								<img src="/images/logo-trackcash.png" alt="">
							</figure>
						</div>
					</div>
					<div class="row">
						<div class="centerElement col-xs-12 col-sm-12 col-md-6 col-lg-7">
							<h2>Cadastre-se no TrackCash:</h2>
						</div>
					</div>	
					<div class="row">
						<div class="centerElement col-xs-12 col-sm-12 col-md-6 col-lg-7">
							<?php 
								//Vamos adicionar ao action a função htmlentities() recebendo a variável global PHP_SELF. Como o cadastro é aberto a qualquer usuário da internet, com esta função qualquer tentativa de ataque XSS é derrubada.
							?>
							<form name="<?php echo $nameForm;?>" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
							  <div class="form-group">
							    <input type="text" name="cpfCnpj" class="form-control" id="cpfCnpj" placeholder="CPF/CNPJ" onkeyup="mascarar(this);">
							  </div>
							  <div class="form-group">
							    <input type="text" name="nome" class="form-control" id="nome" placeholder="Nome">
							  </div>							  								
							  <div class="form-group">
							    <input type="email" class="form-control" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" placeholder="Email">
							    <input name="token" type="hidden" value="<?php echo $token;?>">
							  </div>
							  <div class="form-group">
							    <input type="tel" class="form-control" id="telefone" placeholder="Telefone" onkeyup="mascarar(this);">
							  </div>							  
							  <div class="form-group">
							    <input type="password" class="form-control" id="senha" placeholder="Senha" pattern=".{8,}" title="Para mais segurança digite no mínimo 8 caracteres">
							  </div>
							  <div class="form-group">
							    <input type="password" class="form-control" id="senhaConfirme" placeholder="Confirme sua senha">
							  </div>							  
							  <button type="submit" name="cadastrar" class="btn btn-primary w-100">Acessar o Sistema</button>
							</form>
						</div>
					</div>
					<div class="row">
						<div class="centerElement col-xs-12 col-sm-12 col-md-6 col-lg-7">
							<a href="login.php">Já possui uma conta? Faça login!</a>
						</div>
					</div>
				</div>				
			</div>
		</div>

	    <!-- Optional JavaScript -->
	    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	    <script>
	    	$(document).ready(function(){
	    		e = $.Event('keyup'); e.keyCode= 13;
	    		setTimeout(function(){
	    			$('#cpfCnpj').focus().val('<?php echo rand(10000000000,99999999999);?>');
	    			$('#cpfCnpj').trigger(e);
	    		},500);
	    		$('#telefone').val('(12) 9876-65412');
	    		$('#nome').val('Ludwig Van Beethoven');
	    		$('#email').val('lvb@compositor.com.br');
	    		$('#senha').val('123456789');
	    		$('#senhaConfirme').val($('#senha').val());
	    	});
	    </script>	
	</body>
<?php 
	include_once('includes/footer.php');
?>
</html>