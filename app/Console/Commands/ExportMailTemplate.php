<?php

namespace App\Console\Commands;

use App\Console\Command;
use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

/**
 * Экспорт шаблона письма в виде HTML.
 */
class ExportMailTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:export-mt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Экспорт шаблона письма в виде HTML';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->newLine();
        $this->alert($this->description);

        $startTime = Carbon::now();

        // Создайте экземпляр уведомления
        $notification = new VerifyEmail(config('app.url'));

        // Используйте временного пользователя для рендеринга
        $notifiable = new User(['email' => 'test@example.com']);

        // Рендеринг шаблона
        $mailMessage = $notification->toMail($notifiable);

        $html = $mailMessage->render();

        $timestamp = $startTime->getTimestamp();

        $path = resource_path("views/mail/exported.$timestamp.blade.php");

        // Сохранение HTML в файл
        File::put($path, $html);

        $this->info('Шаблон экспортирован успешно!');
        $this->info("Путь: $path.");

        $duration = (Carbon::now())->diffInSeconds($startTime);
        $this->info("Время выполнения (сек.): $duration.");

        return 0;
    }
}
