

(function(){
    function changeGender() {
    let sexo = $("#selectId :selected").val();
    if (sexo == "F") {
      $("#reservistaContainer").hide()
    } else {
      $("#reservistaContainer").show()
    }
  }

  $(document).ready(function() {
    $('#cpfId').mask('999.999.999-99');
  });
  //VALIDA CPF 
  function isValidCPF() {

    let cpf = document.getElementById("cpfId").value


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
      $(".is-invalid-cpf").show()
      document.getElementById("cpfId").value = ""
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
      document.getElementById("cpfId").classList.add("is-invalid")
      document.getElementById("cpfId").value = ""
      return false;
    }
    soma = 0
    for (var i = 1; i <= 10; i++)
      soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11)) resto = 0
    if (resto != parseInt(cpf.substring(10, 11))) {
      $(".is-invalid-cpf").show()
      document.getElementById("cpfId").classList.add("is-invalid")
      document.getElementById("cpfId").value = ""
      return false;
    }
    
    $(".is-invalid-cpf").hide()
    return true
  }

  document.querySelector("#cpfId").addEventListener("blur",isValidCPF)
  document.querySelector("#selectId").addEventListener("change",changeGender)
  

  // Custom Input File

  const realFileBtns = document.querySelectorAll(".real-file")
  const customBtns = document.querySelectorAll(".custom-button")
  const customTxts = document.querySelectorAll(".custom-text")

  realFileBtns.forEach((element,i) => {
    customBtns[i].addEventListener("click", function() {
      realFileBtns[i].click();
    });

    realFileBtns[i].addEventListener("change", function(event) {
      if (realFileBtns[i].value) {
        customTxts[i].innerHTML = realFileBtns[i].value.match(
          /[\/\\]([\w\d\s\.\-\(\)]+)$/
        )[1];
      } else {
        customTxts[i].innerHTML = "Nenhum Arquivo Selecionado";
      }
    });
  });

  /*
customBtn.addEventListener("click", function() {
  realFileBtn.click();
});


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