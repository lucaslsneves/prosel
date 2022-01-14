<?php
require 'check-session-user-prosel.php';
include "connection.php";

$errors = [];
$data = [];



// Get form data

// mysql_real_escape_string prevent sql injection


$nome = mysqli_real_escape_string($mysqli, $_POST['nome']);
$sexo = mysqli_real_escape_string($mysqli, $_POST['gender']);
$dependents = mysqli_real_escape_string($mysqli, $_POST['dependents']);
$estadoCivil = mysqli_real_escape_string($mysqli, $_POST['estado_civil']);

$possui_dependentes = $dependents == 'S' ? 1 : 0;
$requiredTextFields = array('nome', 'gender', 'dependents', 'estado_civil');


// Required fields validation

foreach ($requiredTextFields as $textInput) {
    if (empty($_POST[$textInput])) {
        $errors[$textInput] = 'Este campo é obrigatório';
    }
}


if (!empty($errors)) {
    $data['success'] = false;
    $data['message'] = 'Preencha todos os campos obrigatórios';
    $data['errors'] = $errors;
    echo json_encode($data);
    exit;
}

// Gender valitdation

if (!($sexo == "M" || $sexo == "F")) {
    $data['success'] = false;
    $data['message'] = 'Sexo inválido';
    $data['errors'] = null;
    echo json_encode($data);
    exit;
}

// Prosel Validation



/*
if(!($prosel == 'Guarapiranga' || $prosel == 'Manoel Victorino')){
    $data['success'] = false;
    $data['message'] = 'Processo seletivo inválido';
    $data['errors'] = null;
    echo json_encode($data);
    exit;
} */



// Dependentes validation

if (!($dependents == "S" || $dependents == "N")) {
    $data['success'] = false;
    $data['message'] = 'Dependentes inválido';
    $data['errors'] = null;
    echo json_encode($data);
    exit;
}

$id = $_SESSION['id'];

$sqlUpdate = "
UPDATE usuario_prosel
SET  
nome_completo = '$nome',
sexo ='$sexo',
possui_dependentes = '$possui_dependentes',
estado_civil = '$estadoCivil'
WHERE
id = '$id'
";



if ($mysqli->query($sqlUpdate) == true) {
    $data['success'] = true;
    $data['message'] = 'Formulário enviado com sucesso';
    $_SESSION['possui_dependentes'] = $possui_dependentes;
    $_SESSION['sexo'] = $sexo;

    if(!$_SESSION['update']) {
        $cpf = $_SESSION['cpf'];
        $user_prosel = $mysqli->query("SELECT id , nome_completo FROM usuario_prosel WHERE cpf = '$cpf'")->fetch_all(MYSQLI_ASSOC);
        $_SESSION['nomeCompleto'] = $user_prosel[0]['nome_completo'];
    }
    
    echo json_encode($data);
} else {
    $data['success'] = false;
    $data['message'] = 'Erro insesperado';
    $_SESSION['possui_dependentes'] = $possui_dependentes;
    echo json_encode($data);
}
