<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalanceHistorySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Заполняем таблицу balance_history тестовыми данными...');

        $accounts = range(1, 5);         // 5 аккаунтов
        $currencies = range(1, 3);       // 3 валюты
        $days = 40;                      // 40 дней назад
        $rowsPerDay = 3;                 // 3 записи на день

        $batchSize = 1000;
        $insertData = [];

        foreach ($accounts as $accountId) {
            foreach ($currencies as $currencyId) {
                for ($d = 0; $d < $days; $d++) {
                    $date = now()->subDays($d);
                    for ($i = 0; $i < $rowsPerDay; $i++) {
                        $createdAt = $date->copy()->setTime(10 + $i, rand(0, 59), 0);
                        $insertData[] = [
                            'account_id'  => $accountId,
                            'currency_id' => $currencyId,
                            'amount'      => rand(10000, 99999) / 100,
                            'created_at'  => $createdAt,
                            'updated_at'  => $createdAt,
                        ];

                        // Если достигли лимита батча — вставляем
                        if (count($insertData) >= $batchSize) {
                            DB::table('balance_history')->insert($insertData);
                            $insertData = [];
                        }
                    }
                }
            }
        }

        // Вставка оставшихся
        if (!empty($insertData)) {
            DB::table('balance_history')->insert($insertData);
        }

        $this->command->info('Готово! Тестовые данные добавлены.');
    }
}

