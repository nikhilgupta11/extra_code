<?php

namespace Modules\Transportation\Console\Commands;

use Illuminate\Console\Command;

class TransportationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:TransportationCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transportation Command description';

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
