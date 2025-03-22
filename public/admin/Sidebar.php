<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= BASE_URL('admin/home'); ?>" class="nav-link">HOME</a>
                </li>


            </ul>
        </nav>
        <!-- /.navbar -->
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= BASE_URL('admin/home'); ?>" class="brand-link">
                <center><img src="<?= $Database->site('LinkIcon'); ?>" style="width: 60px;
    height: 60px;" width="100%"></center>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item has-treeview menu-open">
                            <a href="<?= BASE_URL('admin/home'); ?>" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-header">QUẢN LÝ</li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL('admin/users'); ?>" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Thành viên
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL('admin/courses'); ?>" class="nav-link">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>
                                    Khóa học
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL('admin/chatgpt'); ?>" class="nav-link">
                                <i class="nav-icon fas fa-headset"></i>
                                <p>
                                    Chat GPT
                                </p>
                            </a>
                        </li>

                        <li class="nav-header">CÀI ĐẶT</li>

                        <li class="nav-item">
                            <a href="<?= BASE_URL('admin/system'); ?>" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Hệ thống
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>