<nav id="sidebar">
    <div class="p-4 pt-5">
        <a href="#" class="img logo rounded-circle mb-4 d-block"
            style="background-image: url('{{ auth()->user()->image ? asset('storage/' . auth()->user()->image) : asset('images/logo.jpg') }}');"
            data-bs-toggle="modal" data-bs-target="#profileModal" title="Update Profile">
        </a>
        <ul class="list-unstyled components mb-5">
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>

            @if (in_array(Auth::user()->level, ['admin', 'warehouse']))
                <li class="{{ request()->routeIs('items.*') ? 'active' : '' }}">
                    <a href="{{ route('items.index') }}">Barang</a>
                </li>
            @endif

            @if (in_array(Auth::user()->level, ['warehouse', 'manajement_warehouse']))
                <li class="{{ request()->routeIs('inbounds.*') ? 'active' : '' }}">
                    <a href="{{ route('inbounds.index') }}">Masuk Stok (Inbound)</a>
                </li>

                <li class="{{ request()->routeIs('outbounds.*') ? 'active' : '' }}">
                    <a href="{{ route('outbounds.index') }}">Keluar Stok (Outbound)</a>
                </li>
            @endif

            @if (in_array(Auth::user()->level, ['admin', 'manajement_warehouse']))
                <li class="{{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                    <a href="{{ route('warehouses.index') }}">Warehouse</a>
                </li>
            @endif

            @if (Auth::user()->level === 'admin')
                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">Users Management</a>
                </li>
            @endif
            <li class="nav-item">
                <form action="{{ route('dashboard.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link p-0">
                        Logout
                    </button>
                </form>
            </li>
        </ul>

        <div class="footer">
            <p>
                Copyright &copy;
                <script>
                    document.write(new Date().getFullYear());
                </script>
                All rights reserved
            </p>
        </div>
    </div>
</nav>
