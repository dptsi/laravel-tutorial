<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\HistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ExportHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all data in History table';

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
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = 'history_' . $timestamp . '.xlsx';
        Excel::store(new HistoryExport(), $filename);
    }
}
