<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:process-monthly-stats')]
#[Description('Command description')]
class ProcessMonthlyStats extends Command
{
	protected $signature = 'stats:process';
	protected $description = 'Розрахунок щомісячної статистики';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->info('Статистику успішно оброблено!');
	}
}
