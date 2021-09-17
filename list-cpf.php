<?php

require "verifica.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    include 'connection.php';
    $query = "SELECT * FROM `auth_users_prosel` order by `updated_at` DESC LIMIT 9";
    $dados = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);

    $items_per_page = 9;

    $queryAll = "SELECT COUNT(*) FROM auth_users_prosel";
    $cpfAmount = $mysqli->query($queryAll)->fetch_all(MYSQLI_ASSOC);
    $cpfCount = $cpfAmount[0]['COUNT(*)'];
    $cpfMaxPage = round($cpfCount / $items_per_page, 0);

} catch (Exception $e) {
    print_r($e);
    $data['message'] = 'Erro inesperado,tente novamente mais tarde';
    echo json_encode($data);
    exit;
}
?>


<div id="wrapper-register-cpf">
    <form id="form">
        <div class="own-form-field" id="cpf-container">
            <label for="cpf">CPF *</label>
            <input type="tel" name="cpf" class="form-control" id="cpf" placeholder="Digite o CPF" required maxlength="14">
            <label style="margin-top: 10px" for="cpf">Função *</label>
            <input type="tel" name="funcao" class="form-control" id="funcao" placeholder="Cargo que o canditato irá exercer" required maxlength="50">
            <label style="margin-top: 10px" for="prosel">Processo Seletivo</label>
            <select id="prosel" name="prosel" id="prosel" required>
                <option selected disabled>Selecione a unidade</option>
                <option value="Guarapiranga">Hospital Municipal de Guarapiranga</option>
                <option value="Manoel Victorino">Hospital Manoel Victorino</option>
                <option value="UPA de Brotas">UPA de Brotas</option>
                <option value="UPA de Feira">UPA de Feira</option>
                <option value="Espanhol">Espanhol</option>
                <option value="SESAB">SESAB (IPERBA, Tsylla Balbino/RobertoSantos,Albert Sabin)</option>
                <option value="HGE">HGE</option>
                <option value="Suzano">Suzano</option>
                <option value="Bertioga">Bertioga</option>
                <option value="SACA">SACA</option>
                <option value="CRESAMU">CRESAMU</option>
                <option value="UPA Oropó">UPA Oropó</option>
                <option value="Hugo">Hugo</option>
                <option value="Sede">Sede</option>
            </select>
            <p class="error"></p>
            <p class="success"></p>
            <input type="submit" class="submit-button" id="button" class="submit-button" value="Cadastrar">
        </div>
    </form>
</div>
<div id="table-wrapper">
    <table id="table" maxPage="<?php echo $cpfMaxPage ?>">
        <thead>
            <tr>
                <th scope="col">CPF</th>
                <th scope="col">Criado em</th>
                <th scope="col">Função</th>
               
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dados as $item) {
            ?> <tr>
                    <td data-label="CPF"><?php print_r($item['cpf'])   ?></td>
                    <td data-label="created_at"><?php print_r($item['updated_at']) ?></td>
                    <td data-label="created_at"><?php print_r($item['funcao']) ?></td>
                   
                   <!--  <td data-label="Excluir"><img class="delete-cpf" src="assets/delete.png" cpf="<?php echo $item['cpf'] ?>" style="cursor: pointer;"></img></td> -->
                </tr>
            <?php
            } ?>
        </tbody>
    </table>
</div>
<script>
  
    $page = document.querySelector("#pageCpf");
    const backCpf = document.querySelector("#backCpf")
    const forwardCpf = document.querySelector("#forwardCpf");
    
    const debounce = (fn, delay = 600, setTimeoutId) => (...args) =>
        clearTimeout(setTimeoutId, setTimeoutId = setTimeout(() => fn(...args), delay))


    const selectCpfs = (event) => {
        searchCpf = event.target.value;
        pageCpf = 1
        $.ajax({
            type: "GET",
            url: `list-cpf-per-page.php?page=${pageCpf}&like=${event.target.value}`,
          }).done((html) => {
            document.querySelector("#table-wrapper").classList.remove("loading")
            $("#pageCpf").html(pageCpf)
            $("#table-wrapper").html(html)
          })
    }



    $(document).ready(function () {
        $('#cpf').mask('999.999.999-99');
        $("#searchCpf").on("keyup", debounce(selectCpfs));
        $("#searchCpf").on("keyup", () => {
            document.querySelector("#table-wrapper").classList.add("loading")
            $("#table-wrapper").html("<img src='assets/bigger-spinner.gif'>")
        });
    });

    if(boolListener) {
        boolListener = false;
        backCpf.addEventListener('click', (e) => {
        
        if (pageCpf === 1) {
            return;
        }
        document.querySelector("#table-wrapper").classList.add("loading")
        $("#table-wrapper").html("<img src='assets/bigger-spinner.gif'>")
        --pageCpf;
       
        $.ajax({
            type: "GET",
            url: `list-cpf-per-page.php?page=${pageCpf}&like=${searchCpf}`,
        }).done((data) => {
            $page.innerHTML = pageCpf;
            document.querySelector("#table-wrapper").classList.remove("loading")
            $("#table-wrapper").html(data);
        })
    })


    forwardCpf.addEventListener('click', (e) => {
        let maxPage = $("table").attr("maxPage")
        if (pageCpf >= maxPage) {
            return;
        }
       
        pageCpf++;
        
        document.querySelector("#table-wrapper").classList.add("loading")
        $("#table-wrapper").html("<img src='assets/bigger-spinner.gif'>")
        $.ajax({
            type: "GET",
            url: `list-cpf-per-page.php?page=${pageCpf}&like=${searchCpf}`,
        }).done((data) => {
            $page.innerHTML = pageCpf;
            document.querySelector("#table-wrapper").classList.remove("loading")
            $("#table-wrapper").html(data);
        })
    })

    }



  




    $(document).ready(function() {
        const deleteButtons = document.querySelectorAll(".delete-cpf")
        const pSuccess = document.querySelector(".success")
        const pError = document.querySelector(".error");
        deleteButtons.forEach((button) => {
            button.addEventListener('click', (e) => {
                let cpf = event.target.getAttribute('cpf');
                $.ajax({
                    type: "POST",
                    url: "delete-cpf.php",
                    data: {
                        cpf
                    },
                    dataType: 'json',
                    encode: true,

                }).done((data) => {
                    if (data['message'] == 'Erro inesperado,tente novamente mais tarde') {
                        $(".error").show()
                        $(".success").hide()
                        pError.innerHTML = "Erro ao excluir CPF, tente novamente mais tarde"
                        pSuccess.innerHTML = ""
                        return;
                    }
                    $("#info").load("list-cpf.php")
                })
            })
        })
        $("#form").submit(function(event) {
            $.ajax({
                type: "POST",
                url: "register-cpf.php",
                data: new FormData(this),
                cache: false,
                dataType: 'json',
                contentType: false,
                processData: false
            }).done((data) => {
                const pSuccess = document.querySelector(".success")
                const pError = document.querySelector(".error");
                if (data.success) {
                    $(".success").show()
                    $(".error").hide()
                    pError.innerHTML = ""
                    pSuccess.innerHTML = data.message
                    setTimeout(() => {
                        $("#info").load("list-cpf.php")
                    }, 1000)
                } else {
                    $(".error").show()
                    $(".success").hide()
                    pError.innerHTML = data.message
                }
            })
            event.preventDefault();
        })
    })
</script>