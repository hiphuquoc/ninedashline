<!DOCTYPE html>
<html lang="vi">
<head>
    @include('admin.snippets.head')
    @stack('headCustom')
</head>
<body>
    <div id="js_fullLoading_blur">
        <div class="pageContent container">
            <div class="adminDashboard">
                @include('admin.snippets.menu')
                <main class="adminDashboard_main">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-error">{{ session('error') }}</div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
        <div class="adminDashboard_mobileMenuBackdrop" id="adminMobileMenuBackdrop"></div>
    </div>

    <div id="toast-container" class="toast-container position-fixed top-0 start-0"></div>

    @include('admin.modal.fullLoading')
    @stack('modal')

    @include('admin.snippets.scriptDefault')
    @stack('scriptCustom')
</body>
</html>
