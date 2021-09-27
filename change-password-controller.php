<?php

try {


require "verifica.php";
include 'connection.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if(!isset($_POST['current-password']) || !isset($_POST['new-password']) || !isset($_POST['password-confirmation'])) {
    $data['success'] = false;
    $data['message'] = 'Preencha todos os campos obrigatórios';
    echo json_encode($data);
    exit;
}

$userId = $_SESSION['id_usuario'];
$currentPassword = $_POST['current-password'];
$newPassword = $_POST['new-password'];
$passwordConfirmation = $_POST['password-confirmation'];

$dbUser = $mysqli->query("SELECT * from aut_users where id = '$userId'")->fetch_assoc();

if($dbUser['senha'] != md5($currentPassword)) {
    $data['success'] = false;
    $data['message'] = 'Senha atual incorreta';
    echo json_encode($data);
    exit;
}

// At least 1 number validation

if (!preg_match('/\d/', $newPassword)) {
    $data['success'] = false;
    $data['message'] = 'A nova senha deve conter ao menos 1 número';
    echo json_encode($data);
    exit;
}

// At least 1 letter validation

if(!preg_match("/[a-z]/i", $newPassword)){
    $data['success'] = false;
    $data['message'] = 'A nova senha deve conter ao menos uma letra';
    echo json_encode($data);
    exit;
}

// At least 8 characters validation

if(strlen($newPassword) < 8) {
    $data['success'] = false;
    $data['message'] = 'A nova senha deve conter ao menos 8 caracteres';
    echo json_encode($data);
    exit;
}

// New password and password confirmation must to be the same

if($newPassword != $passwordConfirmation) {
    $data['success'] = false;
    $data['message'] = 'A nova senha e a confirmação são diferentes';
    echo json_encode($data);
    exit;
}

$encryptedNewPassword = md5($newPassword);

$response = $mysqli->query("UPDATE aut_users
SET senha = '$encryptedNewPassword'
WHERE id = '$userId';");

if(!$response) {
    $data['success'] = false;
    $data['message'] = 'Erro inesperado';
    echo json_encode($data);
    exit;
}else {
    $data['success'] = true;
    $data['message'] = 'Senha alterada';
    echo json_encode($data);
    exit;
}

}catch(Exception $e) {
    $data['success'] = false;
    $data['message'] = 'Erro inesperado';
    echo json_encode($data);
    exit;
}
