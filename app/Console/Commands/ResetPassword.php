<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:password {email} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset password of a user';

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
        $user = User::where(
            "email", 
            $this->argument("email")
        )->first();
        
        $user->password = Hash::make($this->option("password"));
        $user->save();
    }
}
