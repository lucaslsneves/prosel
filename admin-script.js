$(document).ready(function () {
    $("form").submit(function (event) {
        
    
      $.ajax({
        type: "POST",
        url: "auth.php",
        data: $("form").serialize(),
        dataType: 'json',
        cache : false,
        encode: true,
      }).done(function (data) {
        console.log(data);
        const $error = document.querySelector('#error')
        if(!data.success) {
            $error.innerHTML = "";
            $error.innerHTML = data.message
        }else {
            window.location.href = 'control-panel.php'
        }
    });
    event.preventDefault();
  })
});