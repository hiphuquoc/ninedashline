<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LangUiWriteTestService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class LangUiWriteTestController extends Controller
{
    public function __construct(
        private readonly LangUiWriteTestService $writeTest,
    ) {}

    public function show(): View
    {
        return view('admin.lang-ui.write-test', [
            'runUrl' => route('admin.lang-ui.write-test.run'),
        ]);
    }

    public function run(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->writeTest->runAll(),
        ]);
    }
}
