// assets/js/chart.js
// សន្មតថាអ្នកប្រើ Chart.js Library
function renderSalesChart(labels, data) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'ការលក់ប្រចាំថ្ងៃ ($)',
                data: data,
                borderColor: '#3498db',
                fill: false,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}