<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WelcomeUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    
    // protected $signature = 'welcome:user';
    
    // protected $signature = '
    //     welcome:user
    //     {name_argument}
    // ';

    // protected $signature = '
    // welcome:user
    // {name_argument=Caca}
    // ';

    protected $signature = '
    welcome:user
    {name_argument=Caca}
    {--org_option=ITS}
    ';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Say Hello to User';

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
     * @return int
     */
    public function handle()
    {
        // 1. Basic
        // $this->info('You call welcome:user command');
        
        // 2. With Argument
        // $name_argument = $this->argument('name_argument');
        // $this->info("
        //         Hello {$name_argument}!\n
        
        //         You call welcome:user command 
        //         with name Argument : {$name_argument}
        //     ");


        // 3. With Option and Argument
        // mengambil argumen tertentu
        $name_argument = $this->argument('name_argument');
    
        // mengambil opsi tertentu
        $org_option = $this->option('org_option');
        
        $this->info("
                Hello {$name_argument} from {$org_option}!\n

                You call welcome:user command 
                with name Argument : {$name_argument}
                with org Option : {$org_option}
            ");
        

        // 4. Ask Method
        // $name = $this->ask('What is your name?');

        // 5. Secret Method
        // $password = $this->secret('What is the password?');
        
        // 6. Confirm Method
        // if ($this->confirm('Do you wish to continue?')) {
        //     $this->info("Ok Continue");
        // } else {
        //     $this->error("Stop Process");
        // }

        return 0;
    }
}
