const charts = JSON.parse(document.getElementById('chart_data').value);
document.addEventListener("DOMContentLoaded", function() {
    var statisticsItem = document.getElementById("audience_growth");

    if (statisticsItem) {
        var data = {
            labels: charts.total_contacts.labels,
            datasets: [{
                label: 'Total Contacts',
                backgroundColor: '#3F52E3',
                data: charts.total_contacts.data
            }, {
                label: 'New Contacts',
                backgroundColor: '#25AB7C',
                data: charts.new_contacts
            }]
        };

        var options = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };
        var earningChartBar = new Chart(statisticsItem, {
            type: 'line',
            data: data,
            options: options
        });
    }
});