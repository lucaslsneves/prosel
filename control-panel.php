<?php
require "verifica.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $role = $_SESSION['role'];
    $query = null;
    $queryAll = null;


    if ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'Sede') {
        $query = "SELECT * FROM `usuario_prosel` order by `updated_at` DESC LIMIT 9";
        $queryAll = "SELECT COUNT(*) FROM usuario_prosel";
    } else {
        $query = "SELECT * FROM `usuario_prosel`  WHERE prosel = '$role' order by `updated_at` DESC LIMIT 9";
        $queryAll = "SELECT COUNT(*) FROM usuario_prosel WHERE prosel = '$role'";
    }

    $dados = [];
    include 'connection.php';

    $dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    $cpfs = '';
    $cpfToFuncao = [];
    foreach ($dados as $user) {
        if (empty($cpfs)) {
            $cpfs = "'" . $user['cpf'] . "'";
        } else {
            $cpfs .= ',' . "'" . $user['cpf'] . "'";
        }
    }
    if (!empty($cpfs)) {
        $queryFuncoes = "SELECT * FROM `auth_users_prosel` where cpf in (" . $cpfs . ")";

        $dadosFuncoes = $mysqli->query($queryFuncoes)->fetch_all(MYSQLI_ASSOC);

        foreach ($dadosFuncoes as $user) {
            $cpfToFuncao[$user['cpf']] = $user['funcao'];
        }
    }



    $items_per_page = 9;

    $docsAmount = $mysqli->query($queryAll)->fetch_all(MYSQLI_ASSOC);
    $docsCount = $docsAmount[0]['COUNT(*)'];
    $docsMaxPage = round($docsCount / $items_per_page, 0);

    // get notifications

    $query = "select id from notifications where reader_aut_user_id =" . $_SESSION['id_usuario'] . " and already_read = 0";
    $notifications = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    $notificationsLength = count($notifications);
} catch (Exception $e) {
    print_r($e);
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
    exit;
}

?>

