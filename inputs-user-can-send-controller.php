<?php
require "verifica.php";

include "connection.php";

$errors = [];
$data = [];


$inputs = $_POST;
$deleteQuery = "DELETE from inputs_user_can_send where usuario_prosel_id =" . $inputs['id'];

$ok = $mysqli->query($deleteQuery);

if(count($inputs) != 1) {

   foreach($inputs as $key => $value) {
    if($key != 'id') {
        // Se o usuário marca e desmacar a checkbox seu valor fica falso
      if($value != false){
        $query = "INSERT into inputs_user_can_send (input_id , usuario_prosel_id) values (" . $value . "," . $inputs['id'] . ")";
        $ok = $mysqli->query($query);
        
      }
    }
   }
}

if($ok) {
    $data['success'] = true;
    $data['message'] = 'Formulário enviado com sucesso';
    echo json_encode($data);
}else {
    $data['success'] = false;
    $data['message'] = 'Erro ao enviar, atualize a página e tente novamente!';
    echo json_encode($data);
}


