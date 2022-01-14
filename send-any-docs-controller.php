<?php
include 'connection.php';
require 'check-session-user-prosel.php';

$inputFiles = $_FILES;

$inputs = $_POST;

$id = $_SESSION['id'];


$ok = true;

foreach ($inputFiles as $key => $value) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $_FILES[$key]['name'], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" .  $key . "_" . "$name";
    move_uploaded_file($_FILES[$key]["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET " . $key . "=" .  "'$path' " . "WHERE id = $id;";
    $ok = $mysqli->query($query);
}

foreach ($inputs as $key => $value) {
    $sqlUpdate = "
UPDATE usuario_prosel
SET " . $key . "=" . "'$value'" . " WHERE id =" . $id;
    $ok = $mysqli->query($sqlUpdate);
}

if ($ok == true) {
    $data['success'] = true;
    $data['message'] = 'Formulário enviado com sucesso';
    echo json_encode($data);
    exit;
} else {
    $data['success'] = false;
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
}
