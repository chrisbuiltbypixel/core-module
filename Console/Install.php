<?php

namespace Modules\Core\Console;

use Modules\AdminUser\Entities\AdminUser;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proton:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install proton';

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
        // install passport
        if ($this->confirm('Do you want to install passport?')) {
            $this->call('passport:install');
        };

        // run over the installed modules and
        // import the scout indexes.
        $this->info('Indexing models');

        if ($this->confirm('Do you want to add a new admin user?')) {

            $data['first_name'] = $this->ask("What's your First Name");
            $data['last_name'] = $this->ask("What's your Last Name");
            $data['email'] = $this->ask("What's your email");
            $password = Str::random(10);
            $data['password'] = bcrypt($password);

            if (AdminUser::where('email', $data['email'])->exists()) {
                $this->error('This email already exists');
            } else {
                $user = AdminUser::create($data);

                $this->comment('An admin user has been created');
                $this->comment('email: ' . $user->email);
                $this->comment('password: ' . $password);

            }

        };

        $this->info('Proton has been installed');
    }
}
