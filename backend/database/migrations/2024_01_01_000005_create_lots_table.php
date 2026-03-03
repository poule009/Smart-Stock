<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->decimal('quantite_initiale', 10, 2);
            $table->decimal('quantite_actuelle', 10, 2)->comment('Stock physique restant');
            $table->date('date_expiration')->comment('Donnée critique pour le FEFO');
            $table->decimal('prix_achat_unitaire', 10, 2)->nullable();
            $table->timestamp('cree_le')->nullable()->useCurrent();
            $table->timestamp('mise_a_jour_le')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            
            $table->index('produit_id');
            $table->index(['produit_id', 'date_expiration']);
            $table->index(['date_expiration', 'quantite_actuelle']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
