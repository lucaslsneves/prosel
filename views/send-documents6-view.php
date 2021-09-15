<?php
require "../check-session-user-prosel.php";
include '../connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$dados = null;
try {
    if ($_SESSION['update']) {
        $id = $_SESSION['id'];
        $query = "SELECT carteira_conselho,especializacoes,conta_bancaria,cnh FROM usuario_prosel WHERE id = '$id'";
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
        <?php if ($dados[0]['carteira_conselho'] == '') {  ?>
            <div class="own-form-field">
                <label for="carteira_conselho">Carteira do conselho</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="carteira_conselho" name="carteira_conselho" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="carteira_conselho">Carteira do conselho <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="carteira_conselho" name="carteira_conselho" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"><strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['carteira_conselho']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>


        <?php if ($dados[0]['conta_bancaria'] == '') {  ?>

            <div class="own-form-field">
                <label for="conta_bancaria">Comprovante de conta bancária (preferencialmente Itaú)</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="conta_bancaria" name="conta_bancaria" />
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



        <?php if ($dados[0]['especializacoes'] == '') {  ?>
            <div class="own-form-field">
                <label for="especializacoes">Especializações ou cursos técnicos (caso houver) (em um único arquivo)</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="especializacoes" name="especializacoes" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>

        <?php } else { ?>
            <div class="own-form-field">
                <label for="especializacoes">Especializações ou cursos técnicos (caso houver) (em um único arquivo) <img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="especializacoes" name="especializacoes" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"><strong>Arquivo já foi enviado</strong></span>
                    <a href="<?php print_r($dados[0]['especializacoes']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>

        <?php if ($dados[0]['cnh'] == '') {  ?>
            <div class="own-form-field">
                <label for="cnh">CNH</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="cnh" name="cnh" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="cnh">CNH<img src="assets/check.svg"></label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="cnh" name="cnh" />
                    <button type="button" class="custom-button">Alterar arquivo</button>
                    <span class="custom-text"><strong>Arquivo já foi enviado </strong></span>
                    <a href="<?php print_r($dados[0]['cnh']) ?>" target="_blank"><img src="assets/download.png" alt="Visualizar" style="cursor:pointer; width:20px;" /></a>
                </div>
                <p class="error"></p>
            </div>
        <?php } ?>


        
        <p id="error"></p>
        <button type="submit" id="buttonId" class="submit-button">
            <p>Finalizar</p>
            <img src="assets/arrow-right.svg" alt="">
        </button>
    </div>
<?php } else {
?>
    <strong id="optional">Atenção! Os campos abaixo são opcionais</strong>
    <div class="own-form-group grid-2-2">
        <div class="own-form-field">
            <label for="carteira_conselho">Carteira do conselho</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="carteira_conselho" name="carteira_conselho" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div class="own-form-field">
            <label for="contabancaria">Comprovante de conta bancária (preferencialmente Itaú)</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="conta_bancaria" name="conta_bancaria" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>

        <div class="own-form-field">
            <label for="especializacoes">Especializações ou cursos técnicos (caso houver) (em um único arquivo)</label>
            <div class="wrapper-input-file">
                <input type="file" class="real-file" id="especializacoes" name="especializacoes" />
                <button type="button" class="custom-button">Escolher Arquivo</button>
                <span class="custom-text">Nenhum arquivo selecionado</span>
            </div>
            <p class="error"></p>
        </div>


        <div class="own-form-field">
                <label for="cnh">CNH</label>
                <div class="wrapper-input-file">
                    <input type="file" class="real-file" id="cnh" name="cnh" />
                    <button type="button" class="custom-button">Escolher Arquivo</button>
                    <span class="custom-text">Nenhum arquivo selecionado</span>
                </div>
                <p class="error"></p>
            </div>

        <p id="error"></p>
        <button type="submit" id="buttonId" class="submit-button">
            <p>Finalizar</p>
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
                update = true;
                customTxts[i].innerHTML = "Arquivo selecionado";

            } else {
                customTxts[i].innerHTML = "Nenhum Arquivo Selecionado";
                update = true;
            }

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
                url: "send-documents6-controller.php",
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
                    $(".container").html(
                        `
                    <div id="wrapperId">
                        <div id="thank-you">
                        <h1 style="margin:0;">Você concluiu a primeira etapa!<h1/>
                        <p style="margin-top:20px;">Na <strong>2ª etapa </strong> você criará uma conta em nosso portal.
                            Após a criação você irá preencher mais algumas informações adicionais.</p>
                        <a href="https://meurh.ints.org.br/RM/Rhu-BancoTalentos/#/RM/Rhu-BancoTalentos/usuario_public" style="align-items:center;cursor:pointer;display:flex; padding:10px; border-radius:4px; background-color: rgb(113,174,140);margin-top:20px;letter-spacing: 1.3px;color:#fff;font-weight:bold;text-transform:uppercase;"><p>Concluir cadastro</p><img src="assets/arrow-right.svg"/></a>
                        </div>
                    <div/>
                    `
                    );

                }
            }).error(() => {
                document.getElementById("buttonId").querySelector("p").innerHTML = "Enviar"
                document.getElementById("buttonId").querySelector("img").src = "assets/arrow-right.svg"
                $("#buttonId").attr("disabled", false);
            })
            event.preventDefault();

        });
    });
    /**
     *  <p style="max-width:300px;margin-top:20px;">Clique no botão abaixo para concluir a  seu cadastro.</strong>
                        <p/>
                      
     */
</script>