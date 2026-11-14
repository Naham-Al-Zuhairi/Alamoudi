<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class BootstrapAdmin extends Command
{
    protected $signature = 'admin:bootstrap';

    protected $description = 'Create an administrator with interactively entered credentials';

    public function handle(): int
    {
        if (User::where('is_admin', true)->exists()) {
            $this->error('An administrator already exists.');

            return self::FAILURE;
        }
        $name = $this->ask('Name');
        $email = $this->ask('Email');
        $password = $this->secret('Password (minimum 12 characters)');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen((string) $password) < 12 || User::where('email', $email)->exists()) {
            $this->error('A unique valid email and a password of at least 12 characters are required.');

            return self::FAILURE;
        }
        User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password), 'is_admin' => true, 'email_verified_at' => now()]);
        $this->info('Administrator created.');

        return self::SUCCESS;
    }
}
