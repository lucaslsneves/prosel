<?php
require "../check-session-user-prosel.php";
include '../connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$dados = null;
try {
    if ($_SESSION['update']) {
        $id = $_SESSION['id'];
        $query = "SELECT certidao_casamento,rg_dependentes,vacinacao_dependentes,comprovante_escolar_dependentes FROM usuario_prosel WHERE id = '$id'";
        $dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    print_r($e);
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
    exit;
}
?>
<?php
if ($_SESSION['update']) {
?>
    <strong id="optional">Atenção! os campos abaixo são opcionais</strong>
    <div class="own-form-group grid-2-2">
        <?php if ($dados[0]['certidao_casamento'] == '') {  ?>
            <div class="own-form-field">
                <label for="wedding">Certidão de casamento ou declaração de união estável</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="wedding" name="wedding" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="wedding">Certidão de casamento ou declaração de união estável <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="wedding" name="wedding" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"><strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['certidao_casamento']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>


        <?php if ($dados[0]['rg_dependentes'] == '') {  ?>

            <div class="own-form-field">
            <label for="children-docs">Certidão de nascimento ou RG dos filhos (em um único arquivo) </label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="children-docs" name="children-docs" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <?php } else { ?>
            <div class="own-form-field">
            <label for="children-docs">Certidão de nascimento ou RG dos filhos (em um único arquivo) <img src="assets/check.svg"></label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="children-docs" name="children-docs" />
                <button type="button" class="custom-button">Alterar arquivo</button>
                <span class="custom-text"><strong>Arquivo já foi enviado</strong></span>
                <a href="<?php print_r($dados[0]['rg_dependentes']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
            </div>
            <p class="error"></p>
        </div>
        <?php } ?>



        <?php if ($dados[0]['vacinacao_dependentes'] == '') {  ?>
            <div class="own-form-field">
                <label for="children-vaccination">Cartão de vacina dos filhos até 05 (cinco) anos de idade (em um único arquivo)</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="children-vaccination" name="children-vaccination" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>

        <?php } else { ?>
            <div class="own-form-field">
                <label for="children-vaccination">Cartão de vacina dos filhos até 05 (cinco) anos de idade (em um único arquivo) <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="children-vaccination" name="children-vaccination" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"><strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['vacinacao_dependentes']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>

        <?php if ($dados[0]['comprovante_escolar_dependentes'] == '') {  ?>

            <div class="own-form-field">
                <label for="children-school">Declaração escolar dos filhos até 14 (quatorze) anos de idade (em um único arquivo)</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="children-school" name="children-school" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>

        <?php } else { ?>
            <div class="own-form-field">
                <label for="children-school">Declaração escolar dos filhos até 14 (quatorze) anos de idade (em um único arquivo) <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="children-school" name="children-school" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"><strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['comprovante_escolar_dependentes']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>

        <p id="error"></p>
        <button type="submit" id="buttonId" class="submit-button">
            <p>Enviar</p>
            <img src="assets/arrow-right.svg" alt="">
        </button>
    </div>
<?php } else {
?>
  <strong id="optional">Atenção! Os campos abaixo são opcionais</strong>
    <div class="own-form-group grid-2-2">

        <div class="own-form-field">
            <label for="wedding">Certidão de casamento ou declaração de união estável</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="wedding" name="wedding" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>


        <div class="own-form-field">
            <label for="children-docs">Certidão de nascimento ou RG dos filhos (em um único arquivo)</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="children-docs" name="children-docs" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>




        <div class="own-form-field">
            <label for="children-vaccination">Cartão de vacina dos filhos até 05 (cinco) anos de idade (em um único arquivo)</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="children-vaccination" name="children-vaccination" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div class="own-form-field">
            <label for="children-school">Declaração escolar dos filhos até 14 (quatorze) anos de idade (em um único arquivo)</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="children-school" name="children-school" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
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
    update = false;
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
                update = true;

            } else {
                customTxts[i].innerHTML = "Nenhum Arquivo Selecionado";
                update = false;
            }
            console.log(update);
        });
    });



    $(document).ready(function() {
        const rgEdit = document.querySelector('#rg-edit');
        if (rgEdit) {
            rgEdit.addEventListener('click', (e) => {
                e.preventDefault();
                $("#rg").attr("disabled", true);
            })
        }
        $("form").off();

        $("form").submit(function(event) {
            document.getElementById("buttonId").querySelector("p").innerHTML = "Enviando..."
            document.getElementById("buttonId").querySelector("img").src = "assets/spinner2.gif"
            $("#buttonId").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "send-documents5-controller.php",
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
                    $("#form").load('views/send-documents6-view.php', () => {
                        setStepButton("#step7");
                    });

                }
            }).error(() => {
                document.getElementById("buttonId").querySelector("p").innerHTML = "Enviar"
                document.getElementById("buttonId").querySelector("img").src = "assets/arrow-right.svg"
                $("#buttonId").attr("disabled", false);
            })
            event.preventDefault();

        });
    });
</script>