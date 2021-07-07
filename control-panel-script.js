(function () {

    const debounce = (fn, delay = 600, setTimeoutId) => (...args) =>
        clearTimeout(setTimeoutId, setTimeoutId = setTimeout(() => fn(...args), delay))


    const selectDocs = (event) => {
        searchDocs = event.target.value;
        $.ajax({
            type: "GET",
            url: `list-docs-per-page.php?like=${event.target.value}&prosel=${proselValue}`,
          }).done((html) => {
            document.querySelector("#info").classList.remove("loading")
            pageDocs = 1
            document.querySelector("#info").classList.remove("loading")
            $("#page").html(pageDocs)
            $("#info").html(html)
          })
    }


    $(document).ready(function () {
        $('#cpf').mask('999.999.999-99');
        $("#search").on("keyup", debounce(selectDocs));
        $("#search").on("keyup", () => {
            document.querySelector("#info").classList.add("loading")
            $("#info").html("<img src='assets/bigger-spinner.gif'>")
        });
    });





    /*
    $(document).ready(function() {
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });*/

    const $sidebar = document.querySelector("#sidebar")
    const $menu = document.querySelector("#menu");
    const $img = $menu.querySelector("img");

    $menu.addEventListener('click', () => {
        if ($sidebar.classList.contains('active')) {
            $img.src = 'assets/menu.png'
        } else {
            $img.src = 'assets/close.png'
        }
        $sidebar.classList.toggle('active')
    });

    const $menuDocs = document.querySelector("#list-docs");
    const $menuRegisterCpf = document.querySelector("#register-cpf");

    $menuDocs.addEventListener('click', () => {
        window.location.reload();
    })

    $menuRegisterCpf.addEventListener('click', () => {
        $("#paginationDocs").hide();
        $(".search-wrapper").hide();

        $("#paginationCpfs").show();
        $("#searchCpf").show();
        $("#info").load("list-cpf.php");
    })
})()