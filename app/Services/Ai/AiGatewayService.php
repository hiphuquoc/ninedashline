<?php

declare(strict_types=1);

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

final class AiGatewayService
{
    /**
     * @param  array<int, array<string, mixed>>  $messages
     * @return array<string, mixed>
     */
    public function chat(array $messages, array $options = []): array
    {
        if (! config('ai.enabled')) {
            throw new RuntimeException('AI chưa bật. Đặt AI_ENABLED=true và API key trong .env');
        }

        [$provider, $model] = $this->resolveProviderAndModel((string) ($options['model'] ?? ''));
        $profile = config('ai.providers.' . $provider, []);
        $baseUrl = rtrim((string) ($profile['base_url'] ?? ''), '/');
        $apiKey = (string) ($profile['api_key'] ?? '');
        $timeout = (int) config('ai.timeout', 90);

        if ($baseUrl === '' || $apiKey === '' || $model === '') {
            throw new RuntimeException('Cấu hình AI provider chưa đầy đủ (base_url, api_key, model).');
        }

        $payload = [
            'model' => $model,
            'messages' => $messages,
        ];

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout($timeout)
            ->post($baseUrl . '/chat/completions', $payload);

        if (! $response->successful()) {
            $error = $response->json('error.message')
                ?? $response->body()
                ?? 'Unknown AI error';
            throw new RuntimeException('AI request failed: ' . Str::limit((string) $error, 500));
        }

        $json = $response->json();
        $content = data_get($json, 'choices.0.message.content');
        if (! is_string($content) || trim($content) === '') {
            throw new RuntimeException('AI không trả về nội dung.');
        }

        $result = [
            'content' => $content,
            'model' => (string) data_get($json, 'model', $model),
            'provider' => $provider,
        ];

        if (! empty($options['debug'])) {
            $result['debug'] = [
                'provider' => $provider,
                'model' => $model,
                'messages' => $messages,
            ];
        }

        return $result;
    }

    /** @return array{0: string, 1: string} */
    private function resolveProviderAndModel(string $modelSpec): array
    {
        $default = (string) config('ai.default_model', 'openai:gpt-4o-mini');
        $spec = trim($modelSpec) !== '' ? trim($modelSpec) : $default;

        if (str_contains($spec, ':')) {
            [$provider, $model] = explode(':', $spec, 2);

            return [trim($provider), trim($model)];
        }

        $provider = (string) config('ai.provider', 'openai');

        return [$provider, $spec];
    }
}
