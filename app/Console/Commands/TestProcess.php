<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Process;

/**
 * Тест выполнения процессов.
 */
class TestProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тест выполнения процессов';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->newLine();
        $this->alert($this->description);

        $startTime = Carbon::now();

        $result = Process::run('ls -la');

        echo $result->output();

        $this->newLine();

        // $this->newLine();
        // $this->alert('alert'); // Жёлтый
        // $this->info('info'); // Зелёный
        // $this->line('line'); // Белый
        // $this->comment('comment'); // Жёлтый
        // $this->question('question'); // Голубой
        // $this->error('error'); // Красный
        // $this->warn('warn'); // Жёлтый

        $duration = (Carbon::now())->diffInSeconds($startTime);
        $this->info("Время выполнения (сек.): $duration.");

        return 0;
    }
}
