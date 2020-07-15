$(document).ready(function () {
    initClientNameClick();

    // a kereső mezők validálása
    $('#name').on('click', function () {
        if ($('#idcard').val().length !== 0) {
            alert('Csak egyik mezővel kereshet!');
        }
    });

    $('#idcard').on('click', function () {
        if ($('#name').val().length !== 0) {
            alert('Csak egyik mezővel kereshet!');
        }
    });

    // ügyfél keresése
    $("#search").click(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "/search",
            dataType: 'json',
            data: {
                name: $('#name').val(),
                idcard: $('#idcard').val(),
                _token: $("#csrf-token").val()
            },
            success: function (result) {
                let client_response = $('#client_response');
                // a lista elrejtése
                client_response.addClass('d-none');
                if (result.status !== 'ok') {
                    alert(result.status)
                } else {
                    let client = JSON.parse(result.client);
                    $('#response_id').text(client.id);
                    $('#response_name').text(client.name);
                    $('#response_idcard').text(client.idcard);
                    $('#response_cars').text(client.client_cars);
                    $('#response_services').text(client.client_services);
                    // a lista megjelenítése
                    client_response.removeClass('d-none');
                    // a cars, services táblák elrejtése
                    $('#client-cars').addClass('d-none');
                    $('#car-service').addClass('d-none');
                }
            },
            error: function (result) {
                alert('error');
            }
        });
    });

    // ügyfél autóinak adatai
    function initClientNameClick() {
        $(".client-name").on("click", function () {
            //alert($(this).data('id'));
            $.ajax({
                type: "POST",
                url: "/cars",
                dataType: 'json',
                data: {
                    client_id: $(this).data('id'),
                    _token: $("#csrf-token").val()
                },
                success: function (result) {
                    let client_cars = $('#client-cars');
                    let status = result.status;
                    if (status !== 'ok') {
                        alert(status);
                    } else {
                        let cars = JSON.parse(result.cars);
                        // a tábla megjelenítése
                        client_cars.removeClass('d-none');
                        // a tábla kiürítése
                        $('#cars_tbody').empty();
                        // a tábla feltöltése
                        $.each(cars, function (i, item) {
                            let $tr = $('<tr>').append(
                                $('<td class="car-id" data-clientid="' + item.client_id + '">').text(item.car_id),
                                $('<td>').text(item.type),
                                $('<td>').text(item.registered),
                                $('<td>').text(item.ownbrand),
                                $('<td>').text(item.accident),
                                $('<td>').text(item.event),
                                $('<td>').text(item.eventtime)
                            ).appendTo('#cars_tbody');
                        });
                        initCarIdClick();
                        // a tábla elrejtése
                        $('#car-service').addClass('d-none');
                    }
                },
                error: function (result) {
                    alert('error');
                }
            });
        })
    }

    // ügyfél autójának szerviz adatai
    function initCarIdClick() {
        $(".car-id").on("click", function () {
            $.ajax({
                type: "POST",
                url: "/services",
                dataType: 'json',
                data: {
                    car_id: $(this).text(),
                    client_id: $(this).data('clientid'),
                    _token: $("#csrf-token").val()
                },
                success: function (result) {
                    let car_service = $('#car-service');
                    let status = result.status;
                    if (status !== 'ok') {
                        alert(status);
                    } else {
                        let services = JSON.parse(result.services);
                        // a tábla megjelenítése
                        car_service.removeClass('d-none');
                        // a tábla kiürítése
                        $('#service_tbody').empty();
                        // a tábla feltöltése
                        $.each(services, function (i, item) {
                            let $tr = $('<tr>').append(
                                $('<td>').text(item.lognumber),
                                $('<td>').text(item.event),
                                $('<td>').text(item.eventtime),
                                $('<td>').text(item.document_id),
                            ).appendTo('#service_tbody');
                        });
                    }
                },
                error: function (result) {
                    alert('error');
                }
            });
        });
    }

    // pagination
    $("ul").addClass('justify-content-center');
    $(function () {
        $('body').on('click', '.pagination a', function (e) {
            e.preventDefault();
            $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 10000;" src="https://i.imgur.com/v3KWF05.gif />');
            let url = $(this).attr('href');
            window.history.pushState("", "", url);
            loadClients(url);
        });

        // az ügyfél lista betöltése
        function loadClients(url) {
            $.ajax({
                url: url
            }).done(function (data) {
                $('.clients').html(data);
                $("ul").addClass('justify-content-center');
                initClientNameClick();
                initCarIdClick();
            }).fail(function () {
                console.log("Hiba az adatok betöltésekor!");
            });
        }
    });
});
