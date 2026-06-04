<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Đăng nhập Quản trị — {{ config('admin.company_name') }}</title>
    @include('partials.favicon')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('admin.partials.login-form-styles')
</head>
<body>
    <div class="bg-animation">
        <div class="grid"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <div class="loginContainer">
        <div class="loginCard">
            <div class="loginCard_header">
                <div class="loginCard_header_logo">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="loginCard_header_title">Đăng nhập Quản trị</h1>
                <p class="loginCard_header_subtitle">{{ config('admin.company_name') }} — vui lòng đăng nhập để tiếp tục.</p>
            </div>

            <div class="loginCard_body">
                <div id="alertBox" class="alert alert--error">
                    <svg class="alert_icon" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    <div id="alertMessage" class="alert_content"></div>
                </div>

                <form id="loginForm" method="POST">
                    @csrf
                    <div class="formGroup">
                        <label class="formGroup_label"><span>Email hoặc tên đăng nhập</span></label>
                        <div class="formGroup_input">
                            <input type="text" name="email" id="email" placeholder="admin@example.com" autocomplete="username" required>
                            <span class="formGroup_input_icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="formGroup">
                        <label class="formGroup_label"><span>Mật khẩu</span></label>
                        <div class="formGroup_input">
                            <input type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password" required>
                            <span class="formGroup_input_icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <button type="button" class="formGroup_input_toggle" onclick="togglePassword()">
                                <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eyeOffIcon" style="display:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="formGroup_remember">
                        <input type="checkbox" name="remember" id="remember" value="1">
                        <label for="remember">Ghi nhớ đăng nhập</label>
                    </div>
                    <button type="submit" class="submitBtn" id="submitBtn">
                        <span class="submitBtn_text">Đăng nhập</span>
                        <span class="submitBtn_loading"><span class="spinner"></span> Đang xử lý...</span>
                    </button>
                </form>
            </div>
            <div class="loginCard_footer">
                <p class="loginCard_footer_text"><a href="{{ route('home') }}">← Quay về trang chủ</a></p>
            </div>
        </div>
        <div class="brandFooter">
            <span>© {{ date('Y') }} {{ config('admin.company_name') }}</span>
        </div>
    </div>
    @include('admin.partials.login-form-script')
</body>
</html>
