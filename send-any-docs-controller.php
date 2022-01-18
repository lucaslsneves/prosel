<?php
include 'connection.php';
require 'check-session-user-prosel.php';

$inputFiles = $_FILES;
$inputs = $_POST;

$id = $_SESSION['id'];

$allowedExtension = array('pdf', 'png', 'jpg', 'jpeg', 'docx', 'doc','PDF', 'PNG', 'JPG', 'JPEG', 'DOCX', 'DOC');

$ok = true;
try {
    foreach ($inputFiles as $key => $value) {
      
        $filename = $_FILES[$key]['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!empty($ext))
            if (!in_array($ext, $allowedExtension)) {
                $data['success'] = false;
                $data['message'] = 'Só são aceitos imagens, documento Word ou PDF, verifique os arquivos que você enviou';
                echo json_encode($data);
                exit;
            }
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
    $unity =  $_SESSION['prosel'];

    if ($unity === 'Sede') {
        $users = $mysqli->query("SELECT id FROM aut_users WHERE role = 'Sede'  OR role = 'dp' OR role = 'rh'")->fetch_all(MYSQLI_ASSOC);
    } else {
        $users = $mysqli->query("SELECT id FROM aut_users WHERE role = '$unity'")->fetch_all(MYSQLI_ASSOC);
    }


    foreach ($users as $user) {
        // setting all columns values
        $userId = $user['id'];
        $candidateId = $_SESSION['id'];
        $candidateCpf = $_SESSION['cpf'];
        $unity = $_SESSION['prosel'];
        $candidateName = $_SESSION['nomeCompleto'];
        $title = $candidateName . " atualiazou documentos";
        $description = $candidateName . " (" . $candidateCpf . ") atualizou os seus documentos";
        $query = "INSERT INTO notifications (creator_usuario_prosel_id,reader_aut_user_id,title,description,unity) values (" . $candidateId . "," . $userId . ", '" . $title . "' , '" . $description . "' , '" . $unity . "')";

        $mysqli->query($query);
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
} catch (Exception $e) {
    $data['success'] = false;
    $data['message'] = 'Só são aceitos imagens, documento Word ou PDF, verifique os arquivos que você enviou';
    echo json_encode($data);
}
