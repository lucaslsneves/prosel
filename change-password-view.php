<?php
require "verifica.php";
?>


<form style="display:flex; flex-direction:column; margin-top:16px;" id="form-password">
    <div  style="margin-bottom:16px;" class="own-form-field">
        <label style="margin-bottom:8px;" for="current-password">Senha Atual</label>
        <input style="padding:8px;" id="current-password" type="password">
    </div>

    <div  style="margin-bottom:16px;" class="own-form-field">
        <label  style="margin-bottom:8px;" for="new-password">Nova Senha</label>
        <input style="padding:8px;"id="new-password" type="password" placeholder="No mínimo 8 caracteres">
    </div>

    <div  style="margin-bottom:0;" class="own-form-field">
        <label  style="margin-bottom:8px;" for="new-password-confirmation">Confirmar Nova Senha</label>
        <input style="padding:8px;"id="new-password-confirmation" type="password" placeholder="No mínimo 8 caracteres">
    </div>
    <p id="error" class="error"></p>
    <button id="change-password-button"class="submit-button">Alterar</button>
</form>


<script>

$("#form-password").submit(function (event) {
      $("#change-password-button").prop("disabled" , true)
      $.ajax({
        type: "POST",
        url: "change-password-controller.php",
        data: $("#form-password").serialize(),
        dataType: 'json',
        cache : false,
        encode: true,
      }).done(function (data) {
        $("#button").prop("disabled" , false)
        const $error = document.querySelector('#error')
        if(!data.success) {
          $("#error").show()
            $error.innerHTML = "";
            $error.innerHTML = data.message
        }else {
            window.location.href = 'control-panel.php'
        }
    }).error(() => {
      $("#button").prop("disabled" , false)
    });
    event.preventDefault();
  })
  
</script>