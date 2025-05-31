<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

abstract class BaseTestCase extends TestCase
{
    use RefreshDatabase;

    protected Model $model;

    protected string $table;

    protected array $columns;

    public function test_it_should_have_a_table(): void
    {
        $this->assertEquals($this->table, $this->model->getTable());
    }

    public function test_it_should_have_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns(
                $this->model->getTable(),
                $this->columns
            )
        );
    }

    public function test_it_should_have_correct_columns_count(): void
    {
        $this->assertCount(
            count(Schema::getColumnListing($this->model->getTable())),
            $this->columns
        );
    }
}
