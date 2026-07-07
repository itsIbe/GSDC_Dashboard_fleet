<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class TestSqlsrvConnection extends Command
{
    protected $signature = 'db:test-sqlsrv';
    protected $description = 'Test SQL Server (sqlsrv) database connection';

    public function handle()
    {
        try {
            DB::connection('sqlsrv')->getPdo();
            $this->info('✅ SQL Server connection successful!');
        } catch (Exception $e) {
            $this->error('❌ Connection failed: ' . $e->getMessage());
        }
    }
}
