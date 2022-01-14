<?php
require "../check-session-user-prosel.php";
include '../connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$dados = null;
try {
    $cpf = $_SESSION['cpf'];
    $query = "
    SELECT nome_completo,sexo,prosel,cpf_dependentes,possui_dependentes,estado_civil,already_sent_all_docs,prosel,auth_users_prosel.funcao
FROM usuario_prosel
INNER JOIN auth_users_prosel on usuario_prosel.cpf = auth_users_prosel.cpf
 WHERE usuario_prosel.cpf = '$cpf'
    ";



    $dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    
    $inputsUserCanSendQuery = "SELECT name,description,usuario_prosel_id,type_file from inputs
    inner join inputs_user_can_send on inputs_user_can_send.input_id = inputs.id
    where usuario_prosel_id = " . $_SESSION['id'];

    $inputsUserCanSend = $mysqli->query($inputsUserCanSendQuery)->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
    exit;
}
?>
<?php if ($dados[0]['already_sent_all_docs'] == 1) { ?>
    <script>
        (() => {
            $('.steps').hide()
        })()
    </script>

    <?php if (empty($inputsUserCanSend)) { ?>
        <h2 style="text-align:center;width:80%;margin:0 auto; font-weight:500">Para atualizar as documentações enviadas favor entrar em contato com o Departamento Pessoal</h2>
    <?php } else { ?>
        <form id="form" style="padding-top:15px; border-top: 1px solid #dbdada;" class="own-form-group grid-2-2">
            <?php foreach ($inputsUserCanSend  as $input) { ?>
                <?php if ($input['type_file'] == 1) { ?>
                    <div class="own-form-field">
                        <label for="<?php echo $input['name'] ?>"><?php echo $input['description'] ?></label>
                        <div class="wrapper-input-file">
                            <input type="file" id="<?php echo $input['name'] ?>" class="real-file" name="<?php echo $input['name'] ?>" required />
                            <button type="button" class="custom-button">Escolher Arquivo</button>
                            <span class="custom-text">Nenhum arquivo selecionado</span>
                        </div>
                        <p class="error"></p>
                    </div>
                <?php } else {   ?>
                    <?php if ($input['name'] == 'nome_completo') { ?>
                        <div class="own-form-field">
                            <label for="nome_completo"> Nome Completo</label>
                            <input type="text" class="form-control" id="nome_completo" name="nome_completo" placeholder="Digite seu nome" maxlength="100" required>
                            <p class="error"></p>
                        </div>
                    <?php } else if ($input['name'] == 'estado_civil') { ?>
                        <div class="own-form-field">
                            <label for="estado_civil">Estado Civil *</label>
                            <select id="estado_civil" name="estado_civil" id="estado_civil" required>
                                <option selected></option>
                                <option value="Solteiro">Solteiro</option>
                                <option value="Casado">Casado</option>
                                <option value="Separado">Separado</option>
                                <option value="Divorciado">Divorciado</option>
                                <option value="Viúvo">Viúvo</option>
                            </select>
                            <p class="error"></p>
                        </div>
                    <?php } else if ($input['name'] == 'sexo') { ?>
                        <div class="own-form-field">
                            <label>Gênero</label>
                            <div class="flex">
                                <div class="flex">
                                    <input type="radio" id="male" name="sexo" value="M" checked="checked" required>
                                    <label for="male">Masculino</label>
                                </div>
                                <div class="flex">
                                    <input type="radio" id="female" name="sexo" value="F" required>
                                    <label for="female">Feminino</label>
                                </div>
                            </div>
                            <p class="error"></p>
                        </div>

                    <?php } else if ($input['name'] == 'possui_dependentes') { ?>
                        <div class="own-form-field">
                            <label for="possui_dependents">Possui dependentes de imposto de renda*</label>
                            <div class="flex">
                                <div class="flex">
                                    <input type="radio" id="yes" name="possui_dependents" value="S" checked="checked" required>
                                    <label for="yes">Sim</label>
                                </div>
                                <div class="flex">
                                    <input type="radio" id="no" name="possui_dependents" value="N" required>
                                    <label for="no">Não</label>
                                </div>
                            </div>
                            <p class="error"></p>
                        </div>
                    <?php } ?>
                <?php } ?>

            <?php } ?>
            <button type="submit" id="buttonId" class="submit-button">
                <p>Enviar</p>
                <img src="assets/arrow-right.svg" alt="">
            </button>
        </form>

        <script>
            $("form").submit(function(event) {
                document.getElementById("buttonId").querySelector("p").innerHTML = "Enviando..."
                document.getElementById("buttonId").querySelector("img").src = "assets/spinner2.gif"
                $("#buttonId").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "send-any-docs-controller.php",
                    data: new FormData(this),
                    cache: false,
                    dataType: 'json',
                    contentType: false,
                    processData: false
                }).done(function(data) {
                    const p = document.querySelector("#error");
                    if (!data.success) {
                        document.getElementById("buttonId").querySelector("p").innerHTML = "Enviar"
                        document.getElementById("buttonId").querySelector("img").src = "assets/arrow-right.svg"
                        p.innerText = 'Erro!'
                    } else {
                        $("#form").load('views/send-documents3-view.php', () => {
                            setStepButton("#step4");
                        });

                    }
                }).error(() => {
                    document.getElementById("buttonId").querySelector("p").innerHTML = "Enviar"
                    document.getElementById("buttonId").querySelector("img").src = "assets/arrow-right.svg"
                    $("#buttonId").attr("disabled", false);
                })
                event.preventDefault();

            });


            const realFileBtns = document.querySelectorAll(".real-file")
            const customBtns = document.querySelectorAll(".custom-button")
            const customTxts = document.querySelectorAll(".custom-text")

            realFileBtns.forEach((element, i) => {
                customBtns[i].addEventListener("click", function() {
                    realFileBtns[i].click();
                });

                realFileBtns[i].addEventListener("change", function(event) {
                    if (realFileBtns[i].value) {
                        /* const match = realFileBtns[i].value.match(
                           /[\/\\]([\w\d\s\.\-\(\)]+)$/
                         )[1] */
                        if (this.files[0].size > 16000000) {
                            alert("Só são aceitos arquivos até 15MB , selecione um arquivo menor!");
                            this.value = "";
                            return;
                        };

                        customTxts[i].innerHTML = "Arquivo selecionado";

                    } else {

                        customTxts[i].innerHTML = "Nenhum Arquivo Selecionado";
                    }
                    console.log(update);
                });

            });
        </script>
    <?php } ?>

