<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="{{ route('home') }}" class="brand-link">
      <!--begin::Brand Image-->
      <img
        src="{{ asset('dist/assets/img/AdminLTELogo.png') }}"
        alt="AdminLTE Logo"
        class="brand-image opacity-75 shadow"
      />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">Book360</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false"
      >
        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon bi bi-palette"></i>
            <p>Dashboard</p>
          </a>
        </li>
        
        <li class="nav-item">
          <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-people"></i>
            <p>Quản lý người dùng</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Route::is('admin.categories.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-tags"></i>
            <p>Quản lý danh mục</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.publishers.index') }}" class="nav-link {{ Route::is('admin.publishers.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-building"></i>
            <p>Quản lý nhà xuất bản</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-book"></i>
            <p>
              Quản lý sách
              <i class="right bi bi-chevron-down"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.formats.index') }}" class="nav-link">
                <i class="nav-icon bi bi-book"></i>
                <p>Định dạng sách</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.languages.index') }}" class="nav-link">
                <i class="nav-icon bi bi-translate"></i>
                <p>Ngôn ngữ</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i class="nav-icon bi bi-box"></i>
                <p>Danh sách sách</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="" class="nav-link">
            <i class="nav-icon bi bi-cart"></i>
            <p>Quản lý đơn hàng</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.banners.index') }}" class="nav-link {{ Route::is('admin.banners.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-images"></i>
            <p>Quản lý banner</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.promotions.index') }}" class="nav-link {{ Route::is('admin.promotions.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-gift"></i>
            <p>Quản lý mã khuyến mãi</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.comments.index') }}" class="nav-link {{ Route::is('admin.comments.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-chat"></i>
            <p>Quản lý bình luận</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.ratings.index') }}" class="nav-link {{ Route::is('admin.ratings.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-star"></i>
            <p>Quản lý đánh giá</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ Route::is('admin.contacts.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-envelope"></i>
            <p>Quản lý liên hệ</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.posts.index') }}" class="nav-link {{ Route::is('admin.posts.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-file-earmark-text"></i>
            <p>Quản lý bài viết</p>
          </a>
        </li>
        
        {{-- <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>
              Dashboard
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../index.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Dashboard v1</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../index2.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Dashboard v2</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../index3.html" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Dashboard v3</p>
              </a>
            </li>
          </ul>
        </li> --}}
        
        
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>