<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="control-panel-styles.css">
    <link rel="icon" href="assets/favicon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://rawgit.com/RobinHerbots/Inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        function openModal(item, inputs) {

            let docsHtml = {

            }
            for (doc in item) {
                if (item[doc]) {
                    docsHtml[doc] = `<a target="_blank" href="${item[doc]}"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a>`
                }
            }

            inputsHtml = "";

            inputs.forEach((input, i) => {
                if (i != 0) {
                    inputsHtml += ","
                }
                inputsHtml += input.description;
            })

            $('.modal-container').css("display", "flex")
            $(".modal-header").html(`
               
                <div style="flex-direction:column" class="grid-modal-item header">
                    <h1 style="font-weight:700; font-size: 24px;">${item.nome_completo == null ? '' : item.nome_completo}</h1>
                    <h2  style="font-weight:400;margin-top:8px; font-size: 22px;">${item.cpf}</h2>
                    <h2  style="font-weight:400;margin-top:8px; font-size: 22px;">${item.already_sent_all_docs == 0 ? '<img src="assets/times-regular.svg" style="width:20px; height:20px;"> Candidato ainda n??o enviou todos documentos ' : 'Candidato j?? enviou todos documentos'}</h2>
                </div>
                <div style="flex-direction:column" class="grid-modal-item header">
                    <h1 style="font-weight:400; font-size: 22px;">${item.prosel || ''}</h1>
                    <h2  style="font-weight:400;margin-top:8px; font-size: 22px;">${item.funcao || ''}</h2>
                    <h2  style="font-weight:500"><h2>Campos dispon??veis para edi????o:</h2>${inputsHtml}</h2>
                </div>
            `)
            $('.modal-content').html(`
            <input type="hidden" name="id" value="${item.id}" />
               <div class="grid-modal-item">
               <input style="margin-right:15px;" type="checkbox" name="sexo" value="4" />
                    <div style="text-align:center">
                    <p>Sexo</p>
                    <span>${item.sexo ?? '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}</span>
                    </div>
                    
               </div>
               <div class="grid-modal-item">
               <input style="margin-right:15px;" type="checkbox" name="estado_civil" value="30" />
                <div style="text-align:center">
                        <p>Estado Civil</p>
                        <span>${item.estado_civil ??'<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}</span>
                </div>
               
                </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="cpf_doc" value="2" />
               <div style="text-align:center">
                    <p>CPF Digitalizado</p>
                   ${docsHtml['cpf_doc'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
                 
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="cnh" value="25" />
               <div style="text-align:center">
                    <p>CNH</p>
                   ${docsHtml['cnh'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
                
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="carteira_trabalho" value="12" />

               <div style="text-align:center">
                    <p>Carteira de Trabalho</p>
                   ${docsHtml['carteira_trabalho'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="foto3x4" value="3" />

               <div style="text-align:center">
                    <p>Foto 3x4</p>
                   ${docsHtml['foto3x4'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="comprovante_endereco" value="5" />

               <div style="text-align:center">
                    <p>Comprovante de Endere??o</p>
                   ${docsHtml['comprovante_endereco'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="rg" value="6" />

               <div style="text-align:center">
                    <p>RG</p>
                   ${docsHtml['rg'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="titulo_eleitor" value="14" />
               <div style="text-align:center">
                    <p>T??tulo de Eleitor</p>
                   ${docsHtml['titulo_eleitor'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="cartao_pis" value="7" />
               <div style="text-align:center">
                    <p>PIS</p>
                   ${docsHtml['cartao_pis'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="cartao_sus" value="8" />
               <div style="text-align:center">
                    <p>Cart??o do SUS</p>
                   ${docsHtml['cartao_sus'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="cartao_vacinacao" value="9" />
               <div style="text-align:center">
                    <p>Cart??o de Vacina????o</p>
                   ${docsHtml['cartao_vacinacao'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="diploma" value="10" />
               <div style="text-align:center">
                    <p>Diploma</p>
                   ${docsHtml['diploma'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="curriculo" value="11" />
               <div style="text-align:center">
                    <p>Curr??culo</p>
                   ${docsHtml['curriculo'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="esocial" value="13" />
               <div style="text-align:center">
                    <p>eSocial</p>
                   ${docsHtml['esocial'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="conta_bancaria" value="23" />
               <div style="text-align:center">
                    <p>Conta Banc??ria</p>
                   ${docsHtml['conta_bancaria'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="especializacoes" value="24" />
               <div style="text-align:center">
                    <p>Especializa????es</p>
                   ${docsHtml['especializacoes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="carteira_conselho" value="22" />
               <div style="text-align:center">
                    <p>Carteira do Conselho</p>
                   ${docsHtml['carteira_conselho'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="reservista" value="16" />
               <div style="text-align:center">
                    <p>Reservista</p>
                   ${docsHtml['reservista'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="cpf_dependentes" value="15" />
               <div style="text-align:center">
                    <p>CPF Dependentes</p>
                   ${docsHtml['cpf_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="certidao_casamento" value="17" />
               <div style="text-align:center">
                    <p>Certid??o de Casamento</p>
                   ${docsHtml['certidao_casamento'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="rg_dependentes" value="19" />
               <div style="text-align:center">
                    <p>RG Dependentes</p>
                   ${docsHtml['rg_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="vacinacao_dependentes" value="20" />
               <div style="text-align:center">
                    <p>Vacina????o Dependentes</p>
                   ${docsHtml['vacinacao_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="comprovante_escolar_dependentes" value="21" />
               <div style="text-align:center">
                    <p>Comprovante Escolar Dependentes</p>
                   ${docsHtml['comprovante_escolar_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
                <div class="grid-modal-item doc">
                <input style="margin-right:15px;" type="checkbox" name="cpf_conjuje" value="18" />
                <div style="text-align:center">
                    <p>CPF C??njuje</p>
                   ${docsHtml['cpf_conjuje'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="rne" value="26" />
               <div style="text-align:center">
                    <p>RNE</p>
                   ${docsHtml['rne'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
                   </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="passaporte" value="27" />
               <div style="text-align:center">
                    <p>Passaporte</p>
                   ${docsHtml['passaporte'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               </div>
               <div class="grid-modal-item doc">
               <input style="margin-right:15px;" type="checkbox" name="certidao_naturalizacao" value="28" />
               <div style="text-align:center">
                    <p>Certid??o Naturaliza????o</p>
                   ${docsHtml['certidao_naturalizacao'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               </div>
               <button type="submit" id="update-inputs" class="submit-button">Salvar</button>
            `)

        

            if (item['already_sent_all_docs'] == 0) {
                $(".modal-content :input").prop("disabled", true);
            }
        }
    </script>

    <style>
        .notification {
            color: white;
            text-decoration: none;
            position: relative;
            display: inline-block;
            cursor: pointer;
            border-radius: 2px;
        }



        .notification .badge {
            position: absolute;
            top: -12px;
            right: -12px;
            padding: 4px 8px;
            border-radius: 50%;
            background: red;
            color: white;
        }
    </style>
