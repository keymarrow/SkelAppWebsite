<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminUserCommand extends Command
{
    protected $signature = 'cms:create-admin
        {email : Admin email address}
        {--name=SkelApp Admin : Display name for the CMS admin}
        {--password= : Strong password for the admin account}';

    protected $description = 'Create or update the private CMS admin user.';

    public function handle(): int
    {
        $password = (string) ($this->option('password') ?: $this->secret('Strong password'));

        $validator = Validator::make([
            'email' => $this->argument('email'),
            'name' => $this->option('name'),
            'password' => $password,
        ], [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', Password::min(16)->mixedCase()->numbers()->symbols()],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $admin = Admin::query()->updateOrCreate(
            ['email' => $this->argument('email')],
            [
                'name' => (string) $this->option('name'),
                'password' => $password,
            ],
        );

        $this->info("CMS admin ready for {$admin->email}.");

        return self::SUCCESS;
    }
}
