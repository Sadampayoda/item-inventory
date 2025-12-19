<nav id="sidebar">
    <div class="p-4 pt-5">
        <a href="#" class="img logo rounded-circle mb-5"
            style="background-image: url('{{ asset('images/logo.jpg') }}');">
        </a>

        <ul class="list-unstyled components mb-5">

            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Barang</a></li>
            <li><a href="#">Masuk Stok (Inbound)</a></li>
            <li><a href="#">Keluar Stok (Outbound)</a></li>
            <li><a href="{{ route('warehouses.index') }}">Warehouse</a></li>
            <li><a href="{{ route('users.index') }}">Users Manajement</a></li>
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
