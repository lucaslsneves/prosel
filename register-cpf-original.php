<?php

$errors = [];
$data = [];
// Conexão com o banco de dados
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    include 'connection.php';
    
    function validaCPF($cpf) {
     
        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
         
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
    
    if(!isset($_POST['cpf'])){
        header("Location: control-panel.php");
        exit;
    }
    // mysql_real_escape_string prevent sql injection
    
    $cpf =  mysqli_real_escape_string($mysqli,$_POST['cpf']);
    $funcao =  mysqli_real_escape_string($mysqli,$_POST['funcao']);
    
    $requiredTextFields = array('cpf','funcao');
    
    // Required Fields Validation
    
    foreach($requiredTextFields as $textInput){
        if(empty($_POST[$textInput])){
            $errors[$textInput] = 'Este campo é obrigatório';
        }
    }
    
    if(!empty($errors)) {
        $data['success'] = false;
        $data['message'] = 'Preencha todos os campos obrigatórios';
        $data['errors'] = $errors;
        echo json_encode($data);
    }else {
        // CPF Validation
        if(!validaCPF($_POST['cpf'])){
            $errors['cpf'] = "CPF inválido";
        }
    
        if(!empty($errors)) {
            $data['success'] = false;
            $data['message'] = 'CPF inválido';
            $data['errors'] = $errors;
            echo json_encode($data);
        }else {
            // CPF is already registed
            $stmt = $mysqli->prepare("SELECT cpf FROM auth_users_prosel WHERE cpf = ?");
            $stmt->bind_param("s", $cpf);
            $stmt->execute();
            $cpfResult = $stmt->get_result();
            $cpfExist = $cpfResult->fetch_row();
    
            if($cpfExist != null) {
                $data['success'] = false;
                $data['message'] = 'CPF já cadastrado';
                $errors['cpf'] = 'CPF já cadastrado';
                $data['errors'] =  $errors;
                echo json_encode($data);
            }else {
                $sql = "INSERT INTO auth_users_prosel (cpf,funcao) VALUES ('$cpf' , '$funcao')";                                      
                if ($mysqli->query($sql) === true) {  
                    $data['success'] = true;
                    $data['message'] = 'CPF cadastrado com sucesso!';
                    $data['errors'] = null;
                    echo json_encode($data);
                } else {
                    $data['success'] = false;
                    $data['message'] =  'Erro inesperado,tente novamente mais tarde';
                    echo json_encode($data);
                }
    
            }
    
    }
    
}  
}catch(Exception $e) {
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
    exit;
}
