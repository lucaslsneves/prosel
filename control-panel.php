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

    <script>
        function openModal(item) {
            let docsHtml = {

            }
            for (doc in item) {
                if(item[doc]) {
                    docsHtml[doc] = `<a target="_blank" href="${item[doc]}"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a>`  
                }
            }

            $('.modal-container').css("display", "flex")
            $(".modal-header").html(`
                <div class="grid-modal-item header">
                    <h1 style="font-weight:700; font-size: 24px;">${item.nome_completo == null ? '' : item.nome_completo}</h1>
                    <h2  style="font-weight:400;margin-top:8px; font-size: 22px;">${item.cpf}</h2>
                </div>
                <div class="grid-modal-item header">
                    <h1 style="font-weight:400; font-size: 22px;">${item.prosel || ''}</h1>
                    <h2  style="font-weight:400;margin-top:8px; font-size: 22px;">${item.funcao || ''}</h2>
                </div>
            `)
            $('.modal-content').html(`
               <div class="grid-modal-item">
                    <p>Sexo</p>
                    <span>${item.sexo ?? '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}</span>
               </div>
               <div class="grid-modal-item">
                    <p>Estado Civil</p>
                    <span>${item.estado_civil ??'<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}</span>
               </div>
               <div class="grid-modal-item doc">
                    <p>CNH</p>
                   ${docsHtml['cnh'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Carteira de Trabalho</p>
                   ${docsHtml['carteira_trabalho'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Foto 3x4</p>
                   ${docsHtml['foto3x4'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Comprovante de Endereço</p>
                   ${docsHtml['comprovante_endereco'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>RG</p>
                   ${docsHtml['rg'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Título de Eleitor</p>
                   ${docsHtml['titulo_eleitor'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>PIS</p>
                   ${docsHtml['cartao_pis'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Cartão do SUS</p>
                   ${docsHtml['cartao_sus'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Cartão de Vacinação</p>
                   ${docsHtml['cartao_vacinacao'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Diploma</p>
                   ${docsHtml['diploma'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Currículo</p>
                   ${docsHtml['curriculo'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>eSocial</p>
                   ${docsHtml['esocial'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Conta Bancária</p>
                   ${docsHtml['conta_bancaria'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Especializações</p>
                   ${docsHtml['especializacoes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Carteira do Conselho</p>
                   ${docsHtml['carteira_conselho'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Reservista</p>
                   ${docsHtml['reservista'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>CPF Dependentes</p>
                   ${docsHtml['cpf_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Certidão de Casamento</p>
                   ${docsHtml['certidao_casamento'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Certidão de Casamento</p>
                   ${docsHtml['certidao_casamento'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>RG Dependentes</p>
                   ${docsHtml['rg_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Vacinação Dependentes</p>
                   ${docsHtml['vacinacao_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Comprovante Escolar</p>
                   ${docsHtml['comprovante_escolar_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
            `)
        }
    </script>
</head>

<body>
    <div class="modal-container">

        <div class="own-modal">
            <div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
                <div id="close-modal">
                    <img src="assets/close.png">
                </div>
            </div>
            <div style="padding-bottom:20px;" class="modal-header"></div>
            <div class="modal-content">

            </div>
        </div>
    </div>
    <div class="container">
        <nav id="sidebar" class="active">
            <ul>
                <li class="logo-item">
                    <img src="assets/svg/adminlogo.svg" class="col-3 ml-2 p-2" style="width: 90px; height: 50px; color: white">
                </li>
                <li class="sidebar-menu-item">
                    <?php if ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'Sede' || $_SESSION['role'] == 'admin') { ?>
                        <p>ADMINISTRAÇÃO</p>
                    <?php } else { ?>
                        <p> <?php echo $_SESSION['role'] ?> </p>
                    <?php } ?>
                </li>
                <li class="sidebar-item" id="list-docs">
                    <img src="assets/docs2.png" alt="Documentos">
                    <p id="username">Documentos</p>
                </li>
                <li class="sidebar-item" id="register-cpf">
                    <img src="assets/add.png" alt="Documentos">
                    <p style="font-size: 15px;" id="username">Cadastro Candidato</p>
                </li>
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


                    <div class="search-wrapper" style="display:flex; flex-direction: column;">
                        <?php if ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'Sede') { ?>
                            <select id="prosel" name="prosel" style="border-radius:8px;font-size:15px;width:200px; margin: 12px 0; padding: 8px;  border: 1px solid #CBD5E0;">
                                <option value="" selected>Processo Seletivo</option>
                                <option value="">Todos</option>
                                <option value="Guarapiranga">Guarapiranga</option>
                                <option value="Manoel Victorino">Manoel Victorino</option>
                            </select>
                        <?php } ?>
                        <input placeholder="Buscar" type="text" name="search" id="search">

                    </div>


                    <input placeholder="Buscar" type="text" name="search" id="searchCpf" style="display:none;">

                </div>
            </header>
            <div id="info">

                <ul class="ul-docs" id="table" maxPage="<?php echo $docsMaxPage ?>">
                    <div id="ul-header">
                        <p>Nome</p>
                        <p>CPF</p>
                        <p>Função</p>
                        <p>Unidade</p>
                    </div>
                    <?php foreach ($dados as $item) {
                       if (isset($cpfToFuncao[$item['cpf']])) {
                            $item['funcao'] = $cpfToFuncao[$item['cpf']];
                       }else {
                        $item['funcao'] = '';
                       }
                    ?>
                        <li data-label="Nome" onclick='openModal(<?php print_r(json_encode(($item))); ?>)'>
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
            window.location.replace('http://localhost/prosel.ints.org.br/admin.php')
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
            console.log(id);
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

</html>