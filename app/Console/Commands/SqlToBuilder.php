<?php

namespace GitterBot\Console\Commands;

use BigShark\SQLToBuilder\BuilderClass;
use Illuminate\Console\Command;

class SqlToBuilder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sql:build {string}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
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
        $sql = $this->argument('string');

        try {
            $builder = new BuilderClass($sql);
            echo $builder->convert();
        } catch (\Exception $e) {
            echo "Error!";
        }

        return false;
    }
}
