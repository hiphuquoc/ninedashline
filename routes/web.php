<?php

use App\Http\Controllers\Admin\ContributeLangUiController;
use App\Http\Controllers\Admin\LangUiAiController;
use App\Http\Controllers\Admin\LandingLangUiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Support\LocaleUrl;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Admin — không prefix locale */
Route::get('/he-thong', [LoginController::class, 'loginForm'])->name('admin.loginForm');
Route::post('/loginAdmin', [LoginController::class, 'loginAdmin'])->name('admin.loginAdmin');
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'role:admin'])->prefix('he-thong')->group(function () {
    Route::get('/trang-chu', fn () => redirect()->route('admin.lang-ui.index'))->name('admin.dashboard');
    Route::get('/ngon-ngu', [LandingLangUiController::class, 'redirectToDefault'])->name('admin.lang-ui.index');
    Route::get('/ngon-ngu/{locale}', [LandingLangUiController::class, 'edit'])->name('admin.lang-ui.edit');
    Route::post('/ngon-ngu/{locale}/save', [LandingLangUiController::class, 'save'])->name('admin.lang-ui.save');

    Route::get('/ngon-ngu-chung-suc', [ContributeLangUiController::class, 'redirectToDefault'])->name('admin.lang-ui.contribute.index');
    Route::get('/ngon-ngu-chung-suc/{locale}', [ContributeLangUiController::class, 'edit'])->name('admin.lang-ui.contribute.edit');
    Route::post('/ngon-ngu-chung-suc/{locale}/save', [ContributeLangUiController::class, 'save'])->name('admin.lang-ui.contribute.save');

    Route::get('/lang-ui/ai/config', [LangUiAiController::class, 'config'])->name('admin.lang-ui.ai.config');

    Route::post('/ngon-ngu/{locale}/ai/translate-field', [LangUiAiController::class, 'translateField'])->name('admin.lang-ui.ai.translate-field');
    Route::post('/ngon-ngu/{locale}/ai/translate-section', [LangUiAiController::class, 'translateSection'])->name('admin.lang-ui.ai.translate-section');
    Route::post('/ngon-ngu/{locale}/google/translate-field', [LangUiAiController::class, 'translateFieldGoogle'])->name('admin.lang-ui.google.translate-field');
    Route::match(['get', 'post'], '/ngon-ngu/{locale}/export-prompt', [LangUiAiController::class, 'exportExternalPrompt'])->name('admin.lang-ui.export-prompt');
    Route::post('/ngon-ngu/{locale}/import-translations', [LangUiAiController::class, 'importTranslations'])->name('admin.lang-ui.import');

    Route::post('/ngon-ngu-chung-suc/{locale}/ai/translate-field', [LangUiAiController::class, 'translateField'])->name('admin.lang-ui.contribute.ai.translate-field');
    Route::post('/ngon-ngu-chung-suc/{locale}/ai/translate-section', [LangUiAiController::class, 'translateSection'])->name('admin.lang-ui.contribute.ai.translate-section');
    Route::post('/ngon-ngu-chung-suc/{locale}/google/translate-field', [LangUiAiController::class, 'translateFieldGoogle'])->name('admin.lang-ui.contribute.google.translate-field');
    Route::match(['get', 'post'], '/ngon-ngu-chung-suc/{locale}/export-prompt', [LangUiAiController::class, 'exportExternalPrompt'])->name('admin.lang-ui.contribute.export-prompt');
    Route::post('/ngon-ngu-chung-suc/{locale}/import-translations', [LangUiAiController::class, 'importTranslations'])->name('admin.lang-ui.contribute.import');
});

Route::get('/', HomeController::class)->name('home');

$localePattern = LocaleUrl::routeLocalePattern();
if ($localePattern !== '') {
    Route::get('/{locale}', HomeController::class)
        ->where('locale', $localePattern)
        ->name('home.locale');
}

Route::post('/api/chat', function (Request $request) {
    $payload = $request->validate([
        'message' => ['required', 'string', 'max:1500'],
        'context' => ['nullable', 'string', 'max:1500'],
    ]);

    $context = strtolower((string) ($payload['context'] ?? 'general'));
    $message = (string) $payload['message'];

    $knowledge = [
        'unclos' => 'UNCLOS 1982 quy định các vùng biển như lãnh hải, vùng đặc quyền kinh tế (EEZ) và thềm lục địa. Năm 2016, Tòa Trọng tài Phụ lục VII trong vụ Philippines kiện Trung Quốc kết luận không có cơ sở pháp lý cho yêu sách quyền lịch sử trong phạm vi đường chín đoạn vượt giới hạn UNCLOS.',
        'geography' => 'Biển Đông nối các tuyến vận tải hàng hải trọng yếu, bao gồm các quần đảo Hoàng Sa, Trường Sa, Đông Sa và Bãi Macclesfield.',
        'timeline' => 'Các mốc then chốt: bản đồ mười một đoạn (1947), điều chỉnh thành chín đoạn (1953), và phán quyết trọng tài Phụ lục VII UNCLOS (2016).',
    ];

    $fallback = 'Giao diện tài liệu này chỉ cung cấp ngữ cảnh phân tích. Hãy hỏi về UNCLOS, dòng thời gian lịch sử, địa lý điểm nóng hoặc so sánh yêu sách để nhận câu trả lời tập trung.';

    $answer = match (true) {
        str_contains($context, 'unclos') || str_contains(strtolower($message), 'unclos') || str_contains(strtolower($message), 'công ước') => $knowledge['unclos'],
        str_contains($context, 'geo') || str_contains(strtolower($message), 'hoàng sa') || str_contains(strtolower($message), 'trường sa') || str_contains(strtolower($message), 'spratly') || str_contains(strtolower($message), 'paracel') || str_contains(strtolower($message), 'biển đông') => $knowledge['geography'],
        str_contains($context, 'timeline') || str_contains(strtolower($message), 'timeline') || str_contains(strtolower($message), 'lịch sử') || str_contains(strtolower($message), '2016') || str_contains(strtolower($message), '1947') => $knowledge['timeline'],
        default => $fallback,
    };

    return response()->json([
        'reply' => $answer,
        'meta' => [
            'mode' => 'documentary-analytical',
            'source' => 'mock-api-ready',
        ],
    ]);
})->withoutMiddleware([VerifyCsrfToken::class]);
