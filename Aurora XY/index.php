<?php include_once("cabecalho.php"); ?>

<!-- 
<a href="https://www.facebook.com/dialog/oauth?client_id=257704477729578&redirect_uri=http://www.idxweb.com.br/gabriel/index.php&scope=email,user_website,user_location">Entrar com Facebook</a> -->
<!--
<?php

@session_start();
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])){
 
  // Informe o seu App ID abaixo
  $appId = '257704477729578';
 
  // Digite o App Secret do seu aplicativo abaixo:
  $appSecret = '0f37743f0e5320db7a2f35a07071deba';
 
  // Url informada no campo "Site URL"
  $redirectUri = urlencode('http://www.idxweb.com.br/gabriel/');
 
  // Obtém o código da query string
  $code = $_GET['code'];
 
  // Monta a url para obter o token de acesso e assim obter os dados do usuário
  $token_url = "https://graph.facebook.com/oauth/access_token?"."client_id=".$appId."&redirect_uri=".$redirectUri. "&client_secret=" . $appSecret."&code=".$code;
 
  //pega os dados
  $response = @file_get_contents($token_url);
  if($response){
    $params = null;
    parse_str($response, $params);
    if(isset($params['access_token']) && $params['access_token']){
      $graph_url = "https://graph.facebook.com/me?access_token=".$params['access_token'];
      $user = json_decode(file_get_contents($graph_url));
 
    // nesse IF verificamos se veio os dados corretamente
      if(isset($user->email) && $user->email){
 
    /*
    *Apartir daqui, você já tem acesso aos dados usuario, podendo armazená-los
    *em sessão, cookie ou já pode inserir em seu banco de dados para efetuar
    *autenticação.
    *No meu caso, solicitei todos os dados abaixo e guardei em sessões.
    */
 
        $_SESSION['email'] = $user->email;
        $_SESSION['nome'] = $user->name;
        $_SESSION['localizacao'] = $user->location->name;
        $_SESSION['uid_facebook'] = $user->id;
        $_SESSION['user_facebook'] = $user->username;
        $_SESSION['link_facebook'] = $user->link;
      }
    }else{
      echo "Erro de conexão com Facebook";
      exit(0);
    }
 
  }else{
    echo "Erro de conexão com Facebook";
    exit(0);
  }
}else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])){
  echo 'Permissão não concedida';
}

?>
-->

<?php
    // Verifica o tipo de requisição e se tem a variável 'code' na url
    if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code'])){

// Informe o id da app
       $appId = '257704477729578';
// Senha da app
       $appSecret = '0f37743f0e5320db7a2f35a07071deba';
// Url informada no campo "Site URL"
       $redirectUri = urlencode('http://www.idxweb.com.br/gabriel/');
// Obtém o código da query string
       $code = $_GET['code'];

// Monta a url para obter o token de acesso
       $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $appId . "&redirect_uri=" . $redirectUri
       . "&client_secret=" . $appSecret . "&code=" . $code;
       
       // Requisita token de acesso
       $response = @file_get_contents($token_url);
       
       if($response){
           $params = null;
           parse_str($response, $params);
           
           // Se veio o token de acesso
           if(isset($params['access_token']) && $params['access_token']){
             $graph_url = "https://graph.facebook.com/me?access_token="
             . $params['access_token'];
      
             // Obtém dados através do token de acesso
             $user = json_decode(file_get_contents($graph_url));
             
             // Se obteve os dados necessários
             if(isset($user->email) && $user->email){
               
               /*
* Autenticação feita com sucesso.
* Loga usuário na sessão. Substitua as linhas abaixo pelo seu código de registro de usuários logados
*/
               $_SESSION['user_data']['email'] = $user->email;
               $_SESSION['user_data']['name'] = $user->name;
               
               /*
* Aqui você pode adicionar um código que cadastra o email do usuário no banco de dados
* A cada requisição feita em páginas de área restrita você verifica se o email
* que está na sessão é um email cadastrado no banco
*/
             }
           }else{
             $_SESSION['fb_login_error'] = 'Falha ao logar no Facebook';
           }
       }else{
           $_SESSION['fb_login_error'] = 'Falha ao logar no Facebook';
       }

    }else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['error'])){
        $_SESSION['fb_login_error'] = 'Permissão não concedida';
    }
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"/>
<title>Login com Facebook</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
<body>
<h1>Login com Facebook</h1>
<?php if(isset($_SESSION['fb_login_error']) && $_SESSION['fb_login_error']): ?>
<p class="message"><?php echo $_SESSION['fb_login_error'] ?></p>
<?php unset($_SESSION['fb_login_error']); endif; ?>
<?php if(isset($_SESSION['user_data']) && !empty($_SESSION['user_data'])): ?>
<div id="facebook-data">
<p>
<strong>Usuário logado com sucesso!</strong>
</p>
<p>
<strong>Nome: </strong><?php echo $_SESSION['user_data']['name'] ?>
</p>
<p>
<strong>E-mail: </strong><?php echo $_SESSION['user_data']['email'] ?>
</p>
</div>
<?php else: ?>
<a href="https://www.facebook.com/dialog/oauth?client_id=257704477729578&scope=email&redirect_uri=<?php echo urlencode('http://www.idxweb.com.br/gabriel/') ?>">Entrar com Facebook</a>
<?php endif; ?>
<a class="fixed" href="http://www.idxweb.com.br/gabriel/" target="_blank">Blog Glauco Custódio - http://blog.glaucocustodio.com</a>
</body>
</html>

<?php include_once("rodape.php"); ?>