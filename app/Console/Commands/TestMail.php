<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

/**
 * Тест Email-отправки.
 */
class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'test:mail';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Тест Email-отправки';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $this->newLine();
        $this->alert($this->description);

        $startTime = Carbon::now();

        $result = Mail::to(['nr.kondrashov@gmail.com', '4progs@inbox.ru'])->send(new \App\Mail\TestMail());

        var_dump($result);

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
        $this->info("Время выполнения (сек.): {$duration}.");

        return 0;
    }
}
