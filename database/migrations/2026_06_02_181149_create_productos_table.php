<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_insumo', ['lacteo', 'mezcla_cereales']); // Clasificación oficial PVL
            $table->string('nombre', 150);       // Ej: Leche Evaporada Entera
            $table->string('marca', 100);        // Ej: Gloria
            $table->string('numero_lote', 50);   // Registro de Sanidad / DIGESA
            $table->date('fecha_vencimiento');   // Control de caducidad
            $table->string('unidad_medida', 50); // Tarro de 410g, Bolsa de 250g
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(20); // Alerta para que no se quede vacío
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
