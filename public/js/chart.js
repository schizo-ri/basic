
    var ctx = $('#myChart');

    var dataArr = $('.dataArr').text();   
   
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pročitano', 'Nepročitano'],
            datasets: [{
                label: '# of Votes',
                data: dataArr,
                backgroundColor: [
                    'rgba(21, 148, 240, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderColor: [
                    'rgba(21, 148, 240, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false,
                labels: {
                    fontColor: 'rgb(255, 99, 132)'
                }
            },
            tooltips: {
                enabled: false,
            }
        }
    });

    var ctx1 = $('#myChart1');
    var myChart = new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Pročitano', 'Nepročitano'],
            datasets: [{
                label: '# of Votes',
                data: [25, 75],
                backgroundColor: [
                    'rgba(7, 30, 87, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderColor: [
                    'rgba(7, 30, 87, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false,
                labels: {
                    fontColor: 'rgb(255, 99, 132)'
                }
            },
            tooltips: {
                enabled: false,
            }
        }
    });

    var ctx2 = $('#myChart2');
    var myChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Pročitano', 'Nepročitano'],
            datasets: [{
                label: '# of Votes',
                data: [25, 75],
                backgroundColor: [
                    'rgba(234, 148, 19, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderColor: [
                    'rgba(234, 148, 19, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false,
                labels: {
                    fontColor: 'rgb(255, 99, 132)'
                }
            },
            tooltips: {
                enabled: false,
            }
        }
    });

    var ctx3 = $('#myChart3');
    var myChart = new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Pročitano', 'Nepročitano'],
            datasets: [{
                label: '# of Votes',
                data: [25, 75],
                backgroundColor: [
                    'rgba(19, 234, 144, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderColor: [
                    'rgba(19, 234, 144, 1)',
                    'rgba(241, 241, 241, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            legend: {
                display: false,
                labels: {
                    fontColor: 'rgb(255, 99, 132)'
                }
            },
            tooltips: {
                enabled: false,
            }
        }
    });
