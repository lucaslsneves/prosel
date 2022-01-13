<?php
include "connection.php";
session_start();
$errors = [];
$data = [];


function validaCPF($cpf)
{

    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// Get form data

// mysql_real_escape_string prevent sql injection

$cpf =  mysqli_real_escape_string($mysqli, $_POST['cpf']);


// Required fields validation

$requiredTextFields = array('cpf');

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

// CPF Validation

if (!validaCPF($_POST['cpf'])) {
    $errors['cpf'] = "CPF inválido";
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['message'] = '';
    $data['errors'] = $errors;
    echo json_encode($data);
    exit;
}

// CPF is known validation

$query = "SELECT cpf FROM auth_users_prosel WHERE cpf ='$cpf'";
$stmt = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);

if (!isset($stmt[0]['cpf'])) {
    $data['success'] = false;
    $data['message'] = '';
    $errors['cpf'] = 'CPF não cadastrado na base de dados , entre em contato com o RH';
    $data['errors'] =  $errors;
    echo json_encode($data);
    exit;
}


// Has the user already sent documents?
// If not, insert.

$user_prosel = $mysqli->query("SELECT * FROM usuario_prosel WHERE cpf = '$cpf' and nome_completo is not null")->fetch_all(MYSQLI_ASSOC);
$user_prosel2 = null;
if (empty($user_prosel[0]['cpf'])) {
    $user_prosel2 = $mysqli->query("SELECT * FROM usuario_prosel WHERE cpf = '$cpf'")->fetch_all(MYSQLI_ASSOC);
    $data['success'] = true;
    $data['message'] = 'Primeiro Registro';
    $_SESSION['update'] = false;
    $_SESSION['cpf'] =  $user_prosel2[0]['cpf'];
    $_SESSION['id'] = $user_prosel2[0]['id'];
    $_SESSION['prosel'] = $user_prosel2[0]['prosel'];
    $_SESSION['step'] = 2;
    echo json_encode($data);
    exit;
}
$user_prosel2 = $mysqli->query("SELECT * FROM usuario_prosel WHERE cpf = '$cpf'")->fetch_all(MYSQLI_ASSOC);
$data['success'] = true;
$data['message'] = 'Atualizar docs';
$_SESSION['update'] = true;
$_SESSION['cpf'] =  $user_prosel2[0]['cpf'];
$_SESSION['id'] = $user_prosel2[0]['id'];
$_SESSION['nomeCompleto'] = $user_prosel2[0]['nome_completo'];
$_SESSION['prosel'] = $user_prosel2[0]['prosel'];
$_SESSION['step'] = 2;
echo json_encode($data);
