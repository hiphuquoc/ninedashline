<?php

return [
    'enabled' => (bool) env('AI_ENABLED', false),

    'provider' => env('AI_PROVIDER', 'openai'),

    'api_key' => env('AI_API_KEY'),

    'timeout' => (int) env('AI_TIMEOUT_SECONDS', 90),

    'debug' => (bool) env('AI_DEBUG', false),

    'providers' => [
        'openai' => [
            'base_url' => env('AI_OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'api_key' => env('AI_OPENAI_API_KEY'),
            'model' => env('AI_OPENAI_MODEL', 'gpt-4o-mini'),
        ],
        'deepinfra' => [
            'base_url' => env('AI_DEEPINFRA_BASE_URL', 'https://api.deepinfra.com/v1/openai'),
            'api_key' => env('AI_DEEPINFRA_API_KEY', env('DEEP_INFRA_API_KEY')),
            'model' => env('AI_DEEPINFRA_MODEL', 'meta-llama/Meta-Llama-3.1-70B-Instruct'),
        ],
        'deepseek' => [
            'base_url' => env('AI_DEEPSEEK_BASE_URL', 'https://api.deepseek.com/v1'),
            'api_key' => env('AI_DEEPSEEK_API_KEY', env('DEEP_SEEK_API_KEY')),
            'model' => env('AI_DEEPSEEK_MODEL', 'deepseek-chat'),
        ],
        'qwen' => [
            'base_url' => env('AI_QWEN_BASE_URL', 'https://dashscope-intl.aliyuncs.com/compatible-mode/v1'),
            'api_key' => env('AI_QWEN_API_KEY', env('QWEN_API_KEY')),
            'model' => env('AI_QWEN_MODEL', 'qwen-plus'),
        ],
        'xai' => [
            'base_url' => env('AI_XAI_BASE_URL', 'https://api.x.ai/v1'),
            'api_key' => env('AI_XAI_API_KEY', env('GROK_API_KEY')),
            'model' => env('AI_XAI_MODEL', 'grok-2-latest'),
        ],
        'anthropic' => [
            'base_url' => env('AI_ANTHROPIC_BASE_URL', ''),
            'api_key' => env('AI_ANTHROPIC_API_KEY', env('CLAUDE_AI_API_KEY')),
            'model' => env('AI_ANTHROPIC_MODEL', 'claude-3-5-sonnet-latest'),
        ],
    ],

    'models' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('AI_AVAILABLE_MODELS', 'openai:gpt-4o-mini,openai:gpt-4o,deepseek:deepseek-chat'))
    ))),

    'default_model' => env('AI_DEFAULT_MODEL') ?: (
        trim((string) env('AI_OPENAI_MODEL', '')) !== ''
            ? 'openai:' . trim((string) env('AI_OPENAI_MODEL'))
            : 'openai:gpt-4o-mini'
    ),
];
