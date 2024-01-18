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

    private bool $shouldKeepRunning = true;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->trap([SIGTERM, SIGQUIT], function (int $signal) {
            $this->shouldKeepRunning = false;
        });

        $maxScale = $this->ask('Enter max scale', 10);

        while ($this->shouldKeepRunning) {
            $expression = $this->ask('Enter expression');

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
