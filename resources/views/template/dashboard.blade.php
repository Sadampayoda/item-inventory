<!doctype html>
<html lang="en">

<head>
    <title>Item Inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- CSS Laravel --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        #sidebar a {
            text-decoration: none;
            color: #fff;
        }

        #sidebar a:hover {
            color: #ddd;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="wrapper d-flex align-items-stretch">
        @include('template.sidebar')
        <!-- Page Content -->
        <div id="content" class="p-4 p-md-5">
            @include('template.nav')
            @yield('content')
        </div>
    </div>

    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <form action="{{ route('dashboard.profile') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">

                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Update Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Foto Profile</label>
                        <input type="file" name="image" class="form-control" accept="image/*">

                        @if (auth()->user()->image)
                            <small class="text-muted d-block mt-1">
                                Foto saat ini:
                            </small>
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" class="img-thumbnail mt-1"
                                width="120">
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>




    {{-- JS Laravel --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

</body>

</html>
