<?php
require "verifica.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $data = [];
    include 'connection.php';
    if (!isset($_POST['cpf'])) {
        header("Location: control-panel.php");
        exit;
    }
    $cpf =  $_POST["cpf"];
    

    $query = "DELETE FROM `auth_users_prosel` WHERE `cpf` = '$cpf'";
    $isOk = $mysqli->query($query);
    $data['message'] = $isOk;
    echo json_encode($data);
} catch (Exception $e) {
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
}
