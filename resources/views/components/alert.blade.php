@props(['key', 'action' => null, 'message' => null, 'justify_text' => 'text-center'])

@if (session()->has($key) || $message)
    @switch($action)
        @case('success')
            <div class="alert alert-success {{ $justify_text }}">
                <strong>Sukses !</strong> {{ $message ?? session($key) }}
            </div>
            @break

        @case('errors')
            <div class="alert alert-danger {{ $justify_text }}">
                <strong>Terjadi Kesalahan !</strong> {{ $message ?? session($key) }}
            </div>
            @break

        @case('warning')
            <div class="alert alert-warning {{ $justify_text }}">
                <strong>Peringatan !</strong> {{ $message ?? session($key) }}
            </div>
            @break

        @default
            <div class="alert alert-info {{ $justify_text }}">
                <strong>Pemberitahuan !</strong> {{ $message ?? session($key) }}
            </div>
    @endswitch
@endif
