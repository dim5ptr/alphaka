<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Usage Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F7FA;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #365AC2;
            margin-bottom: 30px;
        }

        .table-container {
            width: 80%;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden; /* Pastikan border-radius berfungsi */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #365AC2;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #AFC3FC; /* Warna untuk baris genap */
        }

        tr:nth-child(odd) {
            background-color: white; /* Warna untuk baris ganjil */
        }

        td {
            color: #333;
        }

        tr:hover {
            background-color: #365AC2; /* Warna saat hover */
            color: white;
        }

        .loading {
            text-align: center;
            font-size: 20px;
            color: #365AC2;
        }
    </style>
</head>
<body>

    <h1>Data Usage Dashboard</h1>

    <div class="table-container">
        <table id="usageTable">
            <thead>
                <tr>
                    <th>Device ID</th>
                    <th>User Hotspot</th>
                    <th>Uptime</th>
                    <th>Bytes In</th>
                    <th>Bytes Out</th>
                    <th>Total Usage</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be populated here -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to fetch data from API and populate the table
            function fetchData() {
                $.ajax({
                    url: 'http://192.168.1.24:14041/api/hotspot/data_usage_dashboard.json', // Ganti dengan URL API yang sesuai
                    type: 'GET',
                    success: function(response) {
                        // Clear existing data in the table
                        $('tbody').empty();

                        // Populate the table with new data
                        $.each(response, function(index, data) {
                            $('#usageTable tbody').append(`
                                <tr>
                                    <td>${data.device_id}</td>
                                    <td class="bold">${data.user_hotpost}</td>
                                    <td>${data.duration}</td>
                                    <td>${data.rx_rate}</td>
                                    <td>${data.tx_rate}</td>
                                    <td>${data.total_usage}</td>
                                </tr>
                            `);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            // Initial fetch when the page loads
            fetchData();

            // Fetch data every 1 second
            setInterval(fetchData, 1000);
        });
    </script>
</body>
</html>
