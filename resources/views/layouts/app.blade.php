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
        }
        
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            transition: all 0.3s;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
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
            color: #ecf0f1;
            padding: 12px 15px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            white-space: nowrap;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
        }
        
        .sidebar .nav-link:hover {
            background: #34495e;
            color: #3498db;
            border-left: 3px solid #3498db;
        }
        
        .sidebar .nav-link.active {
            background: #34495e;
            color: #3498db;
            border-left: 3px solid #3498db;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        .sidebar .nav-link.dropdown-toggle::after{
            margin-top: .5em;
            margin-left: .5em;
        }
        
        .sidebar-header {
            padding: 20px 15px;
            background: #34495e;
            border-bottom: 1px solid #4a6278;
            white-space: nowrap;
            overflow: hidden;
        }
        
        .sidebar-header .logo {
            color: #ecf0f1;
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
        
        .sidebar.collapsed .sidebar-header .logo img{
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
        
        .main-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: all 0.3s;
            background: #f8f9fa;
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        
        .main-content.expanded {
            margin-left: 60px;
            width: calc(100% - 60px);
        }
        
        .navbar-top {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: 70px;
            position: sticky;
            top: 0;
            z-index: 1050;
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
            color: #2c3e50;
            font-size: 1.2rem;
            padding: 5px 10px;
            cursor: pointer;
        }
        
        .content-wrapper {
            flex: 1 0 auto;
            padding: 20px;
            min-height: calc(100vh - 140px);
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
        
        /* Footer Styles */
        .footer {
            background: #ffffff;
            border-top: 1px solid #dee2e6;
            padding: 15px 0;
            margin-top: auto;
            width: 100%;
            z-index: 100;
        }
        
        .footer .container-fluid {
            max-width: 100%;
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
            }
            
            .footer {
                position: relative;
                width: 100%;
            }
        }
        
        @media (max-width: 576px) {
            .footer .d-flex {
                flex-direction: column !important;
                text-align: center !important;
            }
            
            .footer .mb-2 {
                margin-bottom: 10px !important;
            }
            
            .footer img {
                margin: 0 auto !important;
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
                class="me-2" style="height: 40px; width: auto;">
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
                <div class="collapse {{ request()->routeIs('documents.*') ? 'show' : '' }}" id="documentsSubmenu">
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
                    </ul>
                </div>
            </li>

            <!-- Applicants -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('applicants.*') ? 'active' : '' }}" href="{{ route('applicants.index') }}">
                    <i class="fas fa-user-tie"></i>
                    <span class="menu-text">Applicants</span>
                </a>
            </li>

            <!-- Settings Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('users.*') || request()->routeIs('document-types.*') || request()->routeIs('pillars.*') ? 'active' : '' }}" 
                   href="#" data-bs-toggle="collapse" data-bs-target="#settingsSubmenu">
                    <i class="fas fa-cog"></i>
                    <span class="menu-text">Settings</span>
                </a>
                <div class="collapse {{ request()->routeIs('users.*') || request()->routeIs('document-types.*') || request()->routeIs('pillars.*') ? 'show' : '' }}" id="settingsSubmenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" 
                               href="{{ route('users.index') }}">
                                <i class="fas fa-users-cog"></i>
                                <span class="menu-text">Users</span>
                            </a>
                        </li>
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
                    <h4 class="mb-0">HR Document Management System</h4>
                    <p class="mb-0">Division of Tagbilaran</p>
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
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container-fluid px-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <!-- Left: DepEd Logo -->
                    <div class="mb-2 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/deped-logo.png') }}" alt="DepEd Logo" height="45" class="me-2">
                        </div>
                    </div>
                    
                    <!-- Center: Copyright -->
                    <div class="mb-2 mb-md-0 text-center">
                        <p class="mb-0 text-muted small">
                            &copy; 2025 HR Document Management System
                        </p>
                        <p class="mb-0 text-muted small">Division of Tagbilaran City</p>
                    </div>
                    
                    <!-- Right: Bagong Pilipinas -->
                    <div>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/bagongpilipinas-logo.png') }}" alt="Bagong Pilipinas" height="45" class="me-2">
                        </div>
                    </div>
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
        });
    </script>
    
    @stack('scripts')
</body>
</html>