<?php } else { ?>
    <div style="display:flex;">
        <h2 style="padding-right:15px;border-right:2px solid #dbdada; margin-bottom: 12px; font-weight: 400; font-size:16px">Processo Seletivo:
            <strong style="text-transform:uppercase;color:#2F855A;"><?php echo $dados[0]['prosel'] ?></strong>
        </h2>
        <h2 style="padding-left:15px;font-weight: 400; font-size:16px">Cargo:
            <strong style="text-transform:uppercase;color:#2F855A;"><?php echo $dados[0]['funcao'] ?></strong>
        </h2>
    </div>

    <?php
    if ($_SESSION['update']) {
    ?>
        <div style="padding-top:15px; border-top: 1px solid #dbdada;" class="own-form-group grid-2-2">
            <div class="own-form-field">
                <label for="nome"> Nome Completo *</label>
                <input type="text" class="form-control" id="nome" value="<?php print_r($dados[0]['nome_completo']) ?>" name="nome" placeholder="Digite seu nome" maxlength="100" required>
                <p class="error"></p>
            </div>

            <div class="own-form-field">
                <label>Gênero *</label>
                <div class="flex">

                    <?php if ($dados[0]['sexo'] == 'M') {  ?>
                        <div class="flex">
                            <input type="radio" id="male" name="gender" value="M" checked="checked" required>
                            <label for="male">Masculino</label>
                        </div>
                        <div class="flex">
                            <input type="radio" id="female" name="gender" value="F" required>
                            <label for="female">Feminino</label>
                        </div>
                    <?php } else { ?>
                        <div class="flex">
                            <input type="radio" id="male" name="gender" value="M" required>
                            <label for="male">Masculino</label>
                        </div>
                        <div class="flex">
                            <input type="radio" id="female" name="gender" checked="checked" value="F" required>
                            <label for="female">Feminino</label>
                        </div>
                    <?php } ?>
                </div>
                <p class="error"></p>
            </div>


            <?php if ($dados[0]['estado_civil'] == 'Solteiro') {  ?>
                <div class="own-form-field">
                    <label for="estado_civil">Estado Civil *</label>
                    <select id="estado_civil" name="estado_civil" id="estado_civil" required>
                        <option value="Solteiro" selected>Solteiro</option>
                        <option value="Casado">Casado</option>
                        <option value="Separado">Separado</option>
                        <option value="Divorciado">Divorciado</option>
                        <option value="Viúvo">Viúvo</option>
                    </select>
                    <p class="error"></p>
                </div>
            <?php } else if ($dados[0]['estado_civil'] == 'Casado') { ?>
                <div class="own-form-field">
                    <label for="estado_civil">Estado Civil *</label>
                    <select id="estado_civil" name="estado_civil" id="estado_civil" required>

                        <option value="Solteiro">Solteiro</option>
                        <option value="Casado" selected>Casado</option>
                        <option value="Separado">Separado</option>
                        <option value="Divorciado">Divorciado</option>
                        <option value="Viúvo">Viúvo</option>
                    </select>
                    <p class="error"></p>
                </div>

            <?php } else if ($dados[0]['estado_civil'] == 'Separado') { ?>
                <div class="own-form-field">
                    <label for="estado_civil">Estado Civil *</label>
                    <select id="estado_civil" name="estado_civil" id="estado_civil" required>
                        <option value="Solteiro">Solteiro</option>
                        <option value="Casado">Casado</option>
                        <option value="Separado" selected>Separado</option>
                        <option value="Divorciado">Divorciado</option>
                        <option value="Viúvo">Viúvo</option>
                    </select>
                    <p class="error"></p>
                </div>

            <?php } else if ($dados[0]['estado_civil'] == 'Divorciado') { ?>
                <div class="own-form-field">
                    <label for="estado_civil">Estado Civil *</label>
                    <select id="estado_civil" name="estado_civil" id="estado_civil" required>

                        <option value="Solteiro">Solteiro</option>
                        <option value="Casado">Casado</option>
                        <option value="Separado">Separado</option>
                        <option value="Divorciado" selected>Divorciado</option>
                        <option value="Viúvo">Viúvo</option>
                    </select>
                    <p class="error"></p>
                </div>

            <?php } else if ($dados[0]['estado_civil'] == 'Viúvo') { ?>
                <div class="own-form-field">
                    <label for="estado_civil">Estado Civil *</label>
                    <select id="estado_civil" name="estado_civil" id="estado_civil" required>

                        <option value="Solteiro">Solteiro</option>
                        <option value="Casado">Casado</option>
                        <option value="Separado">Separado</option>
                        <option value="Divorciado">Divorciado</option>
                        <option value="Viúvo" selected>Viúvo</option>
                    </select>
                    <p class="error"></p>
                </div>
            <?php } else { ?>
                <div class="own-form-field">
                    <label for="estado_civil">Estado Civil *</label>
                    <select id="estado_civil" name="estado_civil" id="estado_civil" required>
                        <option selected></option>
                        <option value="Solteiro">Solteiro</option>
                        <option value="Casado">Casado</option>
                        <option value="Separado">Separado</option>
                        <option value="Divorciado">Divorciado</option>
                        <option value="Viúvo">Viúvo</option>
                    </select>
                    <p class="error"></p>
                </div>
            <?php } ?>



            <div class="own-form-field">
                <label>Possui dependentes de imposto de renda ?*</label>
                <div class="flex">
                    <?php if ($dados[0]['possui_dependentes']) {  ?>
                        <div class="flex">
                            <input type="radio" id="yes" name="dependents" value="S" checked="checked" required>
                            <label for="yes">Sim</label>
                        </div>
                        <div class="flex">
                            <input type="radio" id="no" name="dependents" value="N" required>
                            <label for="no">Não</label>
                        </div>
                    <?php } else { ?>
                        <div class="flex">
                            <input type="radio" id="yes" name="dependents" value="S" required>
                            <label for="yes">Sim</label>
                        </div>
                        <div class="flex">
                            <input type="radio" id="no" name="dependents" checked="checked" value="N" required>
                            <label for="no">Não</label>
                        </div>
                    <?php } ?>
                </div>
                <p class="error"></p>
            </div>
            <p id="error"></p>
            <button type="submit" id="buttonId" class="submit-button">
                <p>Enviar</p>
                <img src="assets/arrow-right.svg" alt="">
            </button>
        </div>

    <?php } else {
    ?>
        <div style="padding-top:15px; border-top: 1px solid #dbdada;" class="own-form-group grid-2-2">
            <div class="own-form-field">
                <label for="nome"> Nome Completo *</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome" maxlength="100" required>
                <p class="error"></p>
            </div>

            <div class="own-form-field">
                <label>Gênero *</label>
                <div class="flex">
                    <div class="flex">
                        <input type="radio" id="male" name="gender" value="M" checked="checked" required>
                        <label for="male">Masculino</label>
                    </div>
                    <div class="flex">
                        <input type="radio" id="female" name="gender" value="F" required>
                        <label for="female">Feminino</label>
                    </div>
                </div>
                <p class="error"></p>
            </div>



            <div class="own-form-field">
                <label for="estado_civil">Estado Civil *</label>
                <select id="estado_civil" name="estado_civil" id="estado_civil" required>
                    <option selected></option>
                    <option value="Solteiro">Solteiro</option>
                    <option value="Casado">Casado</option>
                    <option value="Separado">Separado</option>
                    <option value="Divorciado">Divorciado</option>
                    <option value="Viúvo">Viúvo</option>
                </select>
                <p class="error"></p>
            </div>



            <div class="own-form-field">
                <label for="dependents">Possui dependentes de imposto de renda*</label>
                <div class="flex">
                    <div class="flex">
                        <input type="radio" id="yes" name="dependents" value="S" checked="checked" required>
                        <label for="yes">Sim</label>
                    </div>
                    <div class="flex">
                        <input type="radio" id="no" name="dependents" value="N" required>
                        <label for="no">Não</label>
                    </div>
                </div>
                <p class="error"></p>
            </div>
            <p id="error"></p>
            <button type="submit" id="buttonId" class="submit-button">
                <p>Enviar</p>
                <img src="assets/arrow-right.svg" alt="">
            </button>
        </div>
    <?php } ?>
    <script>
        $("#nome").off()
        $("#male").off()
        $("#female").off()
        $("#yes").off()
        $("#no").off()


        let estadoCivil = document.querySelector("#estado_civil");

        let nome = document.querySelector("#nome");
        let male = document.querySelector("#male");
        let female = document.querySelector("#female");
        let yes = document.querySelector("#yes");
        let no = document.querySelector("#no");



        estadoCivil.addEventListener('change', (e) => {
            update = true;
        })

        nome.addEventListener('change', (e) => {
            update = true;
        })


        male.addEventListener('change', (e) => {
            update = true;
        })

        female.addEventListener('change', (e) => {
            update = true;
        })

        yes.addEventListener('change', (e) => {
            update = true;
        })

        no.addEventListener('change', (e) => {
            update = true;
        })




        $(document).ready(function() {
            $("form").off();
            $("form").submit(function(event) {
                document.getElementById("buttonId").querySelector("p").innerHTML = "Enviando..."
                document.getElementById("buttonId").querySelector("img").src = "assets/spinner2.gif"
                $("#buttonId").attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "send-documents1-controller.php",
                    data: new FormData(this),
                    cache: false,
                    dataType: 'json',
                    contentType: false,
                    processData: false
                }).done(function(data) {
                    const p = document.querySelector("#error");
                    if (!data.success) {
                        document.getElementById("buttonId").querySelector("p").innerHTML = "Enviar"
                        document.getElementById("buttonId").querySelector("img").src = "assets/arrow-right.svg"
                        $("#buttonId").attr("disabled", false);
                        if (data.message == 'Erro inesperado,tente novamente mais tarde') {
                            p.innerText = "";
                            p.innerText = data.message;
                            return;
                        }
                        if (data.message === 'Sexo inválido') {
                            p.innerText = "";
                            p.innerText = data.message;
                            return;
                        }
                        p.innerText = "";
                        p.innerText = data.message;
                        const $errors = document.querySelectorAll(".error");

                        $errors.forEach(element => {
                            element.innerHTML = "";
                        });

                        const errors = Object.entries(data.errors);

                        errors.forEach((error) => {
                            let parent = document.getElementById(error[0]).parentElement;
                            if (parent.classList.contains('own-form-field')) {
                                parent.querySelector('.error').innerHTML = "";
                                parent.querySelector('.error').innerHTML = error[1]
                            } else {
                                parent.parentElement.querySelector('.error').innerHTML = "";
                                parent.parentElement.querySelector('.error').innerHTML = error[1]
                            }

                        });
                    } else {
                        $("#form").load('views/send-documents2-view.php', () => {
                            setStepButton("#step3");
                        });

                    }
                })
                event.preventDefault();

            });
        });
    </script>
    <?php if ($_SESSION['update'] == 1 && $_SESSION['step'] >= 2) { ?>
        <script>
            if (loadAgain) {
                loadAgain = false;
                const steps = document.querySelectorAll('.step');
                steps.forEach(step => step.classList.add('pointer'));

                function setStep(stepId) {
                    document.querySelector(stepId).click();
                }

                function setStepButton(stepId) {
                    steps.forEach((step) => step.classList.remove('active'));
                    document.querySelector(stepId).classList.add('active');
                }

                function addLoading(stepId) {
                    document.querySelector(stepId).querySelector("h2").classList.add("desactive");
                    document.querySelector(stepId).querySelector("p").classList.add("desactive");
                    document.querySelector(stepId).querySelector("img").classList.add("active");
                }

                function removeLoading(stepId) {
                    document.querySelector(stepId).querySelector("img").classList.remove("active");
                    document.querySelector(stepId).querySelector("h2").classList.remove("desactive");
                    document.querySelector(stepId).querySelector("p").classList.remove("desactive");
                }


                const step2 = document.querySelector("#step2");

                if (step2.getAttribute('listener') !== 'true') {
                    step2.addEventListener('click', () => {
                        if (update) {
                            alert("Envie as alterações antes de avançar");
                            return;
                        }
                        addLoading("#step2")
                        $("form").load('views/send-documents1-view.php', () => {
                            removeLoading("#step2")
                            steps.forEach((step) => step.classList.remove('active'));
                            step2.classList.add('active');
                        });
                    })
                }



                const step3 = document.querySelector("#step3");

                if (step3.getAttribute('listener') !== 'true') {
                    step3.addEventListener('click', () => {
                        if (update) {
                            alert("Envie as alterações antes de avançar");
                            return;
                        }
                        addLoading("#step3")
                        $("form").load('views/send-documents2-view.php', () => {
                            removeLoading("#step3")
                            steps.forEach((step) => step.classList.remove('active'));
                            step3.classList.add('active');
                        });
                    })
                }


                const step4 = document.querySelector("#step4");

                if (step4.getAttribute('listener') !== 'true') {
                    step4.addEventListener('click', () => {
                        if (update) {
                            alert("Envie as alterações antes de avançar");
                            return;
                        }
                        addLoading("#step4")
                        $("form").load('views/send-documents3-view.php', () => {
                            removeLoading("#step4")
                            steps.forEach((step) => step.classList.remove('active'));
                            step4.classList.add('active');
                        });
                    })
                }

                const step5 = document.querySelector("#step5");


                if (step5.getAttribute('listener') !== 'true') {
                    step5.addEventListener('click', () => {
                        if (update) {
                            alert("Envie as alterações antes de avançar");
                            return;
                        }
                        addLoading("#step5")
                        $("form").load('views/send-documents4-view.php', () => {
                            removeLoading("#step5")
                            steps.forEach((step) => step.classList.remove('active'));
                            step5.classList.add('active');
                        });
                    })
                }

                const step6 = document.querySelector("#step6");
                if (step6.getAttribute('listener') !== 'true') {
                    step6.addEventListener('click', () => {
                        if (update) {
                            alert("Envie as alterações antes de avançar");
                            return;
                        }
                        addLoading("#step6")
                        $("form").load('views/send-documents5-view.php', () => {
                            removeLoading("#step6")
                            steps.forEach((step) => step.classList.remove('active'));
                            step6.classList.add('active');
                        });
                    })
                }

                const step7 = document.querySelector("#step7");

                if (step6.getAttribute('listener') !== 'true') {
                    step7.addEventListener('click', () => {
                        if (update) {
                            alert("Envie as alterações antes de avançar");
                            return;
                        }
                        addLoading("#step7")
                        $("form").load('views/send-documents6-view.php', () => {
                            removeLoading("#step7")
                            steps.forEach((step) => step.classList.remove('active'));
                            step7.classList.add('active');
                        });
                    })
                }
            }
        </script>
    <?php } else if ($_SESSION['step'] >= 2 && $_SESSION['update'] == 0) { ?>
        <script>
            const steps = document.querySelectorAll('.step');

            function setStepButton(stepId) {
                steps.forEach((step) => step.classList.remove('active'));
                document.querySelector(stepId).classList.add('active');
            }
        </script>
    <?php } ?>

<?php } ?>