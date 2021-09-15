<?php
require "../check-session-user-prosel.php";
include '../connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$dados = null;
try {
    if ($_SESSION['update']) {
        $cpf = $_SESSION['cpf'];
        $query = "SELECT nome_completo,sexo,prosel,cpf_dependentes,possui_dependentes,estado_civil FROM usuario_prosel WHERE cpf = '$cpf'";
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


        <?php if ($dados[0]['prosel'] == 'Guarapiranga') {  ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga" selected>Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'Manoel Victorino') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino" selected>Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'UPA de Brotas') {  ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas" selected>UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'UPA de Feira') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira" selected>UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'Espanhol') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol" selected>Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'SESAB') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB" selected>SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'HGE') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE" selected>HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'Suzano') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano" selected>Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'Bertioga') {  ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga" selected>Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'SACA') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA" selected>SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'CRESAMU') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU" selected>CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'UPA Oropó') {  ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó" selected>UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else if ($dados[0]['prosel'] == 'Hugo') { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo" selected>Hugo</option>
                    <option value="Sede">Sede</option>
                </select>
                <p class="error"></p>
            </div>
        <?php } else { ?>
            <div class="own-form-field">
                <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
                <select id="prosel" name="prosel" id="prosel" required>
                    <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                    <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                    <option value="UPA de Brotas">UPA de Brotas</option>
                    <option value="UPA de Feira">UPA de Feira</option>
                    <option value="Espanhol">Espanhol</option>
                    <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                    <option value="HGE">HGE</option>
                    <option value="Suzano">Suzano</option>
                    <option value="Bertioga">Bertioga</option>
                    <option value="SACA">SACA</option>
                    <option value="CRESAMU">CRESAMU</option>
                    <option value="UPA Oropó">UPA Oropó</option>
                    <option value="Hugo">Hugo</option>
                    <option value="Sede" selected>Sede</option>
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
            <label for="prosel">Seu processo seletivo é para qual unidade ? *</label>
            <select id="prosel" name="prosel" id="prosel" required>
                <option selected></option>
                <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                <option value="UPA de Brotas">UPA de Brotas</option>
                <option value="UPA de Feira">UPA de Feira</option>
                <option value="Espanhol">Espanhol</option>
                <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                <option value="HGE">HGE</option>
                <option value="Suzano">Suzano</option>
                <option value="Bertioga">Bertioga</option>
                <option value="SACA">SACA</option>
                <option value="CRESAMU">CRESAMU</option>
                <option value="UPA Oropó">UPA Oropó</option>
                <option value="Hugo">Hugo</option>
                <option value="Sede">Sede</option>
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
    $("#nome").off()
    $("#male").off()
    $("#female").off()
    $("#yes").off()
    $("#no").off()

    let prosel = document.querySelector("#prosel");
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

    prosel.addEventListener("change", (e) => {
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