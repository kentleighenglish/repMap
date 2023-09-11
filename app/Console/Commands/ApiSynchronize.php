<?php

namespace RepMap\Console\Commands;

use Illuminate\Console\Command;

class ApiSynchronize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes Database with Parliament API';

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
        //
    }
}
