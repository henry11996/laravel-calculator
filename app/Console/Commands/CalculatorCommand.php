<?php

namespace App\Console\Commands;

use App\Calculator;
use Illuminate\Console\Command;

class CalculatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:calculator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculator';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while (true) {
            $expression = $this->ask('Enter expression. (Ctrl+D to exit)');

            try {
                $this->info(
                    'Result: '.
                    (new Calculator())->calculate(strval($expression)));
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        }

        return 0;
    }
}
