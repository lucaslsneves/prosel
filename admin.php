
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control-panel Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
</head>

<body>
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
  <script src="admin-script.js"></script>
</body>
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

</html>