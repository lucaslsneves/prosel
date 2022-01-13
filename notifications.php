<?php
require "verifica.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {

    include 'connection.php';
    $query = null;
    $items_per_page = 15;
    $readerId = $_SESSION['id_usuario'];
    $query = "SELECT id,creator_usuario_prosel_id as creator,reader_aut_user_id as reader, title,description,already_read,created_at  FROM `notifications`
        where reader_aut_user_id =" . $readerId . " order by created_at DESC LIMIT " . $items_per_page;

    $queryAll  = "SELECT count(*) FROM notifications where reader_aut_user_id =" . $readerId;

    $dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);





    $cpfAmount = $mysqli->query($queryAll)->fetch_all(MYSQLI_ASSOC);
    $cpfCount = $cpfAmount[0]['count(*)'];
    $notificationMaxPage = round($cpfCount / $items_per_page, 0);

    $mysqli->query("UPDATE notifications set already_read = 1 where reader_aut_user_id =" . $readerId);
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
                if (item[doc]) {
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
                    <p>CPF Digitalizado</p>
                   ${docsHtml['cnh'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>CNH</p>
                   ${docsHtml['cpf_doc'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
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
                    <p>Comprovante Escolar Dependentes</p>
                   ${docsHtml['comprovante_escolar_dependentes'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
                <div class="grid-modal-item doc">
                    <p>CPF Cônjuje</p>
                   ${docsHtml['cpf_conjuje'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>RNE</p>
                   ${docsHtml['rne'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Passaporte</p>
                   ${docsHtml['passaporte'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
               <div class="grid-modal-item doc">
                    <p>Certidão Naturalização</p>
                   ${docsHtml['certidao_naturalizacao'] || '<img src="assets/times-regular.svg" style="width:20px; height:20px;">'}
               </div>
            `)
        }
    </script>

    <style>
        .notification {
            color: white;
            text-decoration: none;
            position: relative;
            display: inline-block;
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

        .container-notifications {
            padding: 30px 0;
            margin: 0 auto;
            width: 90%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }


        .container-notifications div {
            padding: 24px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            border: 2px solid #CBD5E0;
            border-radius: 8px;
            width: 80%;
            max-width: 1080px;
            font-size: 16px;
            font-weight: 400;
        }

        .badge1 {
            background-color: #E53E3E;
            color: #fff;
            font-weight: bold;
            padding: 9px;
            border-radius:3px;
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
            <div class="modal-content">

            </div>
        </div>
    </div>
    <div class="container-notifications" maxPage="<?php echo $notificationMaxPage ?>">
        <?php foreach($dados as $notification) { ?>
            <div>
                <p><?php echo $notification['description'] ?></p>
                <p><?php
                $date = date_create($notification['created_at']);
                echo date_format($date, 'd/m/Y H:i:s') ?></p>

               <?php if($notification['already_read'] == 0) { ?>
                    <p class="badge1">Novo</p>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>
<script>
    $pageNotification = document.querySelector("#pageNotification");
    const backNotification = document.querySelector("#backNotification")
    const forwardNotification = document.querySelector("#forwardNotification");

    if (boolListener) {
        boolListener = false;
        backNotification.addEventListener('click', (e) => {

            if (pageCpf === 1) {
                return;
            }


            $(".container-notifications").html("<img src='assets/bigger-spinner.gif'>")
                --pageCpf;

            $.ajax({
                type: "GET",
                url: `list-notifications-per-page.php?page=${pageCpf}`,
            }).done((data) => {
                $pageNotification.innerHTML = pageCpf;
                $(".container-notifications").html(data);
            })
        })


        forwardNotification.addEventListener('click', (e) => {
            let maxPage = $("table").attr("maxPage")
            if (pageCpf >= maxPage) {
                return;
            }

            pageCpf++;

            $(".container-notifications").html("<img src='assets/bigger-spinner.gif'>")
            $.ajax({
                type: "GET",
                url: `list-notifications-per-page.php?page=${pageCpf}`,
            }).done((data) => {
                $pageNotification.innerHTML = pageCpf;
                $(".container-notifications").html(data);
            })
        })

    }
</script>

</html>