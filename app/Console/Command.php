<?php

namespace App\Console;

use Illuminate\Console\Command as BaseCommand;

/**
 * Базовый класс для консольных команд.
 */
class Command extends BaseCommand
{
    /**
     * @inheritDoc.
     */
    public function __construct()
    {
        parent::__construct();

        /**
         * Отделяем собственные команды (перемещением в нижнюю часть списка).
         */
        $this->signature = "x-{$this->signature}";
        $this->configureUsingFluentDefinition();
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }
}
