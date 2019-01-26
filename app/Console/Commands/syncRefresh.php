<?php

namespace RepMap\Console\Commands;

use Illuminate\Console\Command;
use RepMap\Services\SyncService;

class syncRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes the databases.';

	protected $sync;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SyncService $sync)
    {
        parent::__construct();

		$this->sync = $sync;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(count(5));
		$bar->setFormat('%bar%  %message%');

		$bar->start();

		$bar->setMessage('Clearing All Data');
		$counties = $this->sync->clearAll();

		$bar->setMessage('Fetching Counties');
		$counties = $this->sync->updateCounties();

		if ($counties['success']) {
			$countiesCount = $counties['count'];
			$bar->setMessage("${countiesCount} Counties Added");
			$bar->advance();
		}

		$bar->setMessage('Fetching Constituencies');
		$constituencies = $this->sync->updateConstituencies();

		if ($constituencies['success']) {
			$constituenciesCount = $constituencies['count'];
			$bar->setMessage("${constituenciesCount} Constituencies Added");
			$bar->advance();
		}

		$bar->setMessage('Fetching Members and Parties');
		$members = $this->sync->updateMembers();

		if ($members['success']) {
			$membersCount = $members['count'];
			$partiesCount = $members['partyCount'];
			$bar->setMessage("${membersCount} Members and ${partiesCount} Parties Added");
			$bar->advance();
		}

		$bar->setMessage('Updating GeoJson');
		$this->sync->updateGeoJson();
		$bar->setMessage('GeoJson Updated');
		$bar->advance();

		$bar->finish();
		echo "\n";
		$this->info('Finished');
		echo "\n";
    }
}
