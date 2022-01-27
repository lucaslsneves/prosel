<?php
require "../check-session-user-prosel.php";
include '../connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$dados = null;
try {
    if ($_SESSION['update']) {
        $id = $_SESSION['id'];
        $query = "SELECT cartao_sus, curriculo, cartao_vacinacao,diploma,carteira_trabalho,conta_bancaria FROM usuario_prosel WHERE id = '$id'";
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
    <div class="own-form-group grid-2-2">
        <?php if ($dados[0]['cartao_sus'] == '') {  ?>
            <div class="own-form-field">
                <label for="sus">Cartão do SUS*</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="sus" name="sus" required />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="sus">Cartão do SUS* <img src="assets/check.svg"> </label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="sus" name="sus" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"> <strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['cartao_sus']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>

        <?php if ($dados[0]['conta_bancaria'] == '') {  ?>

            <div class="own-form-field">
                <label for="conta_bancaria">Comprovante de conta bancária (preferencialmente Itaú)</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="conta_bancaria" required name="conta_bancaria" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>

        <?php } else { ?>
            <div class="own-form-field">
                <label for="conta_bancaria">Comprovante de conta bancária (preferencialmente Itaú) <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="conta_bancaria" name="conta_bancaria" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"><strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['conta_bancaria']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>


        <?php if ($dados[0]['cartao_vacinacao'] == '') {  ?>
            <div class="own-form-field">
                <label for="vacinacao">Cartão de vacinação - 1ª Via*</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="vacinacao" name="vacinacao" required />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="vacinacao">Cartão de vacinação - 1ª Via* <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="vacinacao" name="vacinacao" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"> <strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['cartao_vacinacao']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>



        <?php if ($dados[0]['diploma'] == '') {  ?>
            <div class="own-form-field">
                <label for="diploma">Diploma de graduação, ensino médio ou ensino fundamental*</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="diploma" name="diploma" required />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>

        <?php } else { ?>
            <div class="own-form-field">
                <label for="diploma">Diploma de graduação, ensino médio ou ensino fundamental* <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="diploma" name="diploma" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"> <strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['diploma']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>

                </div>
                <p class="error"></p>
            </div>

        <?php } ?>


        <?php if ($dados[0]['curriculo'] == '') {  ?>
            <div class="own-form-field">
                <label for="curriculo">Currículo (atualizado)*</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="curriculo" name="curriculo" required />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="curriculo">Currículo (atualizado)* <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="curriculo" name="curriculo" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"> <strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['curriculo']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>



        <?php if ($dados[0]['carteira_trabalho'] == '') {  ?>
            <div class="own-form-field">
                <label for="carteira_trabalho">Carteira de Trabalho Digital (Com Experiência dos últimos 6 meses) *</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="carteira_trabalho" name="carteira_trabalho" required />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="carteira_trabalho">Carteira de Trabalho Digital (Com Experiência dos últimos 6 meses) * <img src="assets/check.svg"> </label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="carteira_trabalho" name="carteira_trabalho" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"> <strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['carteira_trabalho']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>

        <div></div>
        <p id="error"></p>
        <button type="submit" id="buttonId" class="submit-button">
            <p>Enviar</p>
            <img src="assets/arrow-right.svg" alt="">
        </button>
    </div>
<?php } else {
?>
    <div class="own-form-group grid-2-2">
        <div class="own-form-field">
            <label for="sus">Cartão do SUS*</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="sus" name="sus" required />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div class="own-form-field">
                <label for="conta_bancaria">Comprovante de conta bancária (preferencialmente Itaú)</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="conta_bancaria" required name="conta_bancaria" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>

        <div class="own-form-field">
            <label for="vacinacao">Cartão de vacinação - 1ª Via*</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="vacinacao" name="vacinacao" required />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div class="own-form-field">
            <label for="diploma">Diploma de graduação, ensino médio ou ensino fundamental*</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="diploma" name="diploma" required />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div class="own-form-field">
            <label for="curriculo">Currículo (atualizado)*</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="curriculo" name="curriculo" required />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div class="own-form-field">
            <label for="carteira_trabalho">Carteira de Trabalho Digital (Com Experiência dos últimos 6 meses) *</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="carteira_trabalho" name="carteira_trabalho" required />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div></div>
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
                update = true;
            }
            console.log(update);
        });
    });



    $(document).ready(function() {

        $("form").off();
        const rgEdit = document.querySelector('#rg-edit');
        if (rgEdit) {
            rgEdit.addEventListener('click', (e) => {
                e.preventDefault();
                $("#rg").attr("disabled", true);
            })
        }

        $("form").submit(function(event) {
            document.getElementById("buttonId").querySelector("p").innerHTML = "Enviando..."
            document.getElementById("buttonId").querySelector("img").src = "assets/spinner2.gif"
            $("#buttonId").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "send-documents3-controller.php",
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
                    $("#form").load('views/send-documents4-view.php', () => {
                        setStepButton("#step5");
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