</head>

<body>
    <div class="modal-container">

        <div class="own-modal">
            <div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
                <img style="width:20px;" id="close-modal" src="assets/times-regular-black.svg">
            </div>
            <div style="padding-bottom:20px;" class="modal-header"></div>
            <form class="modal-content">

            </form>
        </div>
    </div>
    <div class="container">
        <nav style="padding:0 12px;" id="sidebar" class="active">
            <ul>

                <li class="logo-item">

                    <img src="assets/svg/adminlogo.svg" class="col-3 ml-2 p-2" style="width: 90px; height: 50px; color: white">

                </li>
                <li style="background-color:transparent;border-bottom:1px solid rgba(255,255,255,0.20);" class="sidebar-menu-item">
                    <?php if ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'Sede' || $_SESSION['role'] == 'admin') { ?>
                        <p>ADMINISTRA????O</p>
                    <?php } else { ?>
                        <p> <?php echo $_SESSION['role'] ?> </p>
                    <?php } ?>
                </li>
                <li style="margin-top:8px;" class="sidebar-item" id="list-docs">
                    <img src="assets/docs2.png" alt="Documentos">
                    <p id="username">Documentos</p>
                </li>
                <li class="sidebar-item" id="register-cpf">
                    <img style="width:24px;" src="assets/user-plus-regular.svg" alt="Documentos">
                    <p style="font-size: 15px;" id="username">Cadastro Candidato</p>
                </li>
                <li class="sidebar-item" id="change-password">
                    <img style="width:24px;" src="assets/key-light.svg" alt="Documentos">
                    <p style="font-size: 15px;" id="username">Trocar Senha</p>
                </li>
                <!-- <li class="sidebar-item" id="benefits">
                    <img style="width:24px;" src="assets/hand-holding-usd-light.svg" alt="Documentos">
                    <p style="font-size: 15px;" id="username">Benef??cios</p>
                </li> -->
                <li class="sidebar-item" id="sign-out">
                    <img style="width:24px;" src="assets/sign-out.svg" alt="Sair">
                    <p id="username">Sair</p>
                </li>
            </ul>
        </nav>
        <div id="content">
            <header>

                <div>
                    <div id="menu">
                        <img src="assets/close.png" alt="menu">
                    </div>
                    <h1 id="page-title">Documentos Admissionais</h1>
                </div>

                <div>

                    <div id="paginationDocs">
                        <div>
                            <button id="back"><img src="assets/arrow-left-white.svg" /></button>
                            <p id="page">1</p>
                            <button id="forward"><img src="assets/arrow-right.svg" /></button>
                        </div>
                    </div>


                    <div id="paginationCpfs">
                        <div>
                            <button id="backCpf"><img src="assets/arrow-left-white.svg" /></button>
                            <p id="pageCpf">1</p>
                            <button id="forwardCpf"><img src="assets/arrow-right.svg" /></button>
                        </div>
                    </div>

                    <div id="paginationNotifications">
                        <div>
                            <button id="backNotification"><img src="assets/arrow-left-white.svg" /></button>
                            <p id="pageNotification">1</p>
                            <button id="forwardNotification"><img src="assets/arrow-right.svg" /></button>
                        </div>
                    </div>



                    <div class="search-wrapper" style="display:flex; flex-direction: column;">
                        <?php if ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'Sede') { ?>
                            <select id="prosel" name="prosel" style="border-radius:8px;font-size:15px;width:208px; margin: 12px 0; padding: 8px;  border: 1px solid #CBD5E0;">
                                <option value="" selected>Processo Seletivo</option>
                                <option value="">Todos</option>
                                
                                <option value="Caucaia - Equipe de Gest??o - Escrit??rio">CAUCAIA - Equipe de Gest??o - Escrit??rio</option>
                                <option value="Caucaia UPA Centro">Caucaia UPA Centro</option>
                    <option value="Caucaia UPA Jurema">Caucaia UPA Jurema</option>
                    <option value="Caucaia HMAGR - Hospital">Caucaia HMAGR - Hospital</option>
                    <option value="Caucaia HMST - Maternidade">Caucaia HMST - Maternidade</option>
                                <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                                <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                                <option value="UPA de Brotas">UPA de Brotas</option>
                                <option value="UPA de Feira">UPA de Feira</option>
                                <option value="Espanhol">Espanhol</option>
                                <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/Roberto Santos,Albert Sabin)</option>
                                <option value="HGE">HGE</option>
                                <option value="Suzano">Suzano</option>
                                <option value="Bertioga">Bertioga</option>
                                <option value="SACA">SACA</option>
                                <option value="CRESAMU">CRESAMU</option>
                                <option value="UPA Orop??">UPA Orop??</option>
                                <option value="Hugo">Hugo</option>
                                <option value="Itumbiara">Itumbiara</option>
                                <option value="Sede">Sede</option>
                            </select>
                        <?php } ?>
                        <input placeholder="Buscar" type="text" name="search" id="search">

                    </div>


                    <input placeholder="Buscar" type="text" name="search" id="searchCpf" style="display:none;">
                    <div style="margin-left:15px;">
                        <a class="notification">
                            <img style="width:30px;" src="assets/bell.svg" />
                            <span class="badge"><?php echo $notificationsLength ?></span>
                        </a>
                    </div>
                </div>
            </header>
            <div id="info">

                <ul class="ul-docs" id="table" maxPage="<?php echo $docsMaxPage ?>">
                    <div id="ul-header">
                        <p>Nome</p>
                        <p>CPF</p>
                        <p>Fun????o</p>
                        <p>Unidade</p>
                    </div>
                    <?php foreach ($dados as $item) {
                        if (isset($cpfToFuncao[$item['cpf']])) {
                            $item['funcao'] = $cpfToFuncao[$item['cpf']];
                        } else {
                            $item['funcao'] = '';
                        }

                        $inputsUserCanSendQuery = "SELECT name,description,usuario_prosel_id,type_file from inputs
                        inner join inputs_user_can_send on inputs_user_can_send.input_id = inputs.id
                        where usuario_prosel_id = " . $item['id'];

                        $inputsUserCanSend1 = $mysqli->query($inputsUserCanSendQuery)->fetch_all(MYSQLI_ASSOC);

                        $inputsUserCanSend2 = json_encode(($inputsUserCanSend1));

                    ?>
                        <li data-label="Nome" onclick='openModal(<?php print_r(json_encode(($item))); ?> , <?php print_r($inputsUserCanSend2) ?>)'>

                            <p><?php print_r($item['nome_completo']) ?></p>
                            <p><?php print_r($item['cpf']) ?></p>
                            <p><?php if (isset($cpfToFuncao[$item['cpf']])) print_r($cpfToFuncao[$item['cpf']]) ?></p>
                            <p><?php print_r($item['prosel']) ?></p>

                        </li>
                    <?php } ?>
                </ul>
            </div>

        </div>
    </div>
