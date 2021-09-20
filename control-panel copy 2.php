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


</head>

<body>
    <div class="container">
        <nav id="sidebar" class="active">
            <ul>
                <li class="logo-item">
                    <img src="assets/svg/adminlogo.svg" class="col-3 ml-2 p-2" style="width: 90px; height: 50px; color: white">
                </li>
                <li class="sidebar-menu-item">
                    <?php if($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'Sede' || $_SESSION['role'] == 'admin'){ ?>
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

                <table id="table" maxPage="<?php echo $docsMaxPage ?>">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">CPF</th>
                            <th scope="col">Sexo</th>
                            <th scope="col">Estado Civil</th>
                            <th scope="col">CNH</th>
                            <th scope="col">CTPS</th>
                            <th scope="col">Foto3x4</th>
                            <th scope="col">Endereço</th>
                            <th scope="col">RG</th>
                            <th scope="col">PIS</th>
                            <th scope="col">SUS</th>
                            <th scope="col">Vacinação</th>
                            <th scope="col">Diploma</th>
                            <th scope="col">Currículo</th>
                            <th scope="col">e-Social</th>
                            <th scope="col">C. Bancária</th>
                            <th scope="col">Espec.</th>
                            <th scope="col">Reservista</th>
                            <th scope="col">CPF Depen.</th>
                            <th scope="col">Casamento/União</th>
                            <th scope="col">RG/Certidão Depen.</th>
                            <th scope="col">Vacinação Depen.</th>
                            <th scope="col">Escola Depen.</th>
                            <th scope="col">Titulo Eleitor</th>
                            <th scope="col">Carteira Conselho</th>
                            <th scope="col">Prosel</th>
                            <th scope="col">Função</th>
                            <?php if ($_SESSION["role"] == "admin") { ?> <th scope="col">Excluir</th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $item) {
                        ?> <tr>
                                <td data-label="Nome"><?php print_r($item['nome_completo'])   ?></td>
                                <td data-label="CPF"><?php print_r($item['cpf']) ?></td>
                                <td data-label="Sexo"><?php print_r($item['sexo']) ?></td>
                                <td data-label="Estado Civil"><?php print_r($item['estado_civil']) ?></td>
                                <td data-label="CNH"><?php if ($item['cnh'] != '') { ?><a target="_blank" href="<?php print_r($item['cnh']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="CTPS"><?php if ($item['carteira_trabalho'] != '') { ?><a target="_blank" href="<?php print_r($item['carteira_trabalho']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="Foto3x4"><?php if ($item['foto3x4'] != '') { ?><a target="_blank" href="<?php print_r($item['foto3x4']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="C. Endereço"><?php if ($item['comprovante_endereco'] != '') { ?><a target="_blank" href="<?php print_r($item['comprovante_endereco']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img><?php } ?></a></td>
                                <td data-label="RG"><?php if ($item['rg'] != '') { ?><a target="_blank" href="<?php print_r($item['rg']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="PIS"><?php if ($item['cartao_pis'] != '') { ?><a target="_blank" href="<?php print_r($item['cartao_pis']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="SUS"><?php if ($item['cartao_sus'] != '') { ?><a target="_blank" href="<?php print_r($item['cartao_sus']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="C. Vacinação"><?php if ($item['cartao_vacinacao'] != '') { ?><a target="_blank" href="<?php print_r($item['cartao_vacinacao']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="Diploma"><?php if ($item['diploma'] != '') { ?><a target="_blank" href="<?php print_r($item['diploma']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="Currículo"><?php if ($item['curriculo'] != '') { ?><a target="_blank" href="<?php print_r($item['curriculo']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="e-Social"><?php if ($item['esocial'] != '') { ?><a target="_blank" href="<?php print_r($item['esocial']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                                <td data-label="C. Bancária"><?php if ($item['conta_bancaria'] != '') { ?><a target="_blank" href="<?php print_r($item['conta_bancaria']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Espec."><?php if ($item['especializacoes'] != '') { ?><a target="_blank" href="<?php print_r($item['especializacoes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Reserv."><?php if ($item['reservista'] != '') { ?><a target="_blank" href="<?php print_r($item['reservista']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="CPF depen"><?php if ($item['cpf_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['cpf_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Certidão/união"><?php if ($item['certidao_casamento'] != '') { ?><a target="_blank" href="<?php print_r($item['certidao_casamento']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="RG depen"><?php if ($item['rg_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['rg_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Vacinação"><?php if ($item['vacinacao_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['vacinacao_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Escola depen"><?php if ($item['comprovante_escolar_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['comprovante_escolar_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Vacinação"><?php if ($item['titulo_eleitor'] != '') { ?><a target="_blank" href="<?php print_r($item['titulo_eleitor']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Escola depen"><?php if ($item['carteira_conselho'] != '') { ?><a target="_blank" href="<?php print_r($item['carteira_conselho']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                                <td data-label="Prosel"><?php print_r($item['prosel']) ?></td>
                                <td data-label="Função"><?php if (isset($cpfToFuncao[$item['cpf']])) print_r($cpfToFuncao[$item['cpf']]) ?></td>

                                <?php if ($_SESSION["role"] == "admin") { ?><td data-label="Excluir"><img class="delete-docs" src="assets/delete.png" id="<?php echo $item['id'] ?>" style="cursor: pointer;"></img></td><?php } ?>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
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
            window.location.replace('http://localhost/prosel/admin.php')
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
        let maxPage = $("table").attr("maxPage")
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