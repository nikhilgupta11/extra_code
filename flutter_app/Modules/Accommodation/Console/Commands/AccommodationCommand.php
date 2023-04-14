<?php

namespace Modules\Accommodation\Console\Commands;

use Illuminate\Console\Command;

class AccommodationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:AccommodationCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accommodation Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return Command::SUCCESS;
    }
}
