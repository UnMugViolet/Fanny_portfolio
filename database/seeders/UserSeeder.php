<?php

namespace Database\Seeders;

use App\Models\User;
use Orchid\Platform\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Default password - use environment variable or fallback
        $userPassword = env('APP_PASSWORD');

        if (!$userPassword) {
            $this->command->error('APP_PASSWORD is not set in the environment. Please set it to create default users.');
            return;
        }

        // Create or update Fanny (main user)
        User::updateOrCreate(
            ['email' => 'contact@fanny-seraudie.fr'],
            [
                'name' => 'Fanny Seraudie',
                'password' => Hash::make($userPassword),
                'permissions' => [
                    "platform.index" => true,
                    "platform.systems.roles" => true,
                    "platform.systems.users" => true,
                    "platform.systems.attachment" => true,
                    "platform.categories" => true,
                    "platform.projects" => true,
                ],
                'email_verified_at' => now(),
            ]
        );

        // Create or update the admin user
        User::updateOrCreate(
            ['email' => 'jaguinpaul@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make($userPassword),
                'permissions' => [
                    "platform.index" => true,
                    "platform.systems.roles" => true,
                    "platform.systems.users" => true,
                    "platform.systems.attachment" => true,
                    "platform.categories" => true,
                    "platform.projects" => true,
                ],
                'email_verified_at' => now(),
            ]
        );
    }
}
