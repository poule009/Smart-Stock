<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->enum('type', ['entree', 'sortie'])->comment('entrée (in) ou sortie (out)');
            $table->decimal('quantite', 10, 2);
            $table->string('raison')->comment('vente, perte, péremption, arrivage');
            $table->timestamps();
            $table->softDeletes();
            
           
            $table->index('lot_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
    }
};
