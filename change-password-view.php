<?php
require "verifica.php";
?>
<style>
  #change-password-button:disabled {
    background-color: #A0AEC0;
    cursor: not-allowed;
  }
</style>
<div style="display:flex;align-items:center;">
  <form style="display:flex; flex-direction:column; margin-top:16px;color:#232c31;" id="form-password">

    <div style="margin-bottom:16px;" class="own-form-field">
      <label style="margin-bottom:8px;" for="current-password">Senha Atual</label>
      <input style="padding:8px;" id="current-password" type="password" placeholder="Senha Atual">
    </div>

    <div style="margin-bottom:16px;" class="own-form-field">
      <label style="margin-bottom:8px;" for="new-password">Nova Senha</label>
      <input style="padding:8px;" id="new-password" type="password" placeholder="Nova Senha">
    </div>

    <div style="margin-bottom:0;" class="own-form-field">
      <label style="margin-bottom:8px;" for="password-confirmation">Confirmar Nova Senha</label>
      <input style="padding:8px;" id="password-confirmation" type="password" placeholder="Confirmação">
    </div>
    <p id="error" class="error"></p>
    <button disabled id="change-password-button" class="submit-button">Alterar</button>
  </form>
  <div style="display:flex;flex-direction:column;margin-left:40px;">
    <div style="display:flex; align-items:center; max-width:400px;margin-bottom:16px;">
      <img id="number-validation" style="width:24px; margin-right: 12px;" src="assets/exclamation-triangle-regular.svg" />
      <p style="font-weight:bold; font-size: 14px;">A nova senha deve conter ao menos um número</p>
    </div>
    <div style="display:flex; align-items:center; max-width:400px;margin-bottom:16px;">
      <img id="letter-validation" style="width:24px; margin-right: 12px;" src="assets/exclamation-triangle-regular.svg" />
      <p style="font-weight:bold; font-size: 14px;">A nova senha deve conter ao menos uma letra</p>
    </div>
    <div style="display:flex; align-items:center; max-width:400px;margin-bottom:16px;">
      <img id="length-validation" style="width:24px; margin-right: 12px;" src="assets/exclamation-triangle-regular.svg" />
      <p style="font-weight:bold; font-size: 14px;">A nova senha deve conter ao menos 8 caracteres</p>
    </div>
    <div style="display:flex; align-items:center; max-width:400px;margin-bottom:16px;">
      <img id="equal-validation" style="width:24px; margin-right: 12px;" src="assets/exclamation-triangle-regular.svg" />
      <p style="font-weight:bold; font-size: 14px;">A nova senha e a sua confirmação devem ser iguais</p>
    </div>
  </div>
</div>
<script>
  $("#form-password").submit(function(event) {
    $("#change-password-button").prop("disabled", true)
    $.ajax({
      type: "POST",
      url: "change-password-controller.php",
      data: $("#form-password").serialize(),
      dataType: 'json',
      cache: false,
      encode: true,
    }).done(function(data) {
      $("#button").prop("disabled", false)
      const $error = document.querySelector('#error')
      if (!data.success) {
        $("#error").show()
        $error.innerHTML = "";
        $error.innerHTML = data.message
      } else {
        window.location.href = 'control-panel.php'
      }
    }).error(() => {
      $("#button").prop("disabled", false)
    });
    event.preventDefault();
  })

  function containsNumbers(str) {
    const regexp = /\d/g;
    return regexp.test(str);
  };

  function containsLetters(str) {
    const regexp = /[a-zA-Z]/g;
    return regexp.test(str);
  }



  let atLeastOneNumberValidation = false;
  let letterValidation = false;
  let lengthValidation = false;
  let passwordConfirmationValidation = false;

  $("#new-password").keyup((e) => {
    let newPassword = e.target.value;
    let passwordConfirmationValue = $("#password-confirmation").val()
    console.log(passwordConfirmationValue)
    if (containsNumbers((newPassword))) {
      $("#number-validation").attr("src", "assets/check-regular.svg");
      atLeastOneNumberValidation = true;
    } else {
      $("#number-validation").attr("src", "assets/exclamation-triangle-regular.svg");
      atLeastOneNumberValidation = false;
    }

    if (containsLetters(newPassword)) {
      $("#letter-validation").attr("src", "assets/check-regular.svg");
      letterValidation = true;
    } else {
      $("#letter-validation").attr("src", "assets/exclamation-triangle-regular.svg");
      letterValidation = false;
    }

    if (newPassword.length >= 8) {
      $("#length-validation").attr("src", "assets/check-regular.svg");
      lengthValidation = true;

    } else {
      $("#length-validation").attr("src", "assets/exclamation-triangle-regular.svg");
      lengthValidation = false;
    }

    if(newPassword === passwordConfirmationValue) {
      $("#equal-validation").attr("src", "assets/check-regular.svg");
      passwordConfirmationValidation = true;
      
    }else {
      $("#equal-validation").attr("src", "assets/exclamation-triangle-regular.svg");
      passwordConfirmationValidation = false;
    }

    if(atLeastOneNumberValidation && letterValidation && lengthValidation && passwordConfirmationValidation) {
      $("#change-password-button").attr("disabled" , false);
    }else {
      $("#change-password-button").attr("disabled" , true);
    }
  })
</script>