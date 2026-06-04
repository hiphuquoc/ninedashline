<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EnsureAdminUserCommand extends Command
{
    protected $signature = 'ninedashline:admin-user
                            {--email= : Email đăng nhập}
                            {--username=admin : Tên đăng nhập}
                            {--password= : Mật khẩu (bắt buộc lần đầu)}
                            {--name=Administrator : Tên hiển thị}';

    protected $description = 'Tạo hoặc cập nhật tài khoản admin (role=admin)';

    public function handle(): int
    {
        $email = (string) ($this->option('email') ?: $this->ask('Email admin'));
        $password = (string) ($this->option('password') ?: $this->secret('Mật khẩu'));

        if ($email === '' || $password === '') {
            $this->error('Cần --email và --password.');

            return self::FAILURE;
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            $user = User::query()->create([
                'name' => (string) $this->option('name'),
                'email' => $email,
                'username' => (string) $this->option('username'),
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);
            $this->info("Đã tạo admin: {$email}");

            return self::SUCCESS;
        }

        $user->role = 'admin';
        $user->password = Hash::make($password);
        if ($this->option('username')) {
            $user->username = (string) $this->option('username');
        }
        $user->save();
        $this->info("Đã cập nhật admin: {$email}");

        return self::SUCCESS;
    }
}
