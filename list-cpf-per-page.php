<?php
require "verifica.php";
include 'connection.php';

$page = 1;

if (!empty($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if (false === $page) {
        $page = 1;
    }
}

$items_per_page = 9;

if (empty($_GET['like'])) {
    $cpf = "";
}else {
    $cpf = $_GET['like'];
}



$offset = ($page - 1) * $items_per_page;



$query = null;
    $queryAll = null;
    $role = $_SESSION['role'];
    if ($role == 'dp' || $role == 'admin' || $role == 'Sede') {
        $query = "SELECT auth_users_prosel.id as id_auth, auth_users_prosel.cpf,  usuario_prosel.id as usuario_prosel_id,usuario_prosel.prosel,auth_users_prosel.funcao  FROM `auth_users_prosel`
        inner join usuario_prosel on usuario_prosel.cpf = auth_users_prosel.cpf
        where auth_users_prosel.cpf LIKE '%$cpf%'
        order by auth_users_prosel.updated_at DESC LIMIT " . $offset . "," . $items_per_page;
        $queryAll  = "SELECT count(*) FROM auth_users_prosel  where auth_users_prosel.cpf LIKE '%$cpf%'";
    } else {
        $query =   "SELECT auth_users_prosel.id as id_auth, auth_users_prosel.cpf,  usuario_prosel.id as usuario_prosel_id,usuario_prosel.prosel,auth_users_prosel.funcao  FROM `auth_users_prosel`
        inner join usuario_prosel on usuario_prosel.cpf = auth_users_prosel.cpf 
        where prosel = '$role'
        order by auth_users_prosel.updated_at DESC LIMIT " . $offset . "," . $items_per_page;
          $queryAll  = "SELECT count(*)  FROM `auth_users_prosel`
          inner join usuario_prosel on usuario_prosel.cpf = auth_users_prosel.cpf 
          where prosel = '$role'
          and auth_users_prosel.cpf LIKE '%$cpf%'
          order by auth_users_prosel.updated_at";
    }




$cpfAmount = $mysqli->query($queryAll)->fetch_all(MYSQLI_ASSOC);
$cpfCount = $cpfAmount[0]['count(*)'];

$cpfMaxPage = ceil($cpfCount / $items_per_page);


$cpfs = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);

?>
<table id="table" maxPage="<?php echo $cpfMaxPage ?>">
    <thead>
        <tr>
            <th scope="col">CPF</th>
            <th scope="col">Função</th>
            <th scope="col">Unidade</th>
        
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cpfs as $item) {
        ?> <tr>
                <td data-label="CPF"><?php print_r($item['cpf'])   ?></td>
                <td data-label="created_at"><?php print_r($item['funcao']) ?></td>
                <td data-label="created_at"><?php print_r($item['prosel']) ?></td>
                
              <!--  <td data-label="Excluir"><img class="delete-cpf" src="assets/delete.png" cpf="<?php echo $item['cpf'] ?>" style="cursor: pointer;"></img></td> -->
            </tr>
        <?php
        } ?>
    </tbody>
</table>
<script>
    (function() {
        $(document).ready(function() {
            const deleteButtons = document.querySelectorAll(".delete-cpf")
            const pSuccess = document.querySelector(".success")
            const pError = document.querySelector(".error");

            deleteButtons.forEach((button) => {
                button.addEventListener('click', (e) => {
                    let cpf = event.target.getAttribute('cpf');
                    $.ajax({
                        type: "POST",
                        url: "delete-cpf.php",
                        data: {
                            cpf
                        },
                        dataType: 'json',
                        encode: true,

                    }).done((data) => {
                        if (data['message'] == 'Erro inesperado,tente novamente mais tarde') {
                            $(".error").show()
                            $(".success").hide()
                            pError.innerHTML = "Erro ao excluir CPF, tente novamente mais tarde"
                            pSuccess.innerHTML = ""
                            return;
                        }
                        $("#info").load("list-cpf.php")
                    })
                })
            })
        })
    })()
</script>