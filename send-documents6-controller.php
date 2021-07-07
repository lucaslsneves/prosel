<?php
require 'check-session-user-prosel.php';
include 'connection.php';

$carteiraConselho = $_FILES['carteira_conselho'] ?? null;
$contaBancaria = $_FILES['conta_bancaria'] ?? null;
$especializacoes = $_FILES['especializacoes'] ?? null;
$cnh = $_FILES['cnh'] ?? null;


$id = $_SESSION['id'];
$query = "SELECT carteira_conselho,especializacoes,conta_bancaria,cnh FROM usuario_prosel WHERE id = '$id'";
$dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);

$fileFields = array();

if (!empty($carteiraConselho['size'])) {
    array_push($fileFields, 'carteira_conselho');
}

if (!empty($contaBancaria['size'])) {
    array_push($fileFields, 'conta_bancaria');
}

if (!empty($especializacoes['size'])) {
    array_push($fileFields, 'especializacoes');
}

if (!empty($cnh['size'])) {
    array_push($fileFields, 'cnh');
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

if (!empty($carteiraConselho["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $carteiraConselho["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "CarteiraConselho_" . "$name";
    move_uploaded_file($carteiraConselho["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET carteira_conselho =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($contaBancaria["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $contaBancaria["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "ContaBancaria_" . $name;
    move_uploaded_file($contaBancaria["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET conta_bancaria =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($especializacoes["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $especializacoes["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "Especializacoes_" . $name;
    move_uploaded_file($especializacoes["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET especializacoes =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($cnh['size'])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $cnh["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "CNH_" . $name;
    move_uploaded_file($cnh["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET cnh =  '$path' WHERE id = $id;";
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

