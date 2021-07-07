<?php
// Conexão com o banco de dados
try {
$data = [];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include 'connection.php';

session_start();

if(!isset($_POST['login']) || !isset($_POST['senha'])){
    header("Location: admin.php");
    exit;
}

    $login = $_POST['login'];
    $senha = md5($_POST['senha']);

    $SQL = "SELECT id, login, senha, role
    FROM aut_users
    WHERE login = '$login'";
    
    $result_id = $mysqli -> query($SQL);
    $total = mysqli_num_rows($result_id);

    if($total){
    
        $dados = mysqli_fetch_array($result_id);
        if(!strcmp($senha, $dados["senha"])){
            $_SESSION["id_usuario"] = $dados["id"];
            $_SESSION["login_usuario"] = $dados["login"];
            $_SESSION['role'] = $dados["role"];
            $data['success'] = true;
            echo json_encode($data);
        }else {
            $data['success'] = false;
            $data['message'] = 'Login ou senha inválidos';
            echo json_encode($data);
        }
    
    }else {
        $data['success'] = false;
        $data['message'] = 'Login ou senha inválidos';
        echo json_encode($data);
    }

}catch(Exception $e){
    print_r($e);
    $data['success'] = false;
    $data['message'] = 'Erro inesperado';
    echo json_encode($data);
   
}
