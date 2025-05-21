// Foco en el campo de búsqueda de Select2 al abrir
$(document).on('select2:open', function(e) {
    const select2Id = e.target.id;
    const searchField = document.querySelector(`.select2-container--open .select2-search__field[aria-controls="select2-${select2Id}-results"]`);
    if (searchField) {
        searchField.focus();
    }
});