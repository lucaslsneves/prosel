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

// set the number of items to display per page
$items_per_page = 9;
$role = $_SESSION['role'];
// build query
$offset = ($page - 1) * $items_per_page;

if (empty($_GET['like'])) {
    $nome = "";
} else {
    $nome = $_GET['like'];
}

$query = null;
$queryAll = null;

if (empty($_GET['prosel']) && ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'Sede')) {
    $prosel = "";
    $query = "SELECT * FROM usuario_prosel WHERE nome_completo LIKE '%$nome%'  or cpf LIKE '%$nome%' ORDER BY updated_at DESC LIMIT " . $offset . "," . $items_per_page;
    $queryAll = "SELECT COUNT(*) FROM usuario_prosel WHERE nome_completo LIKE '%$nome%' or cpf LIKE '%$nome%'";
} else if ($_SESSION['role'] == 'dp' || $_SESSION['role'] == 'admin' || $_SESSION['role'] == 'Sede') {
    $prosel = $_GET['prosel'];
    $query = "SELECT * FROM usuario_prosel WHERE prosel = '$prosel' and  (nome_completo LIKE '%$nome%'  or cpf LIKE '%$nome%')  ORDER BY updated_at DESC LIMIT " . $offset . "," . $items_per_page;
    $queryAll = "SELECT COUNT(*) FROM usuario_prosel WHERE (nome_completo LIKE '%$nome%' or cpf LIKE '%$nome%') and prosel = '$prosel'";
} else {
    if (empty($nome)) {
        $query = "SELECT * FROM `usuario_prosel` WHERE prosel = '$role' order by `updated_at` DESC LIMIT 0,9";
        $queryAll = "SELECT COUNT(*) FROM usuario_prosel WHERE prosel = '$role'";
    } else {
        $query = "SELECT * FROM `usuario_prosel`  WHERE prosel = '$role' and nome_completo LIKE '%$nome%'  or cpf LIKE '%$nome%' order by `updated_at` DESC LIMIT " . $offset . "," . $items_per_page;
        $queryAll = "SELECT COUNT(*) FROM usuario_prosel WHERE prosel = '$role' LIKE '%$nome%'  or cpf LIKE '%$nome%'";
    }
}


$docsAmount = $mysqli->query($queryAll)->fetch_all(MYSQLI_ASSOC);
$docsCount = $docsAmount[0]['COUNT(*)'];
$docsMaxPages = ceil($docsCount / $items_per_page);
$docs = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);


$cpfs = '';
$cpfToFuncao = [];

foreach ($docs as $user) {
    if (empty($cpfs)) {
        $cpfs = "'" . $user['cpf'] . "'";
    } else {
        $cpfs .= ',' . "'" . $user['cpf'] . "'";
    }
}
$queryFuncoes = null;
$dadosFuncoes = null;

if (!empty($cpfs)) {
    $queryFuncoes = "SELECT * FROM `auth_users_prosel` where cpf in (" . $cpfs . ")";
    $dadosFuncoes = $mysqli->query($queryFuncoes)->fetch_all(MYSQLI_ASSOC);
    foreach ($dadosFuncoes as $user) {
        $cpfToFuncao[$user['cpf']] = $user['funcao'];
    }
}



?>
<ul class="ul-docs" id="table" maxPage="<?php echo $docsMaxPages ?>">
    <div id="ul-header">
        <p>Nome</p>
        <p>CPF</p>
        <p>Função</p>
        <p>Unidade</p>
    </div>
    <?php foreach ($docs as $item) {
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