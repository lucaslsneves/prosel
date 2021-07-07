<?php
$errors = [];
$data = [];
// Conexão com o banco de dados
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqli = new mysqli("localhost", "root", "", "prosel");
    $result = $mysqli->query("SELECT DATABASE()");
    $row = $result->fetch_row();
    $mysqli->select_db("prosel");

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

    // Get form data

    // mysql_real_escape_string prevent sql injection

    $cpf =  mysqli_real_escape_string($mysqli, $_POST['cpf']);
    $nome = mysqli_real_escape_string($mysqli, $_POST['nome']);
    $sexo = mysqli_real_escape_string($mysqli, $_POST['gender']);
    $dependents = mysqli_real_escape_string($mysqli, $_POST['dependents']);
    $prosel = mysqli_real_escape_string($mysqli, $_POST['prosel']);

    $requiredTextFields = array('cpf', 'nome', 'gender','dependents','prosel');

    $foto3x4 = $_FILES['foto3x4'];
    $comprovanteEndereco = $_FILES['comprovante'];
    $rg = $_FILES['rg'];
    $pis = $_FILES['pis'];
    $sus = $_FILES['sus'];
    $vacinacao = $_FILES['vacinacao'];
    $diploma = $_FILES['diploma'];
    $curriculo = $_FILES['curriculo'];
    $esocial = $_FILES['esocial'];
    $titulo = $_FILES['titulo_eleitor'];
    
    $requiredFileFields = array(
        'foto3x4', 'comprovante', 'rg', 'pis', 'sus', 'vacinacao', 'diploma', 'curriculo', 'esocial','titulo_eleitor'
    );

    if($dependents == 'S'){
        array_push($requiredFileFields,'family-cpfs');
    }

    $conta_bancaria = $_FILES['conta_bancaria'];
    $reservista = $_FILES['reservista'];
    $especializacoes = $_FILES['especializacoes'];
    $carteira_conselho = $_FILES['carteira_conselho'];

    $familyCpfs =  $_FILES['family-cpfs'];
    $wedding = $_FILES['wedding'];
    $childrenDocs = $_FILES['children-docs'];
    $childrenVaccination = $_FILES['children-vaccination'];
    $childrenSchool = $_FILES['children-school'];
    

    $fileFields = array(
        'foto3x4', 'comprovante', 'rg', 'pis', 'sus', 'vacinacao', 'diploma', 'curriculo', 'esocial','titulo_eleitor',
        'conta_bancaria', 'reservista', 'especializacoes','family-cpfs','wedding','children-docs','children-vaccination','children-school','carteira_conselho'
    );

    // Required fields validation

    foreach ($requiredTextFields as $textInput) {
        if (empty($_POST[$textInput])) {
            $errors[$textInput] = 'Este campo é obrigatório';
        }
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
    } else {

        // File Extension Validation
        $allowedExtension = array('pdf', 'png', 'jpg', 'jpeg', 'docx', 'doc');

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
        } else {
            

            // Size File Validation

            $max_size = 10485760; // 10MB;

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


                    // CPF is known validation
                    $query = "SELECT cpf FROM auth_users_prosel WHERE cpf ='$cpf'";
                    $stmt = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);

                    if (!isset($stmt[0]['cpf'])) {
                        $data['success'] = false;
                        $data['message'] = 'CPF não cadastrado na base de dados , entre em contato com o RH';
                        $errors['cpf'] = 'CPF desconhecido';
                        $data['errors'] =  $errors;
                        echo json_encode($data);
                    } else {
                        // Gender Validation
                        if (!($sexo == "M" || $sexo == "F")) {
                            $data['success'] = false;
                            $data['message'] = 'Sexo inválido';
                            $data['errors'] = null;
                            echo json_encode($data);
                        } else {

                            $caminho_especializacoes = null;
                            $caminho_conta_bancaria = null;
                            $caminho_reservista = null;
                            $caminho_carteira_conselho = null;

                            $caminho_wedding = null;
                            $caminho_familyCpfs = null;
                            $caminho_childrenDocs = null;
                            $caminho_childrenVaccination = null;
                            $caminho_childrenSchool = null;

                            // DEPENDENTES DE IMPOSTO DE RENDA

                            if (file_exists($familyCpfs['tmp_name']) && $dependents == 'S') {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $familyCpfs["name"], $ext11);
                                $nome_familyCpfs = md5(uniqid(time())) . "." . $ext11[1];
                                $caminho_familyCpfs = "docs/CPFsDependentes_" . $nome_familyCpfs;
                                move_uploaded_file($familyCpfs["tmp_name"], $caminho_familyCpfs);
                            }

                            if (file_exists($wedding['tmp_name'])) {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $wedding["name"], $ext11);
                                $nome_wedding = md5(uniqid(time())) . "." . $ext11[1];
                                $caminho_wedding = "docs/Casamento_" . $nome_wedding;
                                move_uploaded_file($wedding["tmp_name"], $caminho_wedding);
                            }
                            if (file_exists($childrenDocs['tmp_name'])) {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $childrenDocs["name"], $ext11);
                                $nome_childrenDocs = md5(uniqid(time())) . "." . $ext11[1];
                                $caminho_childrenDocs = "docs/RgFilhos_" . $nome_childrenDocs;
                                move_uploaded_file($childrenDocs["tmp_name"], $caminho_childrenDocs);
                            }
                            if (file_exists($childrenVaccination['tmp_name'])) {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $childrenVaccination["name"], $ext11);
                                $nome_childrenVaccination = md5(uniqid(time())) . "." . $ext11[1];
                                $caminho_childrenVaccination = "docs/VacinacaoFilhos_" . $nome_childrenVaccination;
                                move_uploaded_file($childrenVaccination["tmp_name"], $caminho_childrenVaccination);
                            }
                            if (file_exists($childrenSchool['tmp_name'])) {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $childrenSchool["name"], $ext11);
                                $nome_childrenSchool = md5(uniqid(time())) . "." . $ext11[1];
                                $caminho_childrenSchool = "docs/EscolaFilhos_" . $nome_childrenSchool;
                                move_uploaded_file($childrenSchool["tmp_name"], $caminho_childrenSchool);
                            }
                         
                            //

                            if (file_exists($carteira_conselho['tmp_name'])) {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $conta_bancaria["name"], $ext11);
                                $nome_carteira_conselho = md5(uniqid(time())) . "." . $ext11[1];
                                $caminho_carteira_conselho = "docs/CarteiraConselho_" . $nome_carteira_conselho;
                                move_uploaded_file($carteira_conselho["tmp_name"], $caminho_carteira_conselho);
                            }


                            if (file_exists($conta_bancaria['tmp_name'])) {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $conta_bancaria["name"], $ext11);
                                $nome_conta_bancaria = md5(uniqid(time())) . "." . $ext11[1];
                                $caminho_conta_bancaria = "docs/ContaBancaria_" . $nome_conta_bancaria;
                                move_uploaded_file($conta_bancaria["tmp_name"], $caminho_conta_bancaria);
                            }

                       

                            if (file_exists($reservista['tmp_name']) && $sexo == 'M') {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $reservista["name"], $ext12);
                                $nome_reservista = md5(uniqid(time())) . "." . $ext12[1];
                                $caminho_reservista = "docs/Reservista_" . $nome_reservista;
                                move_uploaded_file($reservista["tmp_name"], $caminho_reservista);
                            }

                            if (file_exists($especializacoes['tmp_name'])) {
                                preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg){1}$/i", $especializacoes["name"], $ext12);
                                $nome_especializacoes = md5(uniqid(time())) . "." . $ext12[1];
                                $caminho_especializacoes = "docs/Especializacoes_" . $nome_especializacoes;
                                move_uploaded_file($especializacoes["tmp_name"], $caminho_especializacoes);
                            }
                            // Pega extensão da imagem
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $foto3x4["name"], $ext);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $comprovanteEndereco["name"], $ext2);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $rg["name"], $ext3);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $pis["name"], $ext4);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $sus["name"], $ext5);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $vacinacao["name"], $ext6);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $diploma["name"], $ext7);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $curriculo["name"], $ext8);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $esocial["name"], $ext10);
                            preg_match("/\.(gif|docx|doc|bmp|pdf|png|jpg|jpeg){1}$/i", $titulo["name"], $ext10);
                            
                            // Gera um nome único para a imagem
                            $nome_foto3x4 = md5(uniqid(time())) . "." . $ext[1];
                            $nome_comprovanteEndereco = md5(uniqid(time())) . "." . $ext2[1];
                            $nome_rg = md5(uniqid(time())) . "." . $ext3[1];
                            $nome_pis = md5(uniqid(time())) . "." . $ext4[1];
                            $nome_sus = md5(uniqid(time())) . "." . $ext5[1];
                            $nome_vacinacao = md5(uniqid(time())) . "." . $ext6[1];
                            $nome_diploma = md5(uniqid(time())) . "." . $ext7[1];
                            $nome_curriculo = md5(uniqid(time())) . "." . $ext8[1];
                            $nome_esocial = md5(uniqid(time())) . "." . $ext10[1];
                            $nome_titulo_eleitor = md5(uniqid(time())) . "." . $ext10[1];

                            // Caminho de onde ficará a imagem
                            $caminho_foto3x4 = "docs/Foto3x4_" . $nome_foto3x4;
                            $caminho_comprovanteEndereco = "docs/ComprovanteEndereco_" . $nome_comprovanteEndereco;
                            $caminho_rg = "docs/Rg_" . $nome_rg;
                            $caminho_pis = "docs/CartaoPIS_" . $nome_pis;
                            $caminho_sus = "docs/CartaoSUS_" . $nome_sus;
                            $caminho_vacinacao = "docs/CartaoVacinacao_" . $nome_vacinacao;
                            $caminho_diploma = "docs/Diploma_" . $nome_diploma;
                            $caminho_curriculo = "docs/Curriculo_" . $nome_curriculo;
                            $caminho_esocial = "docs/eSocial_" . $nome_esocial;
                            $caminho_titulo_eleitor = "docs/TituloEleitor_" .  $nome_titulo_eleitor;
                            // Faz o upload da imagem para seu respectivo caminho
                            move_uploaded_file($foto3x4["tmp_name"], $caminho_foto3x4);
                            move_uploaded_file($comprovanteEndereco["tmp_name"], $caminho_comprovanteEndereco);
                            move_uploaded_file($rg["tmp_name"], $caminho_rg);
                            move_uploaded_file($pis["tmp_name"], $caminho_pis);
                            move_uploaded_file($sus["tmp_name"], $caminho_sus);
                            move_uploaded_file($vacinacao["tmp_name"], $caminho_vacinacao);
                            move_uploaded_file($diploma["tmp_name"], $caminho_diploma);
                            move_uploaded_file($curriculo["tmp_name"], $caminho_curriculo);
                            move_uploaded_file($esocial["tmp_name"], $caminho_esocial);
                            move_uploaded_file($titulo["tmp_name"], $caminho_titulo_eleitor);

                            /*
                            If CPF is already registered update the row instead insert another one 
                            and delete the old files
                          */

                            $dados = $mysqli->query("SELECT * FROM usuario_prosel WHERE cpf = '$cpf'")->fetch_all(MYSQLI_ASSOC);


                           
                            $sqlInsert = "INSERT INTO usuario_prosel (nome_completo, cpf, foto3x4, sexo, comprovante_endereco, rg, cartao_pis, cartao_sus, cartao_vacinacao, diploma, curriculo, conta_bancaria, esocial, especializacoes, reservista,cpf_dependentes,certidao_casamento,rg_dependentes,vacinacao_dependentes,comprovante_escolar_dependentes,prosel,titulo_eleitor,carteira_conselho)
                        VALUES ('$nome','$cpf','$caminho_foto3x4','$sexo','$caminho_comprovanteEndereco', '$caminho_rg', '$caminho_pis', '$caminho_sus', '$caminho_vacinacao', '$caminho_diploma', '$caminho_curriculo', '$caminho_conta_bancaria', '$caminho_esocial', '$caminho_especializacoes', '$caminho_reservista','$caminho_familyCpfs','$caminho_wedding','$caminho_childrenDocs','$caminho_childrenVaccination','$caminho_childrenSchool','$prosel','$caminho_titulo_eleitor','$caminho_carteira_conselho')";


                            if (empty($dados[0]['id'])) {
                                if ($mysqli->query($sqlInsert) === true) {
                                    $data['success'] = true;
                                    $data['message'] = 'Formulário enviado com sucesso';
                                    echo json_encode($data);
                                } else {
                                    $data['success'] = false;
                                    $data['message'] =  'Erro inesperado,tente novamente mais tarde';
                                    echo json_encode($data);
                                }
                            } else {
                                $id = $dados[0]['id'];
                                $dados = $dados[0];
                                $sqlUpdate = "
                                UPDATE usuario_prosel
                                SET  
                                nome_completo = '$nome',
                                cpf = '$cpf',
                                foto3x4 = '$caminho_foto3x4',
                                sexo ='$sexo' , 
                                comprovante_endereco = '$caminho_comprovanteEndereco', 
                                rg = '$caminho_rg',
                                cartao_pis = '$caminho_pis',
                                cartao_sus = '$caminho_sus',
                                cartao_vacinacao = '$caminho_vacinacao', 
                                diploma ='$caminho_diploma' ,
                                curriculo = '$caminho_curriculo',
                                conta_bancaria = '$caminho_conta_bancaria' ,
                                esocial =  '$caminho_esocial',
                                especializacoes = '$caminho_especializacoes',
                                reservista = '$caminho_reservista',
                               cpf_dependentes = '$caminho_familyCpfs',
                               certidao_casamento = '$caminho_wedding',
                               rg_dependentes = '$caminho_childrenDocs' ,
                               vacinacao_dependentes = '$caminho_childrenVaccination',
                               comprovante_escolar_dependentes = '$caminho_childrenSchool',
                               prosel= '$prosel',
                               titulo_eleitor = '$caminho_titulo_eleitor',
                               carteira_conselho = '$caminho_carteira_conselho'
                                WHERE
                                id = '$id'
                           ";

                                if ($mysqli->query($sqlUpdate) == true) {
                                    $data['success'] = true;
                                    $data['message'] = 'Formulário enviado com sucesso';
                                    echo json_encode($data);

                                    //Delete old files

                                    $fileFieldsColumns = array(
                                        'foto3x4', 'comprovante_endereco', 'rg',  'cartao_pis', 'cartao_sus', 'cartao_vacinacao', 'diploma', 'curriculo', 'esocial',
                                        'conta_bancaria', 'reservista', 'especializacoes','cpf_dependentes','certidao_casamento','rg_dependentes','vacinacao_dependentes','comprovante_escolar_dependentes','carteira_conselho','titulo_eleitor'
                                    );

                                    foreach ($fileFieldsColumns as $fileField) {
                                       
                                        $path = $dados[$fileField];

                                        if ($path) {
                                            unlink("$path");
                                        }
                                    }
                                } else {
                                    $data['success'] = false;
                                    $data['message'] =  'Erro inesperado,tente novamente mais tarde';
                                    echo json_encode($data);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
} catch (Exception $e) {
    print_r($e);
    $data['success'] = false;
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
}
