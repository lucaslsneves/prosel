<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="assets/favicon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
        }

        body {
            color: rgba(255, 255, 255, 0.92);
            font-family: 'Inter', sans-serif;
            background-color: #1A202C;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .container {
            display: flex;
            flex-direction: column;
            width: 400px;

        }

        .container .form {
            padding: 40px;
            border-radius: 16px;
            width: 400px;
           /* border: 1px solid #E2E8F0; */
            background-color: #2D3748;
            display: flex;
            flex-direction: column;
        }

        .container header {
            display: flex;
            justify-content: center;
            margin-bottom: 48px;
        }

        .container header h1 {
            font-weight: 900;
            font-size: 24px;
            margin-left: 12px;
        }

        .container h2 {
            text-align: center;
            font-weight: 800;
            font-size: 36px;
            margin-bottom: 28px;
        }

        input {
            padding: 8px;
            border: 1px solid rgba(255, 255, 255, 0.20);
            border-radius: 3px;
            font-size: 15px;
           color:inherit;
            height: 44px;
            padding: 0 15px;
            border-radius: 8px;
            transition: 200ms;
            background:inherit;
        }
     
        input:focus {
            outline: none !important;
            border: 2px solid #4299E1;
        }

        .input-wrapper {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .input-wrapper label {
            font-size: 16px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        button {
            padding: 16px 0;
            border: none;
            font-weight: 600;
            background-color: #4299E1;
            color: #fff;
            border-radius: 8px;
            cursor: pointer;
            transition: 200ms;
            font-size: 16px;
        }

        button:hover {
            background-color: #3182CE;
        }

      

        button:disabled {
            cursor: not-allowed;
            background-color: #A0AEC0;
        }
        button:disabled:hover {
            background-color: #A0AEC0;

        }
 
        #error {
            display: none;
            margin-bottom: 20px;
            color: #FC8181;
            font-weight: 500;
        }
    </style>
</head>

<body>

    <div class="container">
        <header style="display:flex;align-items:center;">
            <img src="assets/favicon.png" style="width:30px;" />
            <h1>Prosel</h1>
        </header>
        <h2>Painel de Controle</h2>
      
        <form class="form" method="POST" action="auth.php">
            <div class="input-wrapper">
                <label for="login">Usuário</label>
                <input required id="login" name="login" />
            </div>
            <div class="input-wrapper">
                <label for="senha">Senha</label>
                <input required type="password"  id="senha" name="senha" />
            </div>
            <p id="error">oi</p>
            <button id="button" type="submit">Entrar</button>
        </form>
    </div>
    <script src="admin-script.js"></script>

    <!-- 
    <div class="app">
   
        <div class="bg"></div>

        <form method="POST" action="auth.php">
            <header>
                <img src="assets/SVG/adminlogo.svg" style="width: 250px; height: 120px;">

            </header>

            <div class="inputs">
                <input type="text" id="login" name="login" placeholder="Usuário">
                <input type="password" id="senha" name="senha" placeholder="Senha">

                <p id="error"></p>
            </div>
            <input type="submit">
        </form>
    </div>
    -->
    <script src="admin-script.js"></script>
</body>
<!--
<style>
    * {
        margin: 0;
        padding: 0;
        text-decoration: none;
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        background: #f6f6f6;
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
        height: 100vh;
        margin: auto;
        display: flex;
    }

    img {
        max-width: 100%;
    }

    .app {
        background-color: #fff;
        width: 330px;
        height: 570px;
        margin: 2em auto;
        border-radius: 5px;
        padding: 1em;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 31px -2px rgba(0, 0, 0, .3);
    }

    a {
        text-decoration: none;
        color: #257aa6;
    }

    p {
        font-size: 14px;
    color: #fff;
    font-weight: 700;
    line-height: 2;
    }

    .light {
        text-align: right;
        color: #fff;
    }

    .light a {
        color: #fff;
    }

    .bg {
        width: 400px;
        height: 550px;
        background: #057032;
        position: absolute;
        top: -5em;
        left: 0;
        right: 0;
        margin: auto;
        background-image: url("background.jpg");
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        clip-path: ellipse(69% 46% at 48% 46%);
    }

    form {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        text-align: center;
        padding: 2em;
    }

    header {
        width: 220px;
        height: 220px;
        margin: 1em auto;
    }

    form input {
        width: 100%;
        padding: 13px 15px;
        margin: 0.7em auto;
        border-radius: 100px;
        border: none;
        background: rgb(255, 255, 255, 0.3);
        font-family: 'Poppins', sans-serif;
        outline: none;
        color: #fff;
    }

    input::placeholder {
        color: #fff;
        font-size: 13px;
    }

    .inputs {
        margin-top: -4em;
    }

    footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2em;
        text-align: center;
    }

    button,
    input[type="submit"] {
        width: 80%;
        padding: 13px 15px;
        border-radius: 100px;
        border: none;
        background: #057032;
        font-family: 'Poppins', sans-serif;
        outline: none;
        color: #fff;
        cursor: pointer;
        position: absolute;
        left: 30px;
        bottom: -100px;
    }

    @media screen and (max-width: 640px) {
        .app {
            width: 100%;
            height: 100vh;
            border-radius: 0;
        }

        .bg {
            top: -7em;
            width: 450px;
            height: 95vh;
        }

        header {
            width: 90%;
            height: 250px;
        }

        .inputs {
            margin: 0;
        }

        input,
        button {
            padding: 18px 15px;
        }
    }
</style>
-->

</html>