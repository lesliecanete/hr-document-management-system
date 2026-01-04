 <nav class="navbar navbar-expand-lg navbar-{{ $backgroundColor }} bg-{{ $backgroundColor }}">
    <div class="container">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10">

        <a class="navbar-brand" href="/">
            <i class="fas fa-file-contract"></i> HR Document Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                
                {{-- Documents Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="documentsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-file-alt"></i> Documents
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('documents.create') }}">
                                <i class="fas fa-upload"></i> Upload Document
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('documents.index') }}">
                                <i class="fas fa-search"></i> Search Document
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Applicants --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('applicants.index') }}">
                        <i class="fas fa-user-tie"></i> Applicants
                    </a>
                </li>

                {{-- Settings Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('users.index') }}">
                                <i class="fas fa-users-cog"></i> Users
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('document-types.index') }}">
                                <i class="fas fa-file-signature"></i> Document Types
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pillars.index') }}">
                                <i class="fas fa-layer-group"></i> HR Pillars
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            
            {{-- User Menu --}}
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>