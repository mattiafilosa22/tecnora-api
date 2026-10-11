<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_returns_ok_when_database_is_reachable(): void
    {
        $this->getJson('/api/health')
            ->assertOk()
            ->assertExactJson(['status' => 'ok', 'db' => true]);
    }

    public function test_health_reports_db_false_when_database_is_unreachable(): void
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/nonexistent/tecnora.sqlite',
        ]);
        DB::purge('sqlite');

        $this->getJson('/api/health')
            ->assertOk()
            ->assertExactJson(['status' => 'ok', 'db' => false]);
    }

    public function test_cors_allows_frontend_origin(): void
    {
        $this->getJson('/api/health', ['Origin' => 'http://localhost:5173'])
            ->assertHeader('Access-Control-Allow-Origin', 'http://localhost:5173');
    }
}
