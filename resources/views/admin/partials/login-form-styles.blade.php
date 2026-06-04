<style>
    :root {
        --primary: #2196f3;
        --primary-dark: #1976d2;
        --success: #07a35d;
        --danger: #dc2626;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-900: #111827;
        --radius: 12px;
        --radius-lg: 16px;
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        position: relative;
        overflow: hidden;
    }

    .bg-animation {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
    }

    .bg-animation::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 20%, rgba(33, 150, 243, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 70% 80%, rgba(7, 163, 93, 0.1) 0%, transparent 40%);
        animation: loginPulse 15s ease-in-out infinite;
    }

    .bg-animation .grid {
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
        background-size: 50px 50px;
    }

    .bg-animation .floating-shape {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: loginFloat 20s ease-in-out infinite;
    }

    .bg-animation .floating-shape:nth-child(2) {
        width: 300px;
        height: 300px;
        background: var(--primary);
        top: 10%;
        left: 10%;
        animation-delay: -5s;
    }

    .bg-animation .floating-shape:nth-child(3) {
        width: 200px;
        height: 200px;
        background: var(--success);
        bottom: 20%;
        right: 15%;
        animation-delay: -10s;
    }

    .bg-animation .floating-shape:nth-child(4) {
        width: 150px;
        height: 150px;
        background: #f59e0b;
        top: 60%;
        left: 5%;
        animation-delay: -15s;
    }

    @keyframes loginPulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(5deg); }
    }

    @keyframes loginFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(10deg); }
    }

    .loginContainer {
        width: 100%;
        max-width: 440px;
        position: relative;
        z-index: 1;
    }

    .loginCard {
        background: rgba(255, 255, 255, 0.98);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-xl), 0 0 0 1px rgba(255, 255, 255, 0.1);
        overflow: hidden;
        animation: loginSlideUp 0.6s ease-out;
    }

    @keyframes loginSlideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .loginCard_header {
        padding: 2rem 2rem 1.5rem;
        text-align: center;
        position: relative;
    }

    .loginCard_header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 2rem;
        right: 2rem;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gray-200), transparent);
    }

    .loginCard_header_logo {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 14px rgba(33, 150, 243, 0.3);
    }

    .loginCard_header_logo svg {
        width: 32px;
        height: 32px;
        color: #fff;
    }

    .loginCard_header_title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 0.375rem;
    }

    .loginCard_header_subtitle {
        font-size: 0.9375rem;
        color: var(--gray-500);
        line-height: 1.5;
    }

    .loginCard_body { padding: 1.5rem 2rem 2rem; }

    .formGroup { margin-bottom: 1.25rem; }

    .formGroup_label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-700);
    }

    .formGroup_input { position: relative; }

    .formGroup_input input {
        width: 100%;
        padding: 0.875rem 2.75rem 0.875rem 3rem;
        font-size: 0.9375rem;
        color: var(--gray-900);
        background: var(--gray-50);
        border: 2px solid var(--gray-200);
        border-radius: var(--radius);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        font-family: inherit;
    }

    .formGroup_input input:hover { border-color: var(--gray-300); }

    .formGroup_input input:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
    }

    .formGroup_input input.error {
        border-color: var(--danger);
        background: #fef2f2;
    }

    .formGroup_input input::placeholder { color: var(--gray-400); }

    .formGroup_input_icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        pointer-events: none;
    }

    .formGroup_input_icon svg { width: 20px; height: 20px; }

    .formGroup_input input:focus ~ .formGroup_input_icon { color: var(--primary); }

    .formGroup_input_toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray-400);
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
    }

    .formGroup_input_toggle:hover { color: var(--gray-600); }

    .formGroup_input_toggle svg { width: 20px; height: 20px; }

    .formGroup_remember {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.25rem;
    }

    .formGroup_remember input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--primary);
        cursor: pointer;
    }

    .formGroup_remember label {
        font-size: 0.875rem;
        color: var(--gray-600);
        cursor: pointer;
    }

    .alert {
        display: none;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: var(--radius);
        margin-bottom: 1.25rem;
        font-size: 0.875rem;
    }

    .alert.show { display: flex; }

    .alert--error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert--success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert_icon { flex-shrink: 0; width: 20px; height: 20px; }

    .alert_content { flex: 1; line-height: 1.5; }

    .submitBtn {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: var(--radius);
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        margin-top: 1.5rem;
    }

    .submitBtn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(33, 150, 243, 0.35);
    }

    .submitBtn:disabled {
        background: var(--gray-300);
        cursor: not-allowed;
    }

    .submitBtn_text { display: flex; align-items: center; justify-content: center; gap: 0.5rem; }

    .submitBtn_loading { display: none; align-items: center; justify-content: center; gap: 0.5rem; }

    .submitBtn.loading .submitBtn_text { display: none; }

    .submitBtn.loading .submitBtn_loading { display: flex; }

    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: loginSpin 0.8s linear infinite;
    }

    @keyframes loginSpin { to { transform: rotate(360deg); } }

    .loginCard_footer {
        padding: 1rem 2rem;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-100);
        text-align: center;
    }

    .loginCard_footer_text { font-size: 0.8125rem; color: var(--gray-500); }

    .loginCard_footer_text a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .loginCard_footer_text a:hover { text-decoration: underline; }

    .brandFooter {
        text-align: center;
        margin-top: 1.5rem;
        color: rgba(255, 255, 255, 0.5);
        font-size: 0.8125rem;
    }

    @media (max-width: 480px) {
        body { padding: 0; align-items: flex-start; }
        .loginContainer { max-width: none; }
        .loginCard {
            border-radius: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .loginCard_header { padding: 1.5rem 1.25rem 1.25rem; }
        .loginCard_body { padding: 1.25rem; flex: 1; }
        .loginCard_footer { padding: 1rem 1.25rem; }
    }
</style>
