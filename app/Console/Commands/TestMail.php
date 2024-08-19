<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;

/**
 * Тест Email-отправки.
 */
class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тест Email-отправки';

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

        // $result = Mail::to(['nr.kondrashov@gmail.com', '4progs@inbox.ru'])->send(new \App\Mail\TestMail());

        $user = User::first();
        $user->notify(new VerifyEmail());

        $this->newLine();
        $duration = (Carbon::now())->diffInSeconds($startTime);
        $this->info("Время выполнения (сек.): $duration.");

        return 0;
    }
}
