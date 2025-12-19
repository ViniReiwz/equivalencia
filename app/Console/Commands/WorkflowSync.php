<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WorkflowSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza o baco de dados com as novas definições de formulário para cada workflow, tratando edições, adições e remoções.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
    }
}
