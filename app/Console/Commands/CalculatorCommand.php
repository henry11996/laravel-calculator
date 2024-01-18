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
    protected $signature = 'app:calculator {--scale=10}';

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
        $maxScale = $this->option('scale');

        while (true) {
            $expression = $this->ask("Enter expression. Scale: {$maxScale} (Ctrl+D to exit)");

            try {
                $this->info(
                    'Result: '.
                    (new Calculator(maxScale: $maxScale))->calculate(strval($expression)));
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        }

        return 0;
    }
}
