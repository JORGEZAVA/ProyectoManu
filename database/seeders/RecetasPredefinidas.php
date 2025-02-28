<?php

namespace Database\Seeders;

use App\Models\Recetas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecetasPredefinidas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Recetas::factory()->count(10)->create();
    }
}