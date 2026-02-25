<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('pagos')) {
            return;
        }

        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->integer('id_estudiante');
            $table->string('concepto', 120);
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->enum('medio_pago', ['Efectivo', 'Transferencia', 'Tarjeta', 'Yape', 'Plin', 'Otro']);
            $table->integer('registrado_por');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('id_estudiante')->references('id_estudiante')->on('estudiantes');
            $table->foreign('registrado_por')->references('id_usuario')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
