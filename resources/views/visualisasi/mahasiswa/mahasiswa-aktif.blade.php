@extends('layouts.visual')
@section('content')
    <div id="hero-area" class="hero-area-bg">
        <div class="container">
            <div class="row justify-content-start">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="hero-area text-center">
                        <h1 class="wow fadeInUp" data-wow-delay="0.3s">Data Mahasiswa Aktif {{ $status }}</h1>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 p-2 wow fadeInUp" data-wow-delay="0.3s">
                    <table class="table table-striped table-hover border">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Jumlah Mahasiswa Aktif</th>
                                @if($status == 'S1')
                                    <th scope="col">Jumlah Mahasiswa Aktif (Transfer)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody id="dataTableBody">

                        </tbody>
                    </table>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 wow fadeInUp " data-wow-delay="0.3s">
                    <canvas id="chart-1" height="220vh"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        $(document).ready(function() {
            var status = window.location.pathname.split('/').pop();
            var apiUrl = '';
            if (status === 'S1') {
                apiUrl = 'http://127.0.0.1:8000/api/visualisasi/mhsAktif/A2:C7';
            } else if (status === 'S2') {
                apiUrl = ' http://127.0.0.1:8000/api/visualisasi/mhsAktif/E2:F7';
            } else if (status === 'S3') {
                apiUrl = ' http://127.0.0.1:8000/api/visualisasi/mhsAktif/A10:B15';
            } else if (status === 'Profesi') {
                apiUrl = ' http://127.0.0.1:8000/api/visualisasi/mhsAktif/D10:E15';
            } else {
                console.error('Status tidak valid');
                return;
            }
            $.ajax({
                url: apiUrl,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#dataTableBody').empty();
                    $.each(data, function(index, entry) {
                        var row = $('<tr>');
                        row.append('<th scope="row">' + (index + 1) + '</th>');
                        row.append('<td>' + entry['Tahun'] + '</td>');
                        row.append('<td>' + entry['Jumlah Mahasiswa Aktif'] + '</td>');
                        @if ($status == 'S1')
                            if (entry['Jumlah Mahasiswa Aktif (Transfer)']) {
                                row.append('<td>' + entry['Jumlah Mahasiswa Aktif (Transfer)'] +
                                    '</td>');
                            } else {
                                row.append(
                                '<td>-</td>'); // Jika tidak ada data transfer, tampilkan tanda strip
                            }
                        @endif
                        $('#dataTableBody').append(row);
                    });

                    const labels = data.map(entry => entry.Tahun);
                    const dataset1 = {
                        label: 'Jumlah Mahasiswa Aktif',
                        data: data.map(entry => entry['Jumlah Mahasiswa Aktif']),
                        backgroundColor: 'rgba(11, 169, 9, 0.2)',
                        borderColor: 'rgb(11, 169, 9)',
                        borderWidth: 1,
                        tension: 0.4
                    };

                    renderChart('chart-1', 'line', labels, [dataset1]);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });


            function renderChart(canvasId, type, labels, datasets) {
                const config = {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                };
                var myChart = new Chart(document.getElementById(canvasId), config);
            }
        });
    </script>
@endsection
