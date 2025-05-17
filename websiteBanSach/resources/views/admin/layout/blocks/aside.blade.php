<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img src="<?= asset('dist/assets/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>
                            Quản lý
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-people"></i> <!-- Quản lý người dùng -->
                                <p>Quản lý người dùng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('promotions.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-tag"></i> <!-- Quản lý khuyến mãi -->
                                <p>Quản lý khuyến mãi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-folder"></i> <!-- Quản lý danh mục -->
                                <p>Quản lý danh mục</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('books.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-book"></i> <!-- Quản lý sách -->
                                <p>Quản lý sách</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('order_statuses.index') }}" class="nav-link">
                                <i class="nav-icon bi bi-card-checklist"></i> <!-- Quản lý trạng thái -->
                                <p>Quản lý trạng thái đơn hàng</p>
                            </a>
                        </li>

                    </ul>
                </li>
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
