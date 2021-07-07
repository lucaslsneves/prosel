<?php
require 'check-session-user-prosel.php';
include 'connection.php';

$wedding = $_FILES['wedding'] ?? null;
$childrenDocs = $_FILES['children-docs'] ?? null;
$childrenVaccination = $_FILES['children-vaccination'] ?? null;
$childreSchool = $_FILES['children-school'] ?? null;

$id = $_SESSION['id'];
$query = "SELECT certidao_casamento,rg_dependentes,vacinacao_dependentes,comprovante_escolar_dependentes FROM usuario_prosel WHERE id = '$id'";
$dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);




$fileFields = array();

if (!empty($wedding['size'])) {
    array_push($fileFields, 'wedding');
}

if (!empty($childrenDocs['size'])) {
    array_push($fileFields, 'children-docs');
}

if (!empty($childrenVaccination['size'])) {
    array_push($fileFields, 'children-vaccination');
}

if (!empty($childreSchool['size'])) {
    array_push($fileFields, 'children-school');
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




$ok = true;

if (!empty($wedding["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $wedding["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "Casamento_" . "$name";
    move_uploaded_file($wedding["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET certidao_casamento =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($childrenDocs["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $childrenDocs["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "RgDependentes_" . $name;
    move_uploaded_file($childrenDocs["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET rg_dependentes =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($childrenVaccination["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $childrenVaccination["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "VacinacaoFilhos_" . $name;
    move_uploaded_file($childrenVaccination["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET vacinacao_dependentes =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
}

if (!empty($childreSchool["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $childreSchool["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "ComprovanteEscolar_" . $name;
    move_uploaded_file($childreSchool["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET comprovante_escolar_dependentes =  '$path' WHERE id = $id;";
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

