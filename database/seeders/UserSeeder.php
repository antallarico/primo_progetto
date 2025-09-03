<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $utenti = [
            [
                'nome' => 'Antonio',
                'cognome' => 'Tal',
				'name' => 'Antonio',
                'email' => 'mario.rossi@example.com',
                'password' => Hash::make('password1'),
                'ruolo' => 'admin',
                'telefono' => '3331111111',
                'attivo' => true,
            ],
            [
                'nome' => 'Antonio',
                'cognome' => 'Bianchi',
				'name' => 'Antonio',
                'email' => 'luca.bianchi@example.com',
                'password' => Hash::make('password2'),
                'ruolo' => 'RSPP',
                'telefono' => '3332222222',
                'attivo' => true,
            ],
            [
                'nome' => 'Raffaele',
                'cognome' => 'Verdi',
				'name' => 'Raffaele',
                'email' => 'giulia.verdi@example.com',
                'password' => Hash::make('password3'),
                'ruolo' => 'IT Manager',
                'telefono' => '3333333333',
                'attivo' => true,
            ],
            [
                'nome' => 'Paolo',
                'cognome' => 'Neri',
				'name' => 'Paolo',
                'email' => 'paolo.neri@example.com',
                'password' => Hash::make('password4'),
                'ruolo' => 'visitatore',
                'telefono' => '3334444444',
                'attivo' => false,
            ],
            [
                'nome' => 'Nicandro',
                'cognome' => 'Gialli',
				'name' => 'Nicandro',
                'email' => 'laura.gialli@example.com',
                'password' => Hash::make('password5'),
                'ruolo' => 'manutentore',
                'telefono' => '3335555555',
                'attivo' => true,
            ],
        ];

        foreach ($utenti as $u) {
            User::create($u);
        }
    }
}