</body>
<script>
    let pageDocs = 1;
    let pageCpf = 1;
    let boolListener = true;
    let proselValue = "";
    let searchDocs = "";
    let searchCpf = "";
    $page = document.querySelector("#page");
    const backDocs = document.querySelector("#back")
    const forwardDocs = document.querySelector("#forward");
    const search = document.querySelector("#search");
    const prosel = document.querySelector("#prosel");
    const signOut = document.querySelector("#sign-out");

    <?php if ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'Sede') { ?>
        prosel.addEventListener('change', (e) => {
            pageDocs = 1;
            proselValue = e.target.value;
            document.querySelector("#info").classList.add("loading")
            $("#info").html("<img src='assets/bigger-spinner.gif'>")
            console.log(e.target.value);

            $.ajax({
                type: "GET",
                url: `list-docs-per-page.php?page=${pageDocs}&like=${searchDocs}&prosel=${proselValue}`,
            }).done((data) => {
                document.querySelector("#info").classList.remove("loading")
                $page.innerHTML = pageDocs;
                $("#info").html(data);
            })

        })
    <?php } ?>
    signOut.addEventListener('click', (e) => {
        $.ajax({
            type: 'GET',
            url: 'sign-out.php'
        }).done(() => {
            window.location.replace('http://prosel.ints.org.br/admin.php')
        })
    })
    backDocs.addEventListener('click', (e) => {
        if (pageDocs === 1) {
            return;
        }
        document.querySelector("#info").classList.add("loading")
        $("#info").html("<img src='assets/bigger-spinner.gif'>")
            --pageDocs;
        $.ajax({
            type: "GET",
            url: `list-docs-per-page.php?page=${pageDocs}&like=${searchDocs}&prosel=${proselValue}`,
        }).done((data) => {
            document.querySelector("#info").classList.remove("loading")
            $page.innerHTML = pageDocs;
            $("#info").html(data);
        })
    })


    forwardDocs.addEventListener('click', (e) => {

        let maxPage = $(".ul-docs").attr("maxPage")
        console.log(maxPage)
        if (pageDocs >= maxPage) {
            return;
        }
        ++pageDocs;
        document.querySelector("#info").classList.add("loading")
        $("#info").html("<img src='assets/bigger-spinner.gif'>")
        $.ajax({
            type: "GET",
            url: `list-docs-per-page.php?page=${pageDocs}&like=${searchDocs}&prosel=${proselValue}`,
        }).done((data) => {
            document.querySelector("#info").classList.remove("loading")
            $page.innerHTML = pageDocs;
            $("#info").html(data);
        })
    })




    const deleteButtons = document.querySelectorAll(".delete-docs")
    const pError = document.querySelector(".error")
    deleteButtons.forEach((button) => {
        button.addEventListener('click', (e) => {
            let id = event.target.getAttribute('id');
            $.ajax({
                type: "POST",
                url: "delete-docs.php",
                data: {
                    id
                },
                dataType: 'json',
                encode: true,

            }).done((data) => {
                if (data['message'] == 'Erro inesperado,tente novamente mais tarde') {
                    $(".error").show()
                    pError.innerHTML = "Erro ao excluir documentos, tente novamente mais tarde"
                    pSuccess.innerHTML = ""
                    return;
                }
                location.reload();
            })
        })
    })
</script>
<script src="control-panel-script.js"></script>
<script>
    let formValues = {}

    document.querySelector(".modal-content").addEventListener('click', (e) => {
        if (e.target.getAttribute('type') === 'checkbox') {
            formValues[e.target.getAttribute('name')] = e.target.value
        } else if (e.target.id === 'update-inputs') {
            formValues['id'] = document.querySelector(".modal-content input[type='hidden']").value
            $("#update-inputs").html("<img style='width:40px' src='assets/bigger-spinner.gif'>")
            $.ajax({
                type: "POST",
                url: `inputs-user-can-send-controller.php`,
                dataType: 'json',
                data: formValues
            }).done((data) => {
                if(data.success) {
                    window.location.reload();
                }
                $(".modal-content").append(`<p>${data.message}</p>`)
                $("#update-inputs").html("Salvar")
            })
        }
    })
</script>

</html>