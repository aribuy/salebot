const charts = JSON.parse(document.getElementById('chart_data').value);

//Active SubscriptionMiddleWare
const chartItem1 = document.getElementById('statisticsChart1');
if (chartItem1) {
    var context = chartItem1.getContext('2d');
    var gradientBGColor = context.createLinearGradient(0, 0, 0, 200);
    gradientBGColor.addColorStop(0.1, '#24D6AC60');
    gradientBGColor.addColorStop(0.4, '#ffffff30');

    const chart1 = new Chart(chartItem1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Nov', 'Des'],
            datasets: [{
                label: 'Active Subscription',
                backgroundColor: gradientBGColor,
                borderColor: '#24D6A5',
                data: charts.active_subscriptions,
                fill: true
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            },
            elements: {
                line: {
                    borderWidth: 2,
                    tension: 0.4
                },
                point: {
                    radius: 0,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    })
}
// End statisticsChart1




//Total Earning
const chartItem2 = document.getElementById('statisticsChart2');
if (chartItem2) {
    var context = chartItem2.getContext('2d');
    var gradientBGColor = context.createLinearGradient(0, 0, 0, 200);
    gradientBGColor.addColorStop(0.1, '#ffa60050');
    gradientBGColor.addColorStop(0.4, '#ffffff50');

    const chart2 = new Chart(chartItem2, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Nov', 'Des'],
            datasets: [{
                label: 'Total Earning',
                backgroundColor: gradientBGColor,
                borderColor: '#ffa600',
                data: charts.earning,
                fill: true
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            },
            elements: {
                line: {
                    borderWidth: 2,
                    tension: 0.4
                },
                point: {
                    radius: 0,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    })
}



//Total Client
const chartItem3 = document.getElementById('statisticsChart3');
if (chartItem3) {
    var context = chartItem3.getContext('2d');
    var gradientBGColor = context.createLinearGradient(0, 0, 0, 200);
    gradientBGColor.addColorStop(0.1, '#ff563050');
    gradientBGColor.addColorStop(0.4, '#ffffff30');

    const chart3 = new Chart(chartItem3, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Nov', 'Des'],
            datasets: [{
                label: 'Total Client',
                backgroundColor: gradientBGColor,
                borderColor: '#ff5630',
                data: charts.client,
                fill: true
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            },
            elements: {
                line: {
                    borderWidth: 2,
                    tension: 0.4
                },
                point: {
                    radius: 0,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    })
}
// End statisticsChart3



//Total Campaign
const chartItem4 = document.getElementById('statisticsChart4');
if (chartItem4) {
    var context = chartItem4.getContext('2d');
    var gradientBGColor = context.createLinearGradient(0, 0, 0, 200);
    gradientBGColor.addColorStop(0.1, '#3F52E350');
    gradientBGColor.addColorStop(0.4, '#ffffff30');

    const chart4 = new Chart(chartItem4, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Nov', 'Des'],
            datasets: [{
                label: 'Total Campaign',
                backgroundColor: gradientBGColor,
                borderColor: '#3F52E3',
                data: charts.campaign,
                fill: true
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            },
            elements: {
                line: {
                    borderWidth: 2,
                    tension: 0.4
                },
                point: {
                    radius: 0,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    })
}
// End statisticsChart4


// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function() {
    var statisticsItem = document.getElementById("statisticsBarChart");

    if (statisticsItem) {
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var currentMonth = currentDate.getMonth() + 1;

        var startMonth = (currentMonth + 1) % 12 || 12;
        var startYear = currentYear - 1;

        var labels = [];
        var client = [];
        var active_subscriptions = [];
        var earning = [];

        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        for (var i = 0; i < 12; i++) {
            var monthIndex = (startMonth + i - 1) % 12;
            var year = startYear + Math.floor((startMonth + i - 1) / 12);
            labels.push(months[monthIndex] + ' ' + year);
            client.push(charts.client[monthIndex]);
            active_subscriptions.push(charts.active_subscriptions[monthIndex]);
            earning.push(charts.earning[monthIndex]);
        }

        var data = {
            labels: labels,
            datasets: [{
                label: 'Total Client',
                backgroundColor: '#3F52E3',
                data: client
            }, {
                label: 'Total Active Subscriptions',
                backgroundColor: '#24D6A5',
                data: active_subscriptions
            }, {
                label: 'Total Earning',
                backgroundColor: '#FF5630',
                data: earning
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

document.addEventListener("DOMContentLoaded", function() {
    var statisticsItem = document.getElementById("statisticsBarChart1");

    if (statisticsItem) {
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var currentMonth = currentDate.getMonth() + 1;

        var startMonth = (currentMonth + 1) % 12 || 12;
        var startYear = currentYear - 1;

        var labels = [];
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        for (var i = 0; i < 12; i++) {
            var monthIndex = (startMonth + i - 1) % 12;
            var year = startYear + Math.floor((startMonth + i - 1) / 12);
            labels.push(months[monthIndex] + ' ' + year);
        }

        var totalContactsData = [];
        var newContactsData = [];
        for (var j = 0; j < 12; j++) {
            var monthIndex = (startMonth + j - 1) % 12;
            totalContactsData.push(charts.total_contacts[monthIndex]);
            newContactsData.push(charts.new_contacts[monthIndex]);
        }

        var data = {
            labels: labels,
            datasets: [{
                label: 'Total Contacts',
                backgroundColor: '#3F52E3',
                data: totalContactsData
            }, {
                label: 'New Contacts',
                backgroundColor: '#25AB7C',
                data: newContactsData
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

document.addEventListener("DOMContentLoaded", function() {
    var statisticsItem = document.getElementById("campaign_statistic");

    if (statisticsItem) {
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var currentMonth = currentDate.getMonth() + 1;

        var startMonth = (currentMonth + 1) % 12 || 12;
        var startYear = currentYear - 1;

        var labels = [];
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        for (var i = 0; i < 12; i++) {
            var monthIndex = (startMonth + i - 1) % 12;
            var year = startYear + Math.floor((startMonth + i - 1) / 12);
            labels.push(months[monthIndex] + ' ' + year);
        }

        var totalContactsData = [];
        var newContactsData = [];
        for (var j = 0; j < 12; j++) {
            var monthIndex = (startMonth + j - 1) % 12;
            totalContactsData.push(charts.total_contacts[monthIndex]);
            newContactsData.push(charts.new_contacts[monthIndex]);
        }

        var data = {
            labels: labels,
            datasets: [{
                label: 'WhatsApp',
                backgroundColor: '#25d366',
                data: totalContactsData
            }, {
                label: 'Telegram',
                backgroundColor: '#0088cc',
                data: newContactsData
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
            type: 'bar',
            data: data,
            options: options
        });
    }
});


document.addEventListener("DOMContentLoaded", function() {
    var statisticsItem = document.getElementById("conversation_statistic");

    if (statisticsItem) {
        var currentDate = new Date();
        var currentYear = currentDate.getFullYear();
        var currentMonth = currentDate.getMonth() + 1;

        var startMonth = (currentMonth + 1) % 12 || 12;
        var startYear = currentYear - 1;

        var labels = [];
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        for (var i = 0; i < 12; i++) {
            var monthIndex = (startMonth + i - 1) % 12;
            var year = startYear + Math.floor((startMonth + i - 1) / 12);
            labels.push(months[monthIndex] + ' ' + year);
        }

        var totalContactsData = [];
        var newContactsData = [];
        for (var j = 0; j < 12; j++) {
            var monthIndex = (startMonth + j - 1) % 12;
            totalContactsData.push(charts.total_contacts[monthIndex]);
            newContactsData.push(charts.new_contacts[monthIndex]);
        }

        var data = {
            labels: labels,
            datasets: [{
                label: 'WhatsApp',
                backgroundColor: '#25d366',
                data: totalContactsData
            }, {
                label: 'Telegram',
                backgroundColor: '#0088cc',
                data: newContactsData
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
            type: 'bar',
            data: data,
            options: options
        });
    }
});




//Total Contact Lists
const chartItem5 = document.getElementById('statisticsChart5');
if (chartItem5) {
    const chart5 = new Chart(chartItem5, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [
                {
                    label: 'Total Contact Lists',
                    backgroundColor: 'transparent',
                    borderColor: '#24D6A5',
                    data: charts.contact_lists,
                    fill: false
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            },
            elements: {
                line: {
                    borderWidth: 2,
                    tension: 0.4
                },
                point: {
                    radius: 0,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    })
}
// End statisticsChart5




//Total Contacts
const chartItem6 = document.getElementById('statisticsChart6');
if (chartItem6) {
    const chart6 = new Chart(chartItem6, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [
                {
                    label: 'Total Contacts',
                    backgroundColor: 'transparent',
                    borderColor: '#ff5630',
                    data: charts.contacts,
                    fill: false
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            maintainAspectRatio: false,
            scales: {
                x: {
                    display: false
                },
                y: {
                    display: false
                }
            },
            elements: {
                line: {
                    borderWidth: 2,
                    tension: 0.4
                },
                point: {
                    radius: 0,
                    hitRadius: 10,
                    hoverRadius: 4
                }
            }
        }
    })
}
// End statisticsChart6