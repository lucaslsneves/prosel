
let update = false;
let loadAgain = true;
(function () {
  let sexo;
  let dependentes;
  
  function changeGender(event) {
     sexo = event.target.value;
  /*  if (sexo == "F") {
      
      $("#reservistaContainer").hide()
    } else {
      $("#reservistaContainer").show()
    }*/
  }

  function changeDependent(event) {
    let dependentes = event.target.value;
  }

  $(document).ready(function () {
    $('#cpf').mask('999.999.999-99');
  });
  //VALIDA CPF 
  function isValidCPF() {
    let cpf = document.getElementById("cpf").value
    cpf = cpf.replace(/[\s.-]*/igm, '')

    if (
      !cpf ||
      cpf.length != 11 ||
      cpf == "00000000000" ||
      cpf == "11111111111" ||
      cpf == "22222222222" ||
      cpf == "33333333333" ||
      cpf == "44444444444" ||
      cpf == "55555555555" ||
      cpf == "66666666666" ||
      cpf == "77777777777" ||
      cpf == "88888888888" ||
      cpf == "99999999999"
    ) {
      document.querySelector("#cpf-container").querySelector(".error").innerText = ""
      document.querySelector("#cpf-container").querySelector(".error").innerText = "CPF Inválido"
      document.getElementById("cpf").value = ""
      return false;
    }
    var soma = 0
    var resto
    for (var i = 1; i <= 9; i++)
      soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11)) resto = 0
    if (resto != parseInt(cpf.substring(9, 10))) {
      $(".is-invalid-cpf").show()
      document.querySelector("#cpf-container").querySelector(".error").innerText = ""
      document.querySelector("#cpf-container").querySelector(".error").innerText = "CPF Inválido"
      document.getElementById("cpf").value = ""
      return false;
    }
    soma = 0
    for (var i = 1; i <= 10; i++)
      soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11)) resto = 0
    if (resto != parseInt(cpf.substring(10, 11))) {
      $(".is-invalid-cpf").show()
      document.querySelector("#cpf-container").querySelector(".error").innerText = ""
      document.querySelector("#cpf-container").querySelector(".error").innerText = "CPF Inválido"
      document.getElementById("cpf").value = ""
      return false;
    }

    document.querySelector("#cpf-container").querySelector(".error").innerText = ""
    return true
  }

  document.querySelector("#cpf").addEventListener("blur", isValidCPF)
 


  // Custom Input File

  const realFileBtns = document.querySelectorAll(".real-file")
  const customBtns = document.querySelectorAll(".custom-button")
  const customTxts = document.querySelectorAll(".custom-text")

  realFileBtns.forEach((element, i) => {
    customBtns[i].addEventListener("click", function () {
      realFileBtns[i].click();
    });

    realFileBtns[i].addEventListener("change", function (event) {
      if (realFileBtns[i].value) {
       /* const match = realFileBtns[i].value.match(
          /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1] */
        if(this.files[0].size > 15000000){
          alert("File is too big!");
          this.value = "";
          return;
       };
        
          customTxts[i].innerHTML = "Arquivo selecionado";

      } else {
        customTxts[i].innerHTML = "Nenhum Arquivo Selecionado";
      }
    });
  });

  $(document).ready(function () {
    $("#form").submit(function (event) {
      document.getElementById("buttonId").querySelector("p").innerHTML = "Enviando..."
      document.getElementById("buttonId").querySelector("img").src = "assets/spinner2.gif"
      $("#buttonId").attr("disabled", true);
      $.ajax({
        type: "POST",
        url: "send-documents-controller.php",
        data: new FormData(this),
        cache: false,
        dataType: 'json',
        contentType: false,
        processData: false
      }).done(function (data) { 
        const p = document.querySelector("#error");
        if (!data.success) {
          document.getElementById("buttonId").querySelector("p").innerHTML = "Enviar"
          document.getElementById("buttonId").querySelector("img").src = "assets/arrow-right.svg"
          $("#buttonId").attr("disabled", false);
          if (data.message == 'Erro inesperado,tente novamente mais tarde') {
            p.innerText = "";
            p.innerText = data.message;
            return;
          }
          if (data.message === 'Sexo inválido') {
            p.innerText = "";
            p.innerText = data.message;
            return;
          }
          p.innerText = "";
          p.innerText = data.message;
          const $errors = document.querySelectorAll(".error");

          $errors.forEach(element => {
            element.innerHTML = "";
          });
          
          const errors = Object.entries(data.errors);

          errors.forEach((error) => {
            let parent = document.getElementById(error[0]).parentElement;
            if (parent.classList.contains('own-form-field')) {
              parent.querySelector('.error').innerHTML = "";
              parent.querySelector('.error').innerHTML = error[1]
            } else {
              parent.parentElement.querySelector('.error').innerHTML = "";
              parent.parentElement.querySelector('.error').innerHTML = error[1]
            }

          });
        } else {
          $("#form").load('views/send-documents1-view.php',() => {
            setStepButton("#step2");
          }    
          );
         
        }
      })
      event.preventDefault();

    });
  });

  /*
customBtn.addEventListener("click", function() {
  realFileBtn.click();
});


 let formData = {
        nome: $("#nomeCompleto").val(),
        cpf: $("#cpfId").val(),
        gender: $("input[name='gender']:checked").val(),
        rg: $("#rg").val(),
        foto3x4: $("#foto3x4").val(),
        comprovante: $("#comprovanteEndereco").val(),
        pis: $("#pis").val(),
        sus: $("#sus").val(),
        vacinacao: $("#vacinacao").val(),
        esocial: $("#esocial").val(),
        reservista: $("#reservista").val(),
        conta_bancaria: $("#contabancaria").val(),
        diploma: $("#diploma").val(),
        curriculo: $("#curriculo").val(),
        especializacoes: $("#especializacoes").val()
      };
      

realFileBtn.addEventListener("change", function(event) {
  if (realFileBtn.value) {
    customTxt.innerHTML = realFileBtn.value.match(
      /[\/\\]([\w\d\s\.\-\(\)]+)$/
    )[1];
  } else {
    customTxt.innerHTML = "No file chosen, yet.";
  }
}); */

})()