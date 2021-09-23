<?php
require "verifica.php";
?>


<form style="display:flex; flex-direction:column; margin-top:16px;" id="form-password">
    <div class="own-form-field">
        <label for="current-password">Senha Atual</label>
        <input id="current-password" type="password">
    </div>

    <div class="own-form-field">
        <label for="new-password">Nova Senha</label>
        <input id="new-password" type="password">
    </div>

    <div class="own-form-field">
        <label for="new-password-confirmation">Confirmar Nova Senha</label>
        <input id="new-password-confirmation" type="password">
    </div>
    <button class="submit-button">Alterar</button>
</form>