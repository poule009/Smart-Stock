<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->onDelete('set null');
            $table->string('nom');
            $table->string('unite')->comment('kg, litre, unité, portion');
            $table->integer('seuil_alerte')->default(5);
            $table->timestamp('cree_le')->nullable()->useCurrent();
            $table->timestamp('mise_a_jour_le')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('supprime_le');
            
         
            $table->index('categorie_id');
            $table->index('fournisseur_id');
            $table->index('nom');
            $table->index(['categorie_id', 'seuil_alerte']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
