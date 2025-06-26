<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanBalanceHistory extends Command
{
    protected $signature = 'balance-history:clean';
    protected $description = 'Удаляет дубликаты записей из balance_history, оставляя первую запись на день для каждой пары account_id и currency_id';

    public function handle(): int
    {
        $this->info("Начинаем очистку balance_history...");

        $dates = DB::table('balance_history')
            ->selectRaw('DATE(created_at) as day')
            ->where('created_at', '<', now()->subMonth())
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day')
            ->pluck('day');

        foreach ($dates as $date) {
            $this->info("Обрабатывается дата: {$date}");

            $deleted = DB::delete("
                DELETE FROM balance_history
                WHERE id IN (
                    SELECT id FROM (
                        SELECT id,
                               ROW_NUMBER() OVER (
                                   PARTITION BY account_id, currency_id
                                   ORDER BY created_at
                               ) AS row_num
                        FROM balance_history
                        WHERE DATE(created_at) = ?
                    ) t
                    WHERE row_num > 1
                )
            ", [$date]);

            $this->info("Удалено записей за {$date}: {$deleted}");
        }

        $this->info("Очистка завершена.");
        return Command::SUCCESS;
    }
}
