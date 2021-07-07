<?php
require 'check-session-user-prosel.php';
include 'connection.php';

$eSocial = $_FILES['esocial'];
$tituloEleitor = $_FILES['titulo_eleitor'];
$reservista = $_FILES['reservista'] ?? null;
$cpfDependentes = $_FILES['family-cpfs'] ?? null;

$id = $_SESSION['id'];
$query = "SELECT esocial, cpf_dependentes, titulo_eleitor, reservista,sexo,possui_dependentes FROM usuario_prosel WHERE id = '$id'";
$dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);


$requiredFileFields = array();
$emptyColumns = array();
$dataColumns = array();



if (empty($dados[0]['esocial'])) {
    array_push($requiredFileFields, 'esocial');
}

if (empty($dados[0]['cpf_dependentes']) && $dados[0]['possui_dependentes'] == 1) {
    array_push($requiredFileFields, 'family-cpfs');
}

if (empty($dados[0]['reservista']) && $dados[0]['sexo'] == 'M') {
    array_push($requiredFileFields, 'reservista');
}

if (empty($dados[0]['titulo_eleitor'])) {
    array_push($requiredFileFields, 'titulo_eleitor');
}


foreach ($requiredFileFields as $fileInput) {
    if (empty($_FILES[$fileInput]['size'])) {
        $errors[$fileInput] = 'Preencha este campo ou Arquivo Vazio';
    }
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['message'] = 'Preencha todos os campos obrigatórios';
    $data['errors'] = $errors;
    echo json_encode($data);
    exit;
}




$fileFields = array();

if (!empty($eSocial['size'])) {
    array_push($fileFields, 'esocial');
}

if (!empty($tituloEleitor['size'])) {
    array_push($fileFields, 'titulo_eleitor');
}

if (!empty($reservista['size'])) {
    array_push($fileFields, 'reservista');
}

if (!empty($cpfDependentes['size'])) {
    array_push($fileFields, 'family-cpfs');
}


$allowedExtension = array('pdf', 'png', 'jpg', 'jpeg', 'docx', 'doc', 'PDF', 'PNG', 'JPG', 'JPEG', 'DOCX', 'DOC');

foreach ($fileFields as $fileInput) {
    $filename = $_FILES[$fileInput]['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!empty($ext))
        if (!in_array($ext, $allowedExtension)) {
            $errors[$fileInput] = "Arquivos ." . $ext . " não são aceitos";
        }
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['message'] = 'Só são aceitos os seguintes formatos de arquivo: PDF,JPG,PNG,JPEG,docx e doc';
    $data['errors'] = $errors;
    echo json_encode($data);
    exit;
}



// Max Size File Validation

$max_size = 10485760 * 1.5; // 10MB;

foreach ($fileFields as $fileInput) {
    if ($_FILES[$fileInput]['size'] > $max_size) {
        $errors[$fileInput] = "Arquivo maior que 15MB";
    }
}

if (!empty($errors)) {
    $data['success'] = false;
    $data['message'] = 'Só são aceitos arquivos com tamanho de até 15MB';
    $data['errors'] = $errors;
    echo json_encode($data);
    exit;
}

$query = "UPDATE usuario_prosel SET ";



$ok = true;

if (!empty($eSocial["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $eSocial["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "eSocial_" . "$name";
    move_uploaded_file($eSocial["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET esocial =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($tituloEleitor["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $tituloEleitor["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "TituloEleitor_" . $name;
    move_uploaded_file($tituloEleitor["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET titulo_eleitor =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($reservista["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $reservista["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "Reservista_" . $name;
    move_uploaded_file($reservista["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET reservista =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($cpfDependentes["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $cpfDependentes["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "DocsDependentes_" . $name;
    move_uploaded_file($cpfDependentes["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET cpf_dependentes =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
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
