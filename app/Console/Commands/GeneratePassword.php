<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password:generate {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a password to store in the database.';

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
    public function handle()
    {
        $password = password_hash($this->argument('password'), PASSWORD_BCRYPT);
        $this->info('The password to store is: ' . $password);
    }
}
