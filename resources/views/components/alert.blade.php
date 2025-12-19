@props(['key', 'action'])


@if (session()->has($key))
    @switch($action)
        @case('success')
            <div class="alert alert-success text-center">
                <strong>Sukses !</strong> {{ session($key) }}
            </div>
            @break

        @case('errors')
            <div class="alert alert-danger text-center">
                <strong>Terjadi Kesalahan !</strong> {{ session($key) }}
            </div>
            @break

        @case('warning')
            <div class="alert alert-warning text-center">
                <strong>Peringatan !</strong> {{ session($key) }}
            </div>
            @break

        @default
            <div class="alert alert-primary text-center">
                <strong>Pemberitahuan !</strong> {{ session($key) }}
            </div>
            @break
    @endswitch
@endif
