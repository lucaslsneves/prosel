$(document).ready(function () {
    $("form").submit(function (event) {
      $("#button").prop("disabled" , true)
      $.ajax({
        type: "POST",
        url: "auth.php",
        data: $("form").serialize(),
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
});