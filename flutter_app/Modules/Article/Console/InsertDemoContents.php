<?php

namespace Modules\Article\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Article\Entities\Category;
use Modules\Article\Entities\Post;
use Modules\Comment\Entities\Comment;
use Modules\Tag\Entities\Tag;

class InsertDemoContents extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'starter:insert-demo-data {--fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert demo data for posts, categories. --fresh option will truncate the tables.';

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
        Auth::loginUsingId(1);

        $fresh = $this->option('fresh');

        if ($fresh) {
            if ($this->confirm('Database tables (posts, categories) will become empty. Confirm truncate tables?')) {

                // Disable foreign key checks!
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                /**
                 * posts table truncate.
                 */
                DB::table('posts')->truncate();
                $this->info('Truncate Table: posts');

                /**
                 * Categories table truncate.
                 */
                DB::table('categories')->truncate();
                $this->info('Truncate Table: categories');

                // Enable foreign key checks!
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }

        $this->newLine();

        /**
         * Categories.
         */
        $this->info('Inserting Categories');
        Category::factory()->count(5)->create();

        /**
         * Posts.
         */
        $this->info('Inserting Posts');
        Post::factory()->count(25)->create();

        $this->newLine(2);
        $this->info('-- Completed --');
        $this->newLine();
    }
}
