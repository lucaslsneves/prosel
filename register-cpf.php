<?php

$errors = [];
$data = [];
// Conexão com o banco de dados
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    require "verifica.php";
    include 'connection.php';
    
    function validaCPF($cpf)
    {

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

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

    if (!isset($_POST['cpf'])) {
        header("Location: control-panel.php");
        exit;
    }
    // mysql_real_escape_string prevent sql injection

    $cpf =  mysqli_real_escape_string($mysqli, $_POST['cpf']);
    $funcao =  mysqli_real_escape_string($mysqli, $_POST['funcao']);
    $prosel =  mysqli_real_escape_string($mysqli, isset($_POST['prosel']));

    $requiredTextFields = array('cpf', 'funcao');
    $role = $_SESSION['role'];

    if ($role == 'dp' || $role == 'Sede' || $role == 'admin') {
        array_push($requiredTextFields, 'prosel');
    }


    // Required Fields Validation

    foreach ($requiredTextFields as $textInput) {
        if (empty($_POST[$textInput])) {
            $errors[$textInput] = 'Este campo é obrigatório';
        }
    }

    if (!empty($errors)) {
        $data['success'] = false;
        $data['message'] = 'Preencha todos os campos obrigatórios';
        $data['errors'] = $errors;
        echo json_encode($data);
    } else {
        // CPF Validation
        if (!validaCPF($_POST['cpf'])) {
            $errors['cpf'] = "CPF inválido";
        }

        if (!empty($errors)) {
            $data['success'] = false;
            $data['message'] = 'CPF inválido';
            $data['errors'] = $errors;
            echo json_encode($data);
        } else {
            // CPF is already registered
            $stmt = $mysqli->prepare("SELECT id,cpf FROM auth_users_prosel WHERE cpf = ?");
            $stmt->bind_param("s", $cpf);
            $stmt->execute();
            $cpfResult = $stmt->get_result();
            $cpfExist = $cpfResult->fetch_row();

            if ($cpfExist != null) {

                $id = $cpfExist[0];

                $docs = $mysqli->query("SELECT id FROM usuario_prosel WHERE cpf = '$cpf'");
                $docsId = $docs->fetch_all()[0][0];
                $updateProselQuery = null;
                if ($role == 'dp' || $role == 'Sede' || $role == 'admin') {
                    $updateProselQuery =
                        "UPDATE usuario_prosel
                    SET prosel = '$prosel'
                    WHERE id = $docsId;";
                } else {
                    $updateProselQuery =
                        "UPDATE usuario_prosel
                SET prosel = '$role'
                WHERE id = $docsId;";
                }

                $updateFuncaoQuery =
                    "UPDATE auth_users_prosel
                 SET funcao = '$funcao'
                 WHERE id = $id;";


                $mysqli->begin_transaction();
                $mysqli->query($updateFuncaoQuery);
                $mysqli->query($updateProselQuery);
                $mysqli->commit();

                $data['success'] = true;
                $data['message'] = 'Candidato atualizado com sucesso';
                $data['errors'] = null;
                echo json_encode($data);
                exit;
            } else {
                $mysqli->begin_transaction();
                $sql = "INSERT INTO auth_users_prosel (cpf,funcao) VALUES ('$cpf' , '$funcao')";
                $sql2 = null;
                if ($role == 'dp' || $role == 'Sede' || $role == 'admin') {
                    $sql2 = "INSERT INTO usuario_prosel (cpf,prosel) VALUES ('$cpf' , '$prosel')";
                }else {
                    $sql2 = "INSERT INTO usuario_prosel (cpf,prosel) VALUES ('$cpf' , '$role')";
                }
            
                $mysqli->query($sql);
                $mysqli->query($sql2);
                $mysqli->commit();
                $data['success'] = true;
                $data['message'] = 'CPF cadastrado com sucesso!';
                $data['errors'] = null;
                echo json_encode($data);
                exit;
            }
        }
    }
} catch (Exception $e) {
    $mysqli->rollback();
    $data['success'] = false;
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
    exit;
}
