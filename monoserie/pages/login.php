<?php
    require_once '../template.php';

    ob_start();
?>
<style>
        
    /*login page*/
    .login-page-body {
        font-family: sans-serif;
        background-color: #141414;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .login-page-container {
        background-color: rgba(72, 60, 60, 0.75);
        border-radius: 4px;
        padding: 40px;
        width: 450px;
        max-width: 90%;
    }

    .login-page-logo {
        margin-bottom: 20px;
    }

    .login-page-logo img {
        height: 45px;
        display: block;
        margin: 0 auto;
    }

    .login-page-form-container h1 {
        color: #fff;
        font-size: 2em;
        margin-bottom: 20px;
        text-align: center;
    }

    .login-page-form {
        display: flex;
        flex-direction: column;
    }

    .form-group-login {
        margin-bottom: 15px;
    }

    .login-page-input {
        background-color: #333;
        border: none;
        border-radius: 4px;
        color: #fff;
        padding: 14px 20px;
        font-size: 1em;
        width: 100%;
        box-sizing: border-box;
    }

    .login-page-input::placeholder {
        color: #8c8c8c;
    }

    .login-page-button {
        background-color: #e50914;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 16px 20px;
        font-size: 1em;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .login-page-button:hover {
        background-color: #c40812;
    }

    .login-page-remember-me {
        display: flex;
        align-items: center;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    .login-page-remember-me input[type="checkbox"] {
        margin-right: 10px;
    }

    .login-page-remember-me label {
        font-size: 0.9em;
        color: #b3b3b3;
    }

    .login-page-help-links a {
        color: #b3b3b3;
        text-decoration: none;
        font-size: 0.9em;
    }

    .login-page-help-links a:hover {
        text-decoration: underline;
    }

    .login-page-signup-link {
        margin-top: 30px;
        color: #d0d0d0;
        font-size: 1em;
    }

    .login-page-signup-link a {
        color: #fff;
        text-decoration: none;
    }

    .login-page-signup-link a:hover {
        text-decoration: underline;
    }

    .body-login {
        padding: 20px 10%; 
        display: flex;
        flex-direction: column; 
        align-items: center; 
    }

</style>
<div class="body-login">
    <div class="login-page-container">
        <div class="login-page-form-container">
            <h1>Se connecter</h1>
            <form action="../admin/login.php" method="post" class="login-page-form">
                <div class="form-group-login">
                    <input type="text" id="login" name="email" class="login-page-input" placeholder="Email ou numéro de téléphone">
                </div>
                <div class="form-group-login">
                    <input type="password" id="password" name="motdepasse" class="login-page-input" placeholder="Mot de passe">
                </div>
                <button type="submit" class="login-page-button" >Se connecter</button>
                <div class="login-page-help-links">
                    <a href="">Besoin d'aide ?</a>
                </div>
            </form>
            <div class="login-page-signup-link">
                <p>Nouveau sur MonoSerie ? <a href="#">S'inscrire maintenant</a></p>
            </div>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
Template::render($content, 'MonoSerie - Se connecter');
?>