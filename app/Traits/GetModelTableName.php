<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use LogicException;

trait GetModelTableName
{
    use TagCreator;

    public static function getTableName()
    {
        if (!is_subclass_of(self::class, Model::class)) {
            throw new LogicException('Class must extend ' . Model::class);
        }

        $cacheTag = self::getTagName(__FUNCTION__);

        $mockModel = Cache::rememberForever($cacheTag, function () {
            return new self();
        });

        return $mockModel->getTable();
    }
}
