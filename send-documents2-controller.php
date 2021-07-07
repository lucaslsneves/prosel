<?php
include 'connection.php';
require 'check-session-user-prosel.php';


$foto3x4 = $_FILES['foto3x4'];
$comprovanteEndereco = $_FILES['comprovante'];
$rg = $_FILES['rg'];
$pis = $_FILES['pis'];
$cnh = $_FILES['cnh'];

$id = $_SESSION['id'];

$query = "SELECT comprovante_endereco, rg, cartao_pis,foto3x4,cnh FROM usuario_prosel WHERE id = '$id'";
$dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);


$requiredFileFields = array();
$emptyColumns = array();
$dataColumns = array();



if(empty($dados[0]['comprovante_endereco'])){
    array_push($requiredFileFields,'comprovante');
    array_push($emptyColumns,'comprovante_endereco');
}else {
    array_push($dataColumns,'comprovante_endereco');
}

if(empty($dados[0]['rg'])){
    array_push($requiredFileFields,'rg');
    array_push($emptyColumns,'rg');
}else {
    array_push($dataColumns,'rg');
}

if(empty($dados[0]['cartao_pis'])){
    array_push($requiredFileFields,'pis');
    array_push($emptyColumns,'cartao_pis');
}else {
    array_push($dataColumns,'cartao_pis');
}


if(empty($dados[0]['foto3x4'])){
    array_push($requiredFileFields,'foto3x4');
    array_push($emptyColumns,'foto3x4');
}else {
    array_push($dataColumns,'foto3x4');
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




$allowedExtension = array('pdf', 'png', 'jpg', 'jpeg', 'docx', 'doc','PDF', 'PNG', 'JPG', 'JPEG', 'DOCX', 'DOC');

$fileFields = array();

if(!empty($rg["tmp_name"])) {
    array_push($fileFields ,'rg');
}

if(!empty($comprovanteEndereco["tmp_name"])) {
    array_push($fileFields ,'comprovante');
}

if(!empty($pis["tmp_name"])) {
    array_push($fileFields ,'pis');
}

if(!empty($foto3x4["tmp_name"])) {
    array_push($fileFields ,'foto3x4');
}



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
  if (!empty($comprovanteEndereco['size'])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $comprovanteEndereco["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "ComprovanteEndereco_" . "$name";
    move_uploaded_file($comprovanteEndereco["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET comprovante_endereco =  '$path' WHERE id = $id;";
   $ok = $mysqli->query($query);
  }

  if (!empty($rg['size'])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $rg["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "Rg_" . $name;
    move_uploaded_file($rg["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET rg =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
  }

  if (!empty($pis['size'])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $pis["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "CartaoPIS_" . $name;
    move_uploaded_file($pis["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET cartao_pis =  '$path' WHERE id = $id;";
    $ok = $mysqli->query($query);
  }

  if (!empty($foto3x4['size'])) {
    preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $foto3x4["name"], $ext);
    $name = md5(uniqid(time())) . "." . $ext[1];
    $path = "docs/$id" . "Foto3x4_" . $name;
    move_uploaded_file($foto3x4["tmp_name"], $path);
    $query = "UPDATE usuario_prosel SET foto3x4 =  '$path' WHERE id = $id;";
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




  
 

 
