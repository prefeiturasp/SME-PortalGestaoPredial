window.onload = function() {

    //matem o focu no mapa
    jQuery("#map").mouseover(function() {
        jQuery('#map').focus();
    });


    //seta coordenadas inicial do mapa pegando o primeiro valor da lista de contatos
    var latlng = jQuery('.story').data().point.split(',');
    
    var lat = latlng[0];
    var lng = latlng[1];
    map = L.map('map', { center: [lat, lng], zoom: 17 });

    //monta a imagem do mapa
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        subdomains: ['a', 'b', 'c']
    }).addTo(map);

    jQuery("a[href='#chegar']").on("click", function(e) {
        map.invalidateSize(true);
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
        setTimeout(function() {
            map.invalidateSize();
        }, 200);
    });


    //adiciona marcadores iniciais dos contatos
    //jQuery('.story').each(function(i) {
    //if (jQuery(this).attr('data-point') != null + i + 1) {

    jQuery(this).each(function() {
        //console.log(jQuery(this).attr('data-point'));

        // pega lat e lng das class ".story" por data attribute
        //var latlng = jQuery(this).data().point.split(',');
        var lat = latlng[0];
        var lng = latlng[1];
        var desc = latlng[2];
        var zoom = 17;
        var zona = latlng[3]


        if (zona == 'norte') {
            myIcon = L.icon({
                iconUrl: "/wp-content/themes/tema-ceus/img/pin-map-norte.png",
            });
            console.log('norte');
        } else if (zona == 'sul') {
            myIcon = L.icon({
                iconUrl: "/wp-content/themes/tema-ceus/img/pin-map-sul.png",
            });
        } else if (zona == 'leste') {
            myIcon = L.icon({
                iconUrl: "/wp-content/themes/tema-ceus/img/pin-map-leste.png",
            });
        } else if (zona == 'oeste') {
            myIcon = L.icon({
                iconUrl: "/wp-content/themes/tema-ceus/img/pin-map-oeste.png",
            });
        } else {
            myIcon = L.icon({
                iconUrl: "/ceu/wp-content/themes/tema-ceus/img/pin-map-padrao.png",
            });
        }


        // adiciona marcadores
        var marker = L.marker([lat, lng], { icon: myIcon }).bindPopup(desc).addTo(map);

        // adiciona no mapa
        map.setView([lat, lng], zoom);

    });
    //}
    //});

}