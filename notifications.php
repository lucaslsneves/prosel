<?php
require "verifica.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {

    include 'connection.php';
    $query = null;
    
    $readerId = $_SESSION['id_usuario'];
    $query = "SELECT id,creator_usuario_prosel_id as creator,reader_aut_user_id as reader, title,description,already_read,created_at  FROM `notifications`
        where reader_aut_user_id =" . $readerId . " and already_read = 0 order by created_at DESC";

    $queryAll  = "SELECT count(*) FROM notifications where reader_aut_user_id =" . $readerId;

    $dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);




    /*
    $cpfAmount = $mysqli->query($queryAll)->fetch_all(MYSQLI_ASSOC);
    $cpfCount = $cpfAmount[0]['count(*)'];
    $notificationMaxPage = round($cpfCount / $items_per_page, 0);
*/
    $mysqli->query("UPDATE notifications set already_read = 1 where reader_aut_user_id =" . $readerId);
} catch (Exception $e) {
    print_r($e);
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
    exit;
}

?>


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
            align-items: center;
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

