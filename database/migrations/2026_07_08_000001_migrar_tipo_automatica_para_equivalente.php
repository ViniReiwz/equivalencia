<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Aumenta o tamanho da coluna `tipo` para acomodar os novos valores
        DB::statement('ALTER TABLE `aproveitamentos` MODIFY `tipo` VARCHAR(30) NOT NULL');

        DB::table('aproveitamentos')
            ->where('tipo', 'automatica')
            ->update(['tipo' => 'automatica_equivalente']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('aproveitamentos')
            ->whereIn('tipo', ['automatica_equivalente', 'automatica_desequivalente'])
            ->update(['tipo' => 'automatica']);

        DB::statement('ALTER TABLE `aproveitamentos` MODIFY `tipo` VARCHAR(20) NOT NULL');
    }
};
