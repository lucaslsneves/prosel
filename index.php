<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>INTS - Documentos Admissionais</title>

    <link rel="stylesheet" href="styles2.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/favicon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://rawgit.com/RobinHerbots/Inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xregexp/2.0.0/xregexp-all-min.js"></script>
</head>

<body>
    <div class="bs-example">
        <div class="header" style=" height: 50px;">
            <img src="assets/svg/adminlogo.svg" class="col-3 ml-2 p-2" style="width: 90px; height: 50px; color: white">
        </div>

        <div class="container">
            <div class="steps">
                <div class="step active" id="step1">

                    <h2>1</h2>

                    <p>CPF</p>
                    <img src="assets/spinner.gif" />
                </div>
                <img src="assets/arrow-right-dark.svg" />
                <div class="step" id="step2">

                    <h2>2</h2>

                    <p>Campos obrigatórios</p>
                    <img src="assets/spinner.gif" />
                </div>
                <img src="assets/arrow-right-dark.svg" />
                <div class="step" id="step3">

                    <h2>3</h2>

                    <p>Campos obrigatórios</p>
                    <img src="assets/spinner.gif" />
                </div>
                <img src="assets/arrow-right-dark.svg" />
                <div class="step" id="step4">

                    <h2>4</h2>

                    <p>Campos obrigatórios</p>
                    <img src="assets/spinner.gif" />
                </div>
                <img src="assets/arrow-right-dark.svg" />
                <div class="step" id="step5">

                    <h2>5</h2>

                    <p>Campos obrigatórios</p>
                    <img src="assets/spinner.gif" />
                </div>
                <img src="assets/arrow-right-dark.svg" />
                <div class="step" id="step6">


                    <h2>6</h2>

                    <p>Campos Opcionais</p>
                    <img src="assets/spinner.gif" />
                </div>
                <img src="assets/arrow-right-dark.svg" />
                <div class="step" id="step7">

                    <h2>7</h2>

                    <p>Campos Opcionais</p>
                    <img src="assets/spinner.gif" />
                </div>
            </div>
            <div id="wrapperId" class="col-12 d-flex flex-direction-column justify-content-center">
                <h1>Documentos Admissionais</h1>
                <form id="form" name="cadastro">

                    <div class="own-form-group">
                        <div class="own-form-field" id="cpf-container">
                            <label for="cpf">CPF*</label>
                            <input type="tel" name="cpf" class="form-control" id="cpf" maxlength="15" placeholder="Digite seu CPF" required>
                            <strong class="is-invalid-cpf">CPF inválido</strong>
                            <p class="error"></p>
                        </div>
                    </div>
                    <p id="error"></p>
                    <div class="left">
                        <button type="submit" id="buttonId" class="submit-button">
                            <p>Enviar</p>
                            <img src="assets/arrow-right.svg" alt="">
                        </button>
                    </div>
                </form>
            </div>
        </div>



    </div>

    <script type="text/javascript" src="script.js"></script>
</body>

</html>