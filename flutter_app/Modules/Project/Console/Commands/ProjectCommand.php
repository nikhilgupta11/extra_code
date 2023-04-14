<?php

namespace Modules\Project\Console\Commands;

use Illuminate\Console\Command;

class ProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ProjectCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project Command description';

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
