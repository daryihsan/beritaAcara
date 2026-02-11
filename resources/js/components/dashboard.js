import Chart from 'chart.js/auto';

export function initDashboard() {
    // Chart.js logic untuk dashboard menu 
    const ctx = document.getElementById('dashboardChart');
    
    // Jika tidak ada chart, tetap jalankan logic dropdown kartu
    // (Jadi jangan return dulu kalau ctx null, pindahkan return ke dalam blok chart)
    
    if (ctx) {
        const chartWrapper = document.getElementById('chart-wrapper');
        const rawData = JSON.parse(chartWrapper.getAttribute('data-stats'));
        const context = ctx.getContext('2d');
        
        let gradientBlue = context.createLinearGradient(0, 0, 0, 300);
        gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.4)');
        gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        let gradientGray = context.createLinearGradient(0, 0, 0, 300);
        gradientGray.addColorStop(0, 'rgba(148, 163, 184, 0.3)');
        gradientGray.addColorStop(1, 'rgba(148, 163, 184, 0.0)');

        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawData.labels,
                datasets: [
                    {
                        label: 'Data ' + rawData.targetName,
                        data: rawData.personal,
                        borderColor: '#2563eb',
                        backgroundColor: gradientBlue,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4,
                        order: 1
                    },
                    {
                        label: 'Total Global (Semua)',
                        data: rawData.global,
                        borderColor: '#94a3b8',
                        backgroundColor: gradientGray,
                        borderWidth: 2,
                        borderDash: [6, 6],
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4,
                        hidden: true,
                        order: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        boxPadding: 4,
                        displayColors: true,
                        usePointStyle: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', borderDash: [4, 4] },
                        ticks: { stepSize: 1 }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Logic toggle
        const toggleGlobal = document.getElementById('toggleGlobal');
        if(toggleGlobal){
            toggleGlobal.addEventListener('change', function() {
                const isChecked = this.checked;
                myChart.setDatasetVisibility(1, isChecked);
                myChart.update();
            });
        }
    }

    // Logic dropdown kartu 
    const cards = document.querySelectorAll('.card-stat');
    
    cards.forEach(card => {
        const select = card.querySelector('.year-select');
        const numberDisplay = card.querySelector('.display-number');
        const labelDesc = card.querySelector('.label-desc'); 
        
        // Ambil data JSON dari atribut
        const statsArray = JSON.parse(card.getAttribute('data-stats-array')); 
        const totalAll = card.getAttribute('data-total-all'); 

        select.addEventListener('change', function() {
            const year = this.value;
            let finalValue = 0;
            let textDesc = "";

            // Efek animasi angka "blip"
            numberDisplay.style.opacity = 0.5;

            setTimeout(() => {
                if (year === 'all') {
                    finalValue = totalAll;
                    textDesc = "Total Akumulasi Semua Tahun";
                } else {
                    // Ambil dari array, kalau undefined berarti 0
                    finalValue = statsArray[year] ? statsArray[year] : 0;
                    textDesc = "Dokumen Tahun " + year;
                }

                // Update UI
                numberDisplay.innerText = finalValue;
                // Update teks kecil di bawah angka
                if(labelDesc.querySelector('.glyphicon')) {
                    // Logic khusus card global yg ada iconnya
                    labelDesc.innerHTML = `<span class="glyphicon glyphicon-ok-circle"></span> ${textDesc}`;
                } else {
                    labelDesc.innerText = textDesc;
                }
                
                numberDisplay.style.opacity = 1;
            }, 150);
        });
    });
}