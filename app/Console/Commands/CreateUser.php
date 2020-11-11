<?php

namespace App\Console\Commands;

use App\Providers\AccountProvider;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {username} {password} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(AccountProvider $accountProvider)
    {
        $username = $this->argument('username');
        $password = password_hash($this->argument('password'), PASSWORD_BCRYPT);
        $email = $this->argument('email');
        $response = $accountProvider->signup([
            'username' => $username,
            'password' => $password,
            'email' => $email
        ]);

        if ($response->status() == 200) {
            $this->info('User created successfully!');
        } else {
            $this->error('Something has gone wrong. User not created.');
        }
    }
}
