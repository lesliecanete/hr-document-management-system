<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HR Document Management System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
            /* Background with school/office image */
            background-image: url('{{ asset("images/DO-Tagbilaran-City.png") }}');
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            position: relative;
        }
        
        /* Semi-transparent overlay for better readability */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.88);
            z-index: -1;
        }
        
        /* Sidebar with DepEd blue theme */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #1F4E79 0%, #2c6ba8 100%);
            transition: all 0.3s;
            box-shadow: 3px 0 10px rgba(0,0,0,0.2);
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            overflow-x: hidden;
        }
        
        .sidebar.collapsed {
            width: 60px;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 14px 15px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-left: 3px solid #ffffff;
        }
        
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-left: 3px solid #ce1126;
        }
        
        .sidebar .nav-link i {
            width: 22px;
            margin-right: 15px;
            text-align: center;
            flex-shrink: 0;
            margin-top: 2px;
            font-size: 1.1rem;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        
        .sidebar .nav-link.dropdown-toggle::after {
            margin-top: .5em;
            margin-left: .5em;
            border-top-color: rgba(255, 255, 255, 0.7);
        }
        
        .sidebar-header {
            padding: 20px 15px;
            background: rgba(0, 0, 0, 0.2);
            white-space: nowrap;
            overflow: hidden;
        }
        
        .sidebar-header .logo {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        
        .sidebar.collapsed .sidebar-header .logo span {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-header .logo img {
            height: 27px !important;
        }
        
        .menu-text {
            transition: all 0.3s;
            opacity: 1;
            display: inline-block;
            text-wrap: auto;
            word-wrap: break-word;
            line-height: 1.4;
            white-space: normal;
        }
        
        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            display: none;
        }
        
        /* Submenu styles */
        .sidebar .nav .nav {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            margin: 5px 10px;
        }
        
        .sidebar .nav .nav .nav-link {
            padding: 10px 15px;
            font-size: 0.9rem;
            border-left: 2px solid transparent;
        }
        
        .sidebar .nav .nav .nav-link:hover {
            border-left: 2px solid #ffffff;
        }
        
        .sidebar .nav .nav .nav-link.active {
            border-left: 2px solid #ce1126;
        }
        
        .main-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: all 0.3s;
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        
        .main-content.expanded {
            margin-left: 60px;
            width: calc(100% - 60px);
        }
        
        .navbar-top {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 70px;
            position: sticky;
            top: 0;
            z-index: 1050;
            backdrop-filter: blur(5px);
        }
        
        .navbar-top .dropdown {
            position: relative;
            z-index: 1051;
        }
        
        .navbar-top .dropdown-menu {
            z-index: 1052 !important;
        }
        
        .toggle-btn {
            border: none;
            background: none;
            color: #1F4E79;
            font-size: 1.2rem;
            padding: 5px 10px;
            cursor: pointer;
        }
        
        .content-wrapper {
            flex: 1 0 auto;
            padding: 25px;
            min-height: calc(100vh - 180px);
        }
        
        .sidebar.collapsed .dropdown-toggle::after {
            display: none;
        }
        
        .sidebar.collapsed .collapse {
            display: none !important;
        }
        
        .overlay {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        
        .overlay.show {
            display: block;
        }
        
        /* Updated Footer Styles - Matching Login Page */
        .footer {
            background: rgba(31, 78, 121, 0.95);
            color: white;
            padding: 25px 0;
            margin-top: auto;
            width: 100%;
            z-index: 100;
            backdrop-filter: blur(5px);
        }
        
        .footer-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        
        .footer-logos img {
            height: 45px;
            width: auto;
            filter: brightness(0) invert(1);
        }
        
        .study-title {
            font-style: italic;
            font-size: 0.9rem;
            margin: 10px 0;
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
        }
        
        .copyright {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            text-align: center;
        }
        
        /* Card styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-header {
            font-weight: 600;
        }
        .card-header:not(.bg-warning):not(.bg-success):not(.bg-danger):not(.bg-info):not(.bg-primary):not(.bg-secondary):not(.bg-dark):not(.bg-light) {
            background: linear-gradient(135deg, #f0f7ff 0%, #e3efff 100%);
            border-bottom: 1px solid #d0e3ff;
            font-weight: 600;
            color: #1F4E79;
        }
        
        /* Mobile styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 250px;
                z-index: 1045;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar.collapsed {
                transform: translateX(-100%);
                width: 250px;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .main-content.expanded {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .navbar-top {
                z-index: 1060 !important;
            }
            
            .navbar-top .dropdown {
                z-index: 1061 !important;
            }
            
            .navbar-top .dropdown-menu {
                z-index: 1062 !important;
            }
            
            .content-wrapper {
                min-height: calc(100vh - 160px);
                padding: 15px;
            }
            
            .footer {
                position: relative;
                width: 100%;
                padding: 20px 0;
            }
            
            .footer-logos {
                gap: 20px;
            }
            
            .footer-logos img {
                height: 35px;
            }
        }
        
        @media (max-width: 576px) {
            .footer-logos {
                flex-direction: column;
                gap: 15px;
            }
            
            .study-title {
                font-size: 0.8rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/" class="logo">
                <img src="{{ asset('images/depedtagbilaran-logo.jpg') }}" alt="DEPED Logo" 
                class="me-2" style="height: 40px; width: auto; border-radius: 4px;">
                <span class="menu-text">HR Document Management</span>
            </a>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            
            <!-- Documents Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('documents.*') ? 'active' : '' }}" 
                href="#" data-bs-toggle="collapse" data-bs-target="#documentsSubmenu">
                    <i class="fas fa-file-alt"></i>
                    <span class="menu-text">Documents</span>
                </a>
                <div class="collapse show" id="documentsSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documents.create') ? 'active' : '' }}" 
                            href="{{ route('documents.create') }}">
                                <i class="fas fa-upload"></i>
                                <span class="menu-text">Upload Document</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documents.index') ? 'active' : '' }}" 
                            href="{{ route('documents.index') }}">
                                <i class="fas fa-search"></i>
                                <span class="menu-text">Search Document</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('submitting-parties.*') ? 'active' : '' }}" href="{{ route('submitting-parties.index') }}">
                                <i class="fas fa-user-tie"></i>
                                <span class="menu-text">Submitting Parties</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>           
            <!-- Settings Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') || request()->routeIs('document-types.*') || request()->routeIs('pillars.*') ? 'active' : '' }}" 
                href="#" data-bs-toggle="collapse" data-bs-target="#settingsSubmenu">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">Settings</span>
                </a>
                <div class="collapse show" id="settingsSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('document-types.*') ? 'active' : '' }}" 
                            href="{{ route('document-types.index') }}">
                                <i class="fas fa-file-signature"></i>
                                <span class="menu-text">Document Types</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pillars.*') ? 'active' : '' }}" 
                            href="{{ route('pillars.index') }}">
                                <i class="fas fa-layer-group"></i>
                                <span class="menu-text">HR Pillars</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                            href="{{ route('users.index') }}">
                                <i class="fas fa-users-cog"></i>
                                <span class="menu-text">Users</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="navbar navbar-top">
            <div class="container-fluid">
                <button class="toggle-btn" id="sidebarToggle">
                    <i class="fas fa-bars"></i> 
                </button>
                <div class="text-center flex-grow-1">
                    <h4 class="mb-0" style="color: #1F4E79;">HR Document Management System</h4>
                    <p class="mb-0 text-muted">Division of Tagbilaran City</p>
                </div>
                <div class="d-flex align-items-center">
                    @auth
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user-edit me-2"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        
        <!-- Updated Footer -->
        <footer class="footer">
            <div class="container">
                <!-- Study Title -->
                <div class="study-title mb-3">
                    "A Web-Based Electronic Database Management System for HR Department in the Division of Tagbilaran City"
                </div>
                
                <!-- Logos -->
                <div class="footer-logos">
                    <img src="{{ asset('images/deped-logo.png') }}" alt="DepEd Logo">
                    <img src="{{ asset('images/bagongpilipinas-logo.png') }}" alt="Bagong Pilipinas Logo">
                </div>
                
                <!-- Copyright -->
                <div class="copyright">
                    &copy; 2025 HR Document Management System | Division of Tagbilaran City
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Mobile Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('overlay');
            
            // Check if sidebar state is saved in localStorage
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (isCollapsed) {
                collapseSidebar();
            }
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                if (sidebar.classList.contains('collapsed')) {
                    expandSidebar();
                } else {
                    collapseSidebar();
                }
            });
            
            // Close sidebar on overlay click (mobile)
            overlay.addEventListener('click', function() {
                collapseSidebar();
            });
            
            function collapseSidebar() {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                overlay.classList.remove('show');
                localStorage.setItem('sidebarCollapsed', 'true');
                
                // Adjust for mobile
                if (window.innerWidth <= 768) {
                    overlay.classList.add('show');
                }
            }
            
            function expandSidebar() {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                overlay.classList.remove('show');
                localStorage.setItem('sidebarCollapsed', 'false');
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    overlay.classList.remove('show');
                } else if (!sidebar.classList.contains('collapsed')) {
                    overlay.classList.add('show');
                }
            });
            
            // Auto-collapse on mobile
            if (window.innerWidth <= 768 && !sidebar.classList.contains('collapsed')) {
                collapseSidebar();
            }
            
            // Add active class to parent when submenu item is active
            document.querySelectorAll('.sidebar .nav .nav .nav-link.active').forEach(item => {
                const parent = item.closest('.collapse').previousElementSibling;
                if (parent) {
                    parent.classList.add('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>