<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:seed-if-empty', function () {
    if (User::query()->exists()) {
        $this->info('Database already has data, skipping seed.');

        return;
    }

    // No users means nothing real to keep: rebuild the tables so a half-finished earlier seed can't clash.
    $this->call('migrate:fresh', ['--force' => true]);
    $this->call('db:seed', ['--class' => DatabaseSeeder::class, '--force' => true]);
})->purpose('Seed the demo data only on a fresh database');
