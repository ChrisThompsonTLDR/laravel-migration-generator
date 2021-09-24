<?php

namespace LaravelMigrationGenerator\GeneratorManagers;

use Illuminate\Support\Facades\DB;
use LaravelMigrationGenerator\Generators\MySQL\ViewGenerator;
use LaravelMigrationGenerator\Generators\MySQL\TableGenerator;
use LaravelMigrationGenerator\GeneratorManagers\Interfaces\GeneratorManagerInterface;

class MySQLGeneratorManager extends BaseGeneratorManager implements GeneratorManagerInterface
{
    public static function driver(): string
    {
        return 'mysql';
    }

    public function init()
    {
        $tables = DB::select('SHOW FULL TABLES');

        foreach ($tables as $rowNumber => $table) {
            $tableData = (array) $table;
            $table = $tableData[array_key_first($tableData)];
            $tableType = $tableData['Table_type'];
            if ($tableType === 'BASE TABLE') {
                $this->addTableDefinition(TableGenerator::init($table)->definition());
            } elseif ($tableType === 'VIEW') {
                $this->addViewDefinition(ViewGenerator::init($table)->definition());
            }
        }
    }
}
