<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Jobs\TestJob as JobTestJob;
use Illuminate\Support\Carbon;

/**
 * Тест выполнения фонового задания.
 */
class TestJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тест выполнения фонового задания';

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

        JobTestJob::dispatch();

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
