<?php

namespace App\Console\Commands;

use App\Http\Services\RegosService;
use Illuminate\Console\Command;

class GetCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:getCountry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Синхронизировать справочник стран';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $rService;
    public function __construct(RegosService $rService)
    {
        $this->rService = $rService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return $this->rService->sync('getCountry');
    }
}
