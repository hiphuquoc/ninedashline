<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');
        if (!passwordInput || !eyeIcon || !eyeOffIcon) return;

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
        } else {
            passwordInput.type = 'password';
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
        }
    }

    function showAlert(message, type) {
        type = type || 'error';
        const alertBox = document.getElementById('alertBox');
        const alertMessage = document.getElementById('alertMessage');
        if (!alertBox || !alertMessage) return;

        alertBox.className = 'alert alert--' + type + ' show';
        alertMessage.textContent = message;
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideAlert() {
        const alertBox = document.getElementById('alertBox');
        if (alertBox) alertBox.classList.remove('show');
    }

    function setLoading(isLoading) {
        const submitBtn = document.getElementById('submitBtn');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        if (!submitBtn) return;

        submitBtn.classList.toggle('loading', isLoading);
        submitBtn.disabled = isLoading;
        if (emailInput) emailInput.disabled = isLoading;
        if (passwordInput) passwordInput.disabled = isLoading;
    }

    function clearErrors() {
        document.querySelectorAll('.formGroup_input input').forEach(function (input) {
            input.classList.remove('error');
        });
    }

    document.getElementById('loginForm')?.addEventListener('submit', function (e) {
        e.preventDefault();
        hideAlert();
        clearErrors();

        const email = document.getElementById('email')?.value.trim() || '';
        const password = document.getElementById('password')?.value || '';
        const remember = document.getElementById('remember')?.checked || false;

        if (!email) {
            showAlert('Vui lòng nhập email hoặc tên đăng nhập');
            document.getElementById('email')?.classList.add('error');
            document.getElementById('email')?.focus();
            return;
        }

        if (!password) {
            showAlert('Vui lòng nhập mật khẩu');
            document.getElementById('password')?.classList.add('error');
            document.getElementById('password')?.focus();
            return;
        }

        setLoading(true);

        fetch('{{ route('admin.loginAdmin') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ email: email, password: password, remember: remember }),
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { status: response.status, data: data };
                });
            })
            .then(function (result) {
                setLoading(false);
                const data = result.data;

                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(function () {
                        window.location.href = data.redirect_url || '{{ route('admin.lang-ui.index') }}';
                    }, 800);
                    return;
                }

                showAlert(data.message || 'Đăng nhập thất bại');

                if (data.errors) {
                    if (data.errors.email) document.getElementById('email')?.classList.add('error');
                    if (data.errors.password) document.getElementById('password')?.classList.add('error');
                }

                if (data.type === 'credentials') {
                    const pw = document.getElementById('password');
                    if (pw) {
                        pw.value = '';
                        pw.focus();
                    }
                }
            })
            .catch(function () {
                setLoading(false);
                showAlert('Có lỗi xảy ra. Vui lòng thử lại sau.');
            });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('email')?.focus();
    });

    document.querySelectorAll('.formGroup_input input').forEach(function (input) {
        input.addEventListener('input', function () {
            input.classList.remove('error');
        });
    });
</script>
