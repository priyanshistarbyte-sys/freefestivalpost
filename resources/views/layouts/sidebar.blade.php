<div class="sidebar">
    <div class="sidebar-header">
        <div class="brand">
            <i class="fas fa-calendar-alt"></i>
            <span>FreeFestival</span>
        </div>
        <span class="admin-label">Admin Panel</span>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item has-dropdown">
                <a href="#" class="nav-link dropdown-toggle">
                    <i class="fas fa-tags"></i>
                    <span>Category</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-link"><i class="fas fa-list"></i> Category List</a></li>
                    <li><a href="#" class="dropdown-link"><i class="fas fa-plus"></i> Add Category</a></li>
                </ul>
            </li>

            <li class="nav-item has-dropdown">
                <a href="#" class="nav-link dropdown-toggle">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                    <i class="fas fa-chevron-down arrow"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" class="dropdown-link"><i class="fas fa-list"></i> Users List</a></li>
                    <li><a href="#" class="dropdown-link"><i class="fas fa-exchange-alt"></i> Users Transaction</a></li>
                    <li><a href="#" class="dropdown-link"><i class="fas fa-image"></i> User Post</a></li>
                    <li><a href="#" class="dropdown-link"><i class="fas fa-comment"></i> User Feedback</a></li>
                    <li><a href="#" class="dropdown-link"><i class="fas fa-list-ol"></i> OTP List</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-file-image"></i>
                    <span>Template</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-video"></i>
                    <span>Video / GIF</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-images"></i>
                    <span>Photos List</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Application</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-users-cog"></i>
                    <span>Subscription Plan</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-credit-card"></i>
                    <span>Payment</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Site Setting</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-comments"></i>
                    <span>Complain</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
            </li>
        </ul>
    </nav>
</div>