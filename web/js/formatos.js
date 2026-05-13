// web/js/formatos.js
function aplicarCorrector(idInput) {
    let selector = '#' + idInput;

    function procesar() {
        let input = $(selector);
        let texto = input.val();
        if (!texto) return;

        const comunes = {
            'area': 'Área', 'tecnico': 'Técnico', 'tecnica': 'Técnica',
            'codigo': 'Código', 'ultimo': 'Último', 'regimen': 'Régimen'
        };

        let palabras = texto.split(' ');
        let corregidas = palabras.map(p => {
            if (p.length === 0) return p;
            let limpia = p.toLowerCase().trim().replace(/[.,]/g, '');
            let palabra = p.toLowerCase();

            palabra = palabra.replace(/(\w{4,})ia(\b)/gi, '$1ía$2');
            palabra = palabra.replace(/(\w{2,})on(\b)/gi, '$1ón$2');

            if (comunes[limpia]) {
                palabra = palabra.replace(limpia, comunes[limpia].toLowerCase());
            }
            return palabra.charAt(0).toUpperCase() + palabra.slice(1);
        });

        input.val(corregidas.join(' '));
    }

    $(document).on('keyup', selector, function(e) {
        if (e.keyCode === 32 || e.keyCode === 13) procesar();
    });

    $(document).on('blur', selector, function() {
        procesar();
    });
}