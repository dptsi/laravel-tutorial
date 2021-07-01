<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\History;
use Illuminate\Support\Facades\Storage;

class ClearHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all rows from History table';

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
        History::truncate();
    }
}
