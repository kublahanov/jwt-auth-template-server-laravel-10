<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Support\Carbon;

/**
 * Тест парсера файлов.
 */
class TestParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Тест парсера файлов';

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

        $directory = storage_path('/runtime/parse');
        $files = $this->getFilesList($directory);

        // dump($files);

        $this->newLine();

        foreach ($files as $fileName) {
            $fullPath = "$directory/$fileName";
            $this->line("Try to parse: $fullPath.");

            $this->line('YAML:');
            // try {
            //     var_dump(yaml_parse_file($fullPath));
            // } catch (Exception $exception) {
            //     $this->info($exception->getMessage());
            // }

            $this->line('INI:');
            // try {
            //     var_dump(parse_ini_file($fullPath));
            // } catch (Exception $exception) {
            //     $this->info($exception->getMessage());
            // }
        }

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

    /**
     * @param $directory
     * @return array|false
     */
    protected function getFilesList($directory): false|array
    {
        $fileList = [];

        if (!is_dir($directory)) {
            return false;
        }

        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                if (is_file($directory . '/' . $file)) {
                    $fileList[] = $file;
                }
            }
        }

        return $fileList;
    }
}
