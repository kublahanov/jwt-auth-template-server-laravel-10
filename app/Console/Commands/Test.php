<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Тест вывода в консольных командах.
 */
class Test extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'test:index';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Тест вывода в консольных командах';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle(): int
    {
        $this->newLine();
        $this->alert($this->description);

        $startTime = Carbon::now();

        $this->newLine();
        $this->alert('alert'); // Жёлтый
        $this->info('info'); // Зелёный
        $this->line('line'); // Белый
        $this->comment('comment'); // Жёлтый
        $this->question('question'); // Голубой
        $this->error('error'); // Красный
        $this->warn('warn'); // Жёлтый

        // Log::stack(['stderr'])->error('error');
        // Log::stack(['stderr'])->info('info');
        // Log::stack(['stderr'])->alert('alert');
        // Log::stack(['stderr'])->critical('critical');
        // Log::stack(['stderr'])->debug('debug');
        // Log::stack(['stderr'])->emergency('emergency');
        // Log::stack(['stderr'])->notice('notice');
        // Log::stack(['stderr'])->warning('warning');

        // $channel = Log::build([
        //     'driver' => 'single',
        //     'path' => storage_path('logs/custom.log'),
        // ]);

        // Log::channel($channel)->error('error');
        // Log::channel($channel)->info('info');
        // Log::channel($channel)->alert('alert');
        // Log::channel($channel)->critical('critical');
        // Log::channel($channel)->debug('debug');
        // Log::channel($channel)->emergency('emergency');
        // Log::channel($channel)->notice('notice');
        // Log::channel($channel)->warning('warning');

        // file_put_contents(
        //     storage_path('logs/custom.log'),
        //     PHP_EOL . date('Y-m-d H:i:s') . ' :: ' . print_r([1, 2, 3], true)
        // );

        $duration = (Carbon::now())->diffInSeconds($startTime);
        $this->info("Время выполнения (сек.): $duration.");

        return 0;
    }
}
