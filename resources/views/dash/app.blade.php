<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiktok Scrapper Dashboard</title>

    <link rel="stylesheet" href="assets/css/main/app.css">
    <link rel="stylesheet" href="assets/css/main/app-dark.css">
    <link rel="shortcut icon" href="assets/images/logo/favicon.svg" type="image/x-icon">
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/png">

    <link rel="stylesheet" href="assets/extensions/filepond/filepond.css">
    <link rel="stylesheet" href="assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css">
    <link rel="stylesheet" href="assets/extensions/toastify-js/src/toastify.css">
    <link rel="stylesheet" href="assets/css/pages/filepond.css">

    <link rel="stylesheet" href="assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="assets/css/pages/simple-datatables.css">

    <link rel="stylesheet" href="assets/extensions/sweetalert2/sweetalert2.min.css">
</head>

<body>
    <div id="app">
        @include('sweetalert::alert')
        @include('dash/side')

        <div id="main" class='layout-navbar'>
            @include('dash/header')
            <div id="main-content">

                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Tiktok PDF Extracktor</h3>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="dashboard">Dashboard</a></li>
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <section class="section">
                        <div class="col-12 col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <h5 class="card-title">Upload File</h5>

                                        <input type="file" name="file" accept=".pdf" class="form-control" required>
                                        <button id="success" type="submit"
                                            class="btn btn-primary rounded-pill mt-3">Upload</button>
                                    </form>

                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Tool</h5>
                                    <div class="buttons">
                                        <a href="export" class="btn icon icon-left btn-success"><i
                                                data-feather="check-circle"></i>
                                            Export Excel</a>
                                        <a href="delete" class="btn icon icon-left btn-danger"><i
                                                data-feather="alert-circle"></i>
                                            Clear Table</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                Table Data
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Telp</th>
                                            <th>Alamat</th>
                                            <th>Item</th>
                                            <th>QTY</th>
                                            <th>Resi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($scrapper as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama}}</td>
                                            <td>{{ $item->telp}}</td>
                                            <td>{{ $item->alamat}}</td>
                                            <td>{{ $item->item}}</td>
                                            <td>{{ $item->qty}}</td>
                                            <td>{{ $item->resi}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
                @include('dash/footer')
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/app.js"></script>

    <script src="assets/extensions/filepond/filepond.js"></script>
    <script src="assets/extensions/toastify-js/src/toastify.js"></script>
    <script src="assets/js/pages/filepond.js"></script>

    <script src="assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
    <script src="assets/js/pages/simple-datatables.js"></script>

    <script src="assets/extensions/sweetalert2/sweetalert2.min.js"></script>>
    <script src="assets/js/pages/sweetalert2.js"></script>>

    @section('scripts')
    <script>
        // Get a reference to the file input element
        const inputElement = document.querySelector('input[type="file"]');
    
        // Create a FilePond instance
        const pond = FilePond.create(inputElement);
    </script>
    @endsection
</body>

</html>