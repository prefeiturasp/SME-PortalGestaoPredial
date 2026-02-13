var $s = jQuery.noConflict();

$s('#attachment-details-alt-text').each(function() {
    var textbox2 = $s(document.createElement('textarea')).val(this.value);
    //console.log(this);
    
    $s.each(this.attributes, function() {
        if (this.specified) {            
            textbox2.attr( 'id', 'attachment-details-alt-text');
        }
    });
    $s(this).replaceWith(textbox2);
    
});

$s('#attachment_alt').each(function() {
    var textbox = $s(document.createElement('textarea')).val(this.value);
    var textalt = $s('#attachment_alt');
    //console.log(this);
    
    $s.each(this.attributes, function() {
        if (this.specified) {            
            textbox.attr( 'name', '_wp_attachment_image_alt');
            textbox.addClass('widefat');
        }
    });
    $s(this).replaceWith(textbox);
    
});

$s('#attachment-details-two-column-alt-text').each(function() {
    var textbox = $s(document.createElement('textarea')).val(this.value);
    console.log(this.attributes);
    $s.each(this.attributes, function() {
        if (this.specified) {
            textbox.prop(this.name, this.value)
        }
    });
    $s(this).replaceWith(textbox);
});



window.onload = function() {    

    var $div = $s("#__wp-uploader-id-0");
    
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === "class") {

                //var attributeValue = $s(mutation.target).prop(mutation.attributeName);
                //console.log("Class attribute changed to:", attributeValue);

                $s("[aria-describedby='alt-text-description']").each(function() {
                    var textbox = $s(document.createElement('textarea')).val(this.value);
                    //console.log(this.attributes);
                    $s.each(this.attributes, function() {
                        if (this.specified) {
                            textbox.attr( 'id', 'attachment-details-two-column-alt-text');
                        }
                    });
                    $s(this).replaceWith(textbox);
                });
            }
        });
    });

    if ($div.length){
        observer.observe($div[0], {
            attributes: true
        });
    }    

    var $divPop = $s(".wp-admin");
    var observerPop = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
           
            if (mutation.attributeName === "class") {              
                $s('.media-frame-toolbar .media-toolbar-secondary').append("<div class='notice notice-warning'><p><strong>ALERTA: Não se esqueça de recortar a imagem!</strong></p></div>");               
            }
        });        
    });
    if ($divPop.length){
        observerPop.observe($divPop[0], {
            attributes: true
        });
    }    
}