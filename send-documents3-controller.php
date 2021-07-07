<?php
require 'check-session-user-prosel.php';
include 'connection.php';

$sus = $_FILES['sus'];
$vacinacao = $_FILES['vacinacao'];
$diploma = $_FILES['diploma'];
$curriculo = $_FILES['curriculo'];
$carteiraTrabalho = $_FILES['carteira_trabalho'];

$id = $_SESSION['id'];

$query = "SELECT cartao_sus, curriculo, cartao_vacinacao,diploma,carteira_trabalho FROM usuario_prosel WHERE id = '$id'";
$dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);


$requiredFileFields = array();
$emptyColumns = array();
$dataColumns = array();



if(empty($dados[0]['cartao_sus'])){
    array_push($requiredFileFields,'sus');
}

if(empty($dados[0]['curriculo'])){
   array_push($requiredFileFields,'curriculo');
}

if(empty($dados[0]['cartao_vacinacao'])){
    array_push($requiredFileFields,'vacinacao');
}


if(empty($dados[0]['diploma'])){
    array_push($requiredFileFields,'diploma');
}

if(empty($dados[0]['carteira_trabalho'])){
    array_push($requiredFileFields,'carteira_trabalho');
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

if(!empty($sus['size'])) {
    array_push($fileFields ,'sus');
}

if(!empty($vacinacao['size'])) {
    array_push($fileFields ,'vacinacao');
}

if(!empty($diploma['size'])) {
    array_push($fileFields ,'diploma');
}

if(!empty($curriculo['size'])) {
    array_push($fileFields ,'curriculo');
}

if(!empty($carteiraTrabalho['size'])) {
    array_push($fileFields ,'carteira_trabalho');
}


$allowedExtension = array('pdf', 'png', 'jpg', 'jpeg', 'docx', 'doc','PDF', 'PNG', 'JPG', 'JPEG', 'DOCX', 'DOC');

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

  $max_size = 10485760 * 1.5; // 15MB;

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

  if (!empty($sus["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $sus["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "CartaoSus_" . "$name";
    move_uploaded_file($sus["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET cartao_sus =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
  }

  if (!empty($vacinacao["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $vacinacao["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "CartaoVacinacao_" . $name;
    move_uploaded_file($vacinacao["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET cartao_vacinacao =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
  }

  if (!empty($diploma["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $diploma["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "Diploma_" . $name;
    move_uploaded_file($diploma["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET diploma =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
  }

  if (!empty($curriculo["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $curriculo["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "Curriculo_" . $name;
    move_uploaded_file($curriculo["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET curriculo =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
  }

  if (!empty($carteiraTrabalho["tmp_name"])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $carteiraTrabalho["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "CTPS_" . $name;
    move_uploaded_file($carteiraTrabalho["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET carteira_trabalho =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
  }



  if($ok == true) {
    $data['success'] = true;
    $data['message'] = 'Formulário enviado com sucesso';
    echo json_encode($data);
    exit;
  }else {
    $data['success'] = false;
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
  }




  
 

 
