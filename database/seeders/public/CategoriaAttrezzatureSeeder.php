<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaAttrezzatureSeeder extends Seeder
{
    public function run()
    {
        DB::table('categorie_attrezzature')->insert([
            ['nome' => 'Antincendio'],
            ['nome' => 'Sollevamento'],
            ['nome' => 'Produzione'],
            ['nome' => 'Elettriche'],
            ['nome' => 'Utensileria'],
            ['nome' => 'Impianti a pressione'],
        ]);
    }
}
