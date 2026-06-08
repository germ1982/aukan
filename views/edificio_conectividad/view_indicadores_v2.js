/**
 * Inicializa un gráfico de tipo dona (Doughnut) de Chart.js para las tarjetas.
 * @param {string} chartId - ID del elemento canvas del DOM.
 * @param {Array<number>} dataValues - Array con los valores de los estados [bueno, malo, regular, caido, desconocido].
 */
function initDashboardChart(chartId, estadosData) {
    var ctx = document.getElementById(chartId);
    if (!ctx) return;

    new Chart(ctx.getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Bueno', 'Malo', 'Regular', 'Caído', 'Desconocido'],
            datasets: [{
                data: estadosData,
                backgroundColor: ['#2b9348', '#d90429', '#ffa200', '#3d3d3d', '#aaaaaa'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
}