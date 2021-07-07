<?php
require "verifica.php";
include 'connection.php';

$page = 1;

if (!empty($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if (false === $page) {
        $page = 1;
    }
}

// set the number of items to display per page
$items_per_page = 9;

// build query
$offset = ($page - 1) * $items_per_page;

if (empty($_GET['like'])) {
    $nome = "";
} else {
    $nome = $_GET['like'];
}

$query = null;
$queryAll = null;

if (empty($_GET['prosel'])) {
    $prosel = "";
    $query = "SELECT * FROM usuario_prosel WHERE nome_completo LIKE '%$nome%'  or cpf LIKE '%$nome%' ORDER BY updated_at DESC LIMIT " . $offset . "," . $items_per_page;
    $queryAll = "SELECT COUNT(*) FROM usuario_prosel WHERE nome_completo LIKE '%$nome%' or cpf LIKE '%$nome%'";
} else {
    $prosel = $_GET['prosel'];
    $query = "SELECT * FROM usuario_prosel WHERE prosel = '$prosel' and  (nome_completo LIKE '%$nome%'  or cpf LIKE '%$nome%')  ORDER BY updated_at DESC LIMIT " . $offset . "," . $items_per_page;
    $queryAll = "SELECT COUNT(*) FROM usuario_prosel WHERE (nome_completo LIKE '%$nome%' or cpf LIKE '%$nome%') and prosel = '$prosel'";
}


$docsAmount = $mysqli->query($queryAll)->fetch_all(MYSQLI_ASSOC);
$docsCount = $docsAmount[0]['COUNT(*)'];
$docsMaxPages = ceil($docsCount / $items_per_page);
$docs = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);


$cpfs = '';
$cpfToFuncao = [];

foreach ($docs as $user) {
    if (empty($cpfs)) {
        $cpfs = "'" . $user['cpf'] . "'";
    } else {
        $cpfs .= ',' . "'" . $user['cpf'] . "'";
    }
}
$queryFuncoes = "SELECT * FROM `auth_users_prosel` where cpf in (" . $cpfs . ")";

$dadosFuncoes = $mysqli->query($queryFuncoes)->fetch_all(MYSQLI_ASSOC);

foreach ($dadosFuncoes as $user) {
    $cpfToFuncao[$user['cpf']] = $user['funcao'];
}



?>

<table id="table" maxPage="<?php echo $docsMaxPages ?>">
    <thead>
        <tr>
            <th scope="col">Nome</th>
            <th scope="col">CPF</th>
            <th scope="col">Sexo</th>
            <th scope="col">Estado Civil</th>
            <th scope="col">CNH</th>
            <th scope="col">CTPS</th>
            <th scope="col">Foto3x4</th>
            <th scope="col">Endereço</th>
            <th scope="col">RG</th>
            <th scope="col">PIS</th>
            <th scope="col">SUS</th>
            <th scope="col">Vacinação</th>
            <th scope="col">Diploma</th>
            <th scope="col">Currículo</th>
            <th scope="col">e-Social</th>
            <th scope="col">C. Bancária</th>
            <th scope="col">Espec.</th>
            <th scope="col">Reservista</th>
            <th scope="col">CPF Depen.</th>
            <th scope="col">Casamento/União</th>
            <th scope="col">RG/Certidão Depen.</th>
            <th scope="col">Vacinação Depen.</th>
            <th scope="col">Escola Depen.</th>
            <th scope="col">Titulo Eleitor</th>
            <th scope="col">Carteira Conselho</th>
            <th scope="col">Prosel</th>
            <th scope="col">Função</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($docs as $item) {
        ?> <tr>
                <td data-label="Nome"><?php print_r($item['nome_completo'])   ?></td>
                <td data-label="CPF"><?php print_r($item['cpf']) ?></td>
                <td data-label="Sexo"><?php print_r($item['sexo']) ?></td>
                <td data-label="Estado Civil"><?php print_r($item['estado_civil']) ?></td>
                <td data-label="CNH"><?php if ($item['cnh'] != '') { ?><a target="_blank" href="<?php print_r($item['cnh']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="CTPS"><?php if ($item['carteira_trabalho'] != '') { ?><a target="_blank" href="<?php print_r($item['carteira_trabalho']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="Foto3x4"><?php if ($item['foto3x4'] != '') { ?><a target="_blank" href="<?php print_r($item['foto3x4']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="C. Endereço"><?php if ($item['comprovante_endereco'] != '') { ?><a target="_blank" href="<?php print_r($item['comprovante_endereco']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img><?php } ?></a></td>
                <td data-label="RG"><?php if ($item['rg'] != '') { ?><a target="_blank" href="<?php print_r($item['rg']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="PIS"><?php if ($item['cartao_pis'] != '') { ?><a target="_blank" href="<?php print_r($item['cartao_pis']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="SUS"><?php if ($item['cartao_sus'] != '') { ?><a target="_blank" href="<?php print_r($item['cartao_sus']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="C. Vacinação"><?php if ($item['cartao_vacinacao'] != '') { ?><a target="_blank" href="<?php print_r($item['cartao_vacinacao']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="Diploma"><?php if ($item['diploma'] != '') { ?><a target="_blank" href="<?php print_r($item['diploma']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="Currículo"><?php if ($item['curriculo'] != '') { ?><a target="_blank" href="<?php print_r($item['curriculo']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="e-Social"><?php if ($item['esocial'] != '') { ?><a target="_blank" href="<?php print_r($item['esocial']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a><?php } ?></td>
                <td data-label="C. Bancária"><?php if ($item['conta_bancaria'] != '') { ?><a target="_blank" href="<?php print_r($item['conta_bancaria']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Espec."><?php if ($item['especializacoes'] != '') { ?><a target="_blank" href="<?php print_r($item['especializacoes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Reserv."><?php if ($item['reservista'] != '') { ?><a target="_blank" href="<?php print_r($item['reservista']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="CPF depen"><?php if ($item['cpf_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['cpf_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Certidão/união"><?php if ($item['certidao_casamento'] != '') { ?><a target="_blank" href="<?php print_r($item['certidao_casamento']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="RG depen"><?php if ($item['rg_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['rg_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Vacinação"><?php if ($item['vacinacao_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['vacinacao_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Escola depen"><?php if ($item['comprovante_escolar_dependentes'] != '') { ?><a target="_blank" href="<?php print_r($item['comprovante_escolar_dependentes']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Vacinação"><?php if ($item['titulo_eleitor'] != '') { ?><a target="_blank" href="<?php print_r($item['titulo_eleitor']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Escola depen"><?php if ($item['carteira_conselho'] != '') { ?><a target="_blank" href="<?php print_r($item['carteira_conselho']) ?>"><img src="assets/download.png" style="width: 15px; height: 15px; cursor: pointer;"></img></a> <?php } ?></td>
                <td data-label="Prosel"><?php print_r($item['prosel']) ?></td>
                <td data-label="Função"><?php print_r($cpfToFuncao[$item['cpf']]) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>