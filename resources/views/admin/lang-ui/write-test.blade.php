@extends('layouts.admin')

@section('content')
@include('admin.components.pageHeader', [
    'title' => 'Kiểm tra chức năng dịch / lưu',
    'desc' => 'Chạy thử ghi file <code>config/lang_ui/</code> trước khi dịch AI hàng loạt — tránh mất thời gian khi server thiếu quyền.',
    'icon' => '<path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
])

<div class="langUiWriteTest" id="langUiWriteTest" data-run-url="{{ $runUrl }}">
    <div class="langUiWriteTest_actions">
        <button type="button" class="adminButton adminButton--primary" id="langUiWriteTest_run">
            <i class="fa-solid fa-play" aria-hidden="true"></i>
            Chạy kiểm tra
        </button>
        <p class="langUiWriteTest_hint">Thử ghi giống thao tác <strong>Lưu section</strong> và <strong>Dịch ngang</strong> (landing <code>nav</code>, Chung sức <code>chung_suc</code>).</p>
    </div>

    <div class="langUiWriteTest_status" id="langUiWriteTest_status" hidden aria-live="polite"></div>

    <div class="langUiWriteTest_meta" id="langUiWriteTest_meta" hidden>
        <dl class="langUiWriteTest_metaList">
            <div><dt>Domain</dt><dd id="langUiWriteTest_host">—</dd></div>
            <div><dt>Đường dẫn server</dt><dd><code id="langUiWriteTest_path">—</code></dd></div>
            <div><dt>User PHP hiện tại</dt><dd><code id="langUiWriteTest_phpuser">—</code></dd></div>
        </dl>
    </div>

    <div class="langUiWriteTest_checks" id="langUiWriteTest_checks" hidden>
        <h2 class="langUiWriteTest_checksTitle">Kết quả từng bước</h2>
        <ul class="langUiWriteTest_checkList" id="langUiWriteTest_checkList"></ul>
    </div>

    <div class="langUiWriteTest_fix" id="langUiWriteTest_fix" hidden>
        <h2 class="langUiWriteTest_fixTitle"><i class="fa-solid fa-terminal"></i> Lệnh sửa trên VPS (aaPanel)</h2>
        <p class="langUiWriteTest_fixLead">SSH vào server, dán và chạy (đường dẫn và domain lấy từ site thực tế):</p>
        <div class="langUiWriteTest_fixBox">
            <pre class="langUiWriteTest_fixPre" id="langUiWriteTest_fixPre"></pre>
            <button type="button" class="adminButton adminButton--secondary langUiWriteTest_copy" id="langUiWriteTest_copy">
                <i class="fa-solid fa-copy" aria-hidden="true"></i> Sao chép lệnh
            </button>
        </div>
        <p class="langUiWriteTest_fixNote">Ubuntu không dùng aaPanel: thay <code>www:www</code> bằng <code>www-data:www-data</code>. Sau khi chạy, bấm <strong>Chạy kiểm tra</strong> lại.</p>
    </div>
</div>
@endsection

@push('scriptCustom')
<script>
(function () {
    const root = document.getElementById('langUiWriteTest');
    if (!root) return;

    const runUrl = root.dataset.runUrl;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const btn = document.getElementById('langUiWriteTest_run');
    const statusEl = document.getElementById('langUiWriteTest_status');
    const metaEl = document.getElementById('langUiWriteTest_meta');
    const checksEl = document.getElementById('langUiWriteTest_checks');
    const checkList = document.getElementById('langUiWriteTest_checkList');
    const fixEl = document.getElementById('langUiWriteTest_fix');
    const fixPre = document.getElementById('langUiWriteTest_fixPre');
    const copyBtn = document.getElementById('langUiWriteTest_copy');

    function setStatus(ok, text) {
        if (!statusEl) return;
        statusEl.hidden = false;
        statusEl.className = 'langUiWriteTest_status langUiWriteTest_status--' + (ok ? 'ok' : 'err');
        statusEl.textContent = text;
    }

    function render(data) {
        document.getElementById('langUiWriteTest_host').textContent = data.site_host || '—';
        document.getElementById('langUiWriteTest_path').textContent = data.site_path || '—';
        document.getElementById('langUiWriteTest_phpuser').textContent = data.php_user || '—';
        metaEl.hidden = false;

        checkList.innerHTML = (data.checks || []).map(function (c) {
            if (c.skipped) {
                return '<li class="langUiWriteTest_check langUiWriteTest_check--skip">' +
                    '<span class="langUiWriteTest_checkBadge">Bỏ qua</span>' +
                    '<div><strong>' + c.label + '</strong><p>' + c.message + '</p></div></li>';
            }
            const cls = c.ok ? 'ok' : 'err';
            const badge = c.ok ? 'OK' : 'Lỗi';
            return '<li class="langUiWriteTest_check langUiWriteTest_check--' + cls + '">' +
                '<span class="langUiWriteTest_checkBadge">' + badge + '</span>' +
                '<div><strong>' + c.label + '</strong>' +
                '<p>' + (c.message || '') + '</p>' +
                (c.path ? '<code class="langUiWriteTest_checkPath">' + c.path + '</code>' : '') +
                '</div></li>';
        }).join('');
        checksEl.hidden = false;

        if (data.has_permission_error) {
            fixPre.textContent = data.fix_commands_text || '';
            fixEl.hidden = false;
        } else {
            fixEl.hidden = true;
        }

        if (data.all_ok) {
            setStatus(true, 'Tất cả bước kiểm tra đều OK — có thể dịch AI và lưu file an toàn.');
        } else if (data.has_permission_error) {
            setStatus(false, 'Có lỗi quyền ghi — chạy lệnh SSH bên dưới rồi kiểm tra lại.');
        } else {
            setStatus(false, 'Có bước thất bại — xem chi tiết bên dưới.');
        }
    }

    async function runTest() {
        btn.disabled = true;
        setStatus(true, 'Đang kiểm tra…');
        try {
            const res = await fetch(runUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const json = await res.json();
            if (!res.ok || !json.success) {
                throw new Error(json.message || ('HTTP ' + res.status));
            }
            render(json.data || {});
        } catch (e) {
            setStatus(false, e.message || 'Kiểm tra thất bại.');
        } finally {
            btn.disabled = false;
        }
    }

    btn?.addEventListener('click', runTest);
    copyBtn?.addEventListener('click', async function () {
        const text = fixPre?.textContent || '';
        try {
            await navigator.clipboard.writeText(text);
            copyBtn.textContent = 'Đã sao chép';
            setTimeout(function () {
                copyBtn.innerHTML = '<i class="fa-solid fa-copy" aria-hidden="true"></i> Sao chép lệnh';
            }, 2000);
        } catch (_) {
            window.prompt('Sao chép lệnh:', text);
        }
    });

    runTest();
})();
</script>
@endpush
