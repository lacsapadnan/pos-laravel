<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <!-- BEGIN NAVBAR TOGGLER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- END NAVBAR TOGGLER -->
        <div class="flex-row navbar-nav order-md-last">
            <div class="d-none d-md-flex">
                <div class="nav-item dropdown d-none d-md-flex">
                    <a href="#" class="px-0 nav-link" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">
                        <!-- Download SVG icon from http://tabler.io/icons/icon/bell -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-1">
                            <path
                                d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6">
                            </path>
                            <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                        </svg>
                        @if($notificationCount > 0)
                        <span class="badge bg-red">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <div class="card">
                            <div class="card-header d-flex">
                                <h3 class="card-title">Notifikasi Stok</h3>
                                <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable">
                                @if($notifications->count() > 0)
                                @foreach($notifications->take(5) as $notification)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span
                                                class="status-dot status-dot-animated {{ $notificationService->getPriorityColor($notification['priority']) }} d-block"></span>
                                        </div>
                                        <div class="col text-truncate">
                                            <a href="{{ $notification['url'] }}" class="text-body d-block">
                                                {{ $notification['product']['name'] }}
                                            </a>
                                            <div class="d-block text-secondary text-truncate mt-n1">
                                                Stok: {{ number_format($notification['product']['stock']) }} / Min: {{
                                                number_format($notification['product']['minimum_stock']) }}
                                                <span
                                                    class="badge badge-sm {{ $notificationService->getPriorityColor($notification['priority']) }} ms-1">
                                                    {{ $notificationService->getPriorityLabel($notification['priority'])
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions"
                                                onclick="markAsRead({{ $notification['id'] }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon text-muted icon-2">
                                                    <path d="M20 6L9 17l-5-5"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @if($notifications->count() > 5)
                                <div class="list-group-item text-center">
                                    <a href="{{ route('stock.index') }}" class="text-muted">
                                        Lihat {{ $notifications->count() - 5 }} notifikasi lainnya...
                                    </a>
                                </div>
                                @endif
                                @else
                                <div class="list-group-item text-center py-4">
                                    <div class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                            <path d="M20 6L9 17l-5-5"></path>
                                        </svg>
                                        <div>Tidak ada notifikasi stok rendah</div>
                                        <small>Semua produk memiliki stok yang cukup</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if($notifications->count() > 0)
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <a href="{{ route('stock.index') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-warehouse me-1"></i> Lihat Stok
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="#" class="btn btn-primary w-100" onclick="markAllAsRead()">
                                            <i class="fas fa-check-double me-1"></i> Tandai Dibaca
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="p-0 px-2 nav-link d-flex lh-1" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"> </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ auth()->user() ? auth()->user()->name : '' }}</div>
                        <div class="mt-1 small text-secondary">{{ auth()->user() ?
                            auth()->user()->getRoleNames()->first() : '' }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item">Settings</a>
                    <a href="#" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
        </div>
    </div>
</header>

<script>
    function markAsRead(productId) {
    fetch('{{ route("notifications.mark-as-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update notification count
            updateNotificationBadge(data.notification_count);

            // Show success message
            showNotification(data.message, 'success');

            // Refresh the notification dropdown
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memproses notifikasi', 'error');
    });
}

function markAllAsRead() {
    fetch('{{ route("notifications.mark-all-as-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update notification count
            updateNotificationBadge(data.notification_count);

            // Show success message
            showNotification(data.message, 'success');

            // Refresh the notification dropdown
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat memproses notifikasi', 'error');
    });
}

function updateNotificationBadge(count) {
    const badge = document.querySelector('.navbar .badge');
    if (count > 0) {
        if (badge) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'inline';
        }
    } else {
        if (badge) {
            badge.style.display = 'none';
        }
    }
}

function showNotification(message, type) {
    // Create a simple toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}

// Auto refresh notification count every 5 minutes
setInterval(() => {
    fetch('{{ route("notifications.count") }}')
        .then(response => response.json())
        .then(data => {
            updateNotificationBadge(data.notification_count);
        })
        .catch(error => {
            console.error('Error refreshing notification count:', error);
        });
}, 300000); // 5 minutes
</script>
