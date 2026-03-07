<?php

namespace App\Infrastructure\Services;

use App\Application\Contracts\TransactionManager;
use Illuminate\Support\Facades\DB;

class EloquentTransactionManager implements TransactionManager
{
    public function run(callable $callback): mixed
    {
        return DB::transaction($callback);
    }
}
