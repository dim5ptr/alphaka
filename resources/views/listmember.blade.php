<div class="container">
    <!-- Tombol Anggota dan Pengurus, serta input pencarian dengan ikon -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <button type="button" class="btn" id="anggotaBtn">Member</button>
            <button type="button" class="btn" id="pengurusBtn">Administrator</button>
        </div>

        <!-- Input pencarian dengan ikon -->
        <div class="input-group" style="width: 200px;">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-search"></i> <!-- Ikon pencarian -->
                </span>
            </div>
            <input type="search" id="searchInput" class="form-control" placeholder="Search...">
        </div>
    </div>

    <!-- Tabel -->
    <table class="table table-bordered" id="dataTable">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Handle</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="dataBody">
            <!-- Data akan diisi menggunakan JavaScript -->
        </tbody>
    </table>
</div>  

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        var anggotaData = [
            { id: 1, first: 'John', last: 'Doe', handle: '@john_doe', email: 'john@gmail.com'},
            { id: 2, first: 'Jane', last: 'Smith', handle: '@jane_smith', email: 'jane@gmail.com' }
        ];

        var pengurusData = [
            { id: 1, first: 'Alice', last: 'Johnson', handle: '@alice_johnson', email: 'alice@gmail.com' },
            { id: 2, first: 'Bob', last: 'Brown', handle: '@bob_brown', email: 'bob@gmail.com' }
        ];

        function populateTable(data) {
            var tbody = document.getElementById('dataBody');
            tbody.innerHTML = ''; 

            data.forEach(function (item) {
                var row = document.createElement('tr');
                row.innerHTML = `
                <th scope="row">${item.id}</th>
                <td>${item.first}</td>
                <td>${item.last}</td>
                <td>${item.handle}</td>
                <td>${item.email}</td>
                <td>
                    <form action="{{ route('showmoredetails', ['organization_name' => $organization['organization_name']]) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="More Details">
                            <i class="fa fa-ellipsis-h"></i>
                        </button>
                    </form>

                </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Fungsi untuk pencarian tabel
        document.getElementById('searchInput').addEventListener('input', function () {
            var searchValue = this.value.toLowerCase(); 
            var rows = document.querySelectorAll('#dataTable tbody tr'); 
            
            rows.forEach(function (row) {
                var cells = row.querySelectorAll('td');
                var found = Array.from(cells).some(function (cell) {
                    return cell.textContent.toLowerCase().includes(searchValue); 
                });

                row.style.display = found ? '' : 'none'; 
            });
        });

        document.getElementById('anggotaBtn').addEventListener('click', function () {
            populateTable(anggotaData); // Isi data anggota
        });

        document.getElementById('pengurusBtn').addEventListener('click', function () {
            populateTable(pengurusData); // Isi data pengurus
        });

        populateTable(anggotaData); // Default load data anggota saat halaman dimuat
    </script>
    <style>
        .btn {
            background: transparent; 
            border: none; /* Tidak ada border */
        }

        .btn:hover {
            border-bottom: 2px solid #7773d4; /* Garis bawah saat di-hover */
            transition: border-bottom 0.3s ease; /* Transisi yang halus */
        }

        .btn.active,
        .btn:focus {
            border-bottom: 2px solid #7773d4; /* Garis bawah saat aktif atau fokus */
        }

        /* Border Collapse untuk mencegah celah */
        .table {
            border-collapse: collapse; /* Hindari celah antar-border */
        }

        .table-bordered {
            border: 1px solid black; /* Border utama pada tabel */
        }

        .table-bordered th, 
        .table-bordered td, 
        .table-bordered tr {
            border-color: black !important; /* Warna border hitam */
        }


                .btn-sm {
        padding: 5px 8px; /* Adjust padding for smaller size */
        }

        .btn-info, .btn-danger, .btn-secondary {
        border-radius: 3px; /* Add rounded corners */
        }

    </style>
@endsection
