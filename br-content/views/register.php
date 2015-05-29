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
    <article>
        <section>

            <!-- register form -->
            <form class="ui form" method="post" action="register.php" name="registerform">

                <h4 class="ui header center aligned">Cadastre-se</h4>

                <div class="required field">
                    <label for="login_input_username">Meu nome</label>
                    <input id="login_input_username" class="login_input" type="text" name="user_name" placeholder="Meu nome completo" required />
                </div>

                <div class="required field">
                    <label for="login_input_email">Email</label>
                    <div class="ui icon input">
                        <input id="login_input_email" class="login_input" type="email" name="user_email" required />
                        <i class="user icon"></i>
                    </div>
                </div>

                <div class="required field">
                    <label for="login_input_password_new">Password (min. 6 characters)</label>
                    <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
                </div>

                <div class="required field">
                    <label for="login_input_password_repeat">Repeat password</label>
                    <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
                </div>

                <div class="field">
                    <div class="ui toggle checkbox">
                        <input type="radio" name="terms">
                        <label>Eu aceito os <a href="#"><b>Termos de Serviço</b></a>.</label>
                    </div>
                </div>

                <input type="submit" name="register" class="ui submit positive button" value="Criar minha conta" />

            </form>

            <!-- backlink -->
            <a href="index.php">Back to Login Page</a>

        </section>
    </article>

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.12.2/semantic.min.js"></script>
    <script type="text/javascript" src="br-content/js/defaults.js"></script>

</body>
</html>
