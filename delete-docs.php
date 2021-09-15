<?php
require "verifica.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $data = [];
    include 'connection.php';
    if (!isset($_POST['id'])) {
        header("Location: control-panel.php");
        exit;
    }
     
$id =  $_POST["id"];

$fileFields = array(
    'foto3x4', 'comprovante_endereco', 'rg',  'cartao_pis', 'cartao_sus', 'cartao_vacinacao', 'diploma', 'curriculo', 'esocial',
    'conta_bancaria', 'reservista', 'especializacoes','cpf_dependentes','certidao_casamento','rg_dependentes','vacinacao_dependentes','comprovante_escolar_dependentes','carteira_conselho','titulo_eleitor'
);



$dados = $mysqli->query("SELECT * FROM usuario_prosel WHERE id = '$id'")->fetch_all(MYSQLI_ASSOC);
$dados = $dados[0];

// Deletar arquivos

/*foreach ($fileFields as $fileField) {
    $path = $dados[$fileField];

    if ($path) {
        unlink("$path");
    }
}*/

$query = "DELETE FROM `usuario_prosel` WHERE `id` = '$id'";

$isOk = $mysqli->query($query);
$data['message'] = $isOk;
echo json_encode($data);
} catch (Exception $e) {
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
}

