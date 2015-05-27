<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
}
?>

<!DOCTYPE html>
<head>

    <!-- Standard Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properities -->
    <title>OTES02 - Organize seu portfolio</title>

    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.12.2/semantic.min.css">
    <link rel='stylesheet' type="text/css" href="//fonts.googleapis.com/css?family=Raleway:400,300,700">
    <link rel="stylesheet" type="text/css" href="br-content/css/home.css">
    <link rel="stylesheet" type="text/css" href="br-content/css/signup.css">

</head>

<body>
    <header>
        <div class="ui small image">
            <img src="/images/wireframe/image.png">
        </div>
        <h3>Blackroom</h3>
    </header>

    <article class="ui two column divided grid">

        <section class="column">

            <h4 class="ui header center aligned">Vantagens do Blackroom</h4>

        </section>

        <section class="column">
            <form method="post" action="register.php" name="registerform" class="ui form">

                <h4 class="ui header center aligned">Cadastre-se</h4>

                <div class="required field">
                    <label for="login_input_name">meu nome</label>
                    <input id="login_input_name" class="name_input" type="text" name="user_name" placeholder="Meu nome completo" required />
                </div>

                <div class="required field">
                    <label for="login_input_email">meu email</label>
                    <div class="ui icon input">
                        <input id="login_input_email" class="login_input" type="email" name="user_email" placeholder="Digite um email válido" required />
                        <i class="user icon"></i>
                    </div>
                </div>

                <div class="required field">
                    <label for="login_input_password_new">minha senha</label>
                    <div class="ui icon input">
                        <input id="login_input_password_new" class="login_input" type="password" name="user_password" pattern=".{8,}" placeholder="Sua senha deve ter pelo menos 8 caracteres" required autocomplete="off" />
                        <i class="lock icon"></i>
                    </div>
                </div>

                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="radio" name="terms">
                        <label>Eu aceito os <a href="#"><b>Termos de Serviço</b></a>.</label>
                    </div>
                </div>
                
                <input type="submit" class="ui submit positive button" name="register" value="Criar minha conta"/>

            </form>
        </section>

        <!-- backlink -->
        <a href="index.php">Ainda não tenho certeza, quero voltar!</a>
    </article>

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.12.2/semantic.min.js"></script>
    <script type="text/javascript" src="br-content/js/defaults.js"></script>

</body>
</html>