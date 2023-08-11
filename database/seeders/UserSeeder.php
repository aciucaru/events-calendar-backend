<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(SeedConstants::USERS_COUNT)->create();

        // User::factory()->create([
        //     'username' => 'susan',
        //     'name' => 'Susan Castrejon',
        //     'email' => 'susan.castrejon@example.com',
        //     'password' => Hash::make('susan')
        // ]);

        // User::factory()->create([
        //     'username' => 'jorg',
        //     'name' => 'Jorg Junker',
        //     'email' => 'jorg.junker@example.com',
        //     'password' => Hash::make('jorg')
        // ]);

        // User::factory()->create([
        //     'username' => 'nadine',
        //     'name' => 'Nadine Blau',
        //     'email' => 'nadine.blau@example.com',
        //     'password' => Hash::make('nadine')
        // ]);

        // User::factory()->create([
        //     'username' => 'paulo',
        //     'name' => 'Paulo Carvalho Rodrigues',
        //     'email' => 'paulo.rodrigues@example.com',
        //     'password' => Hash::make('paulo')
        // ]);

        // User::factory()->create([
        //     'username' => 'erno',
        //     'name' => 'Erno Vayrynen',
        //     'email' => 'erno.vayrynen@example.com',
        //     'password' => Hash::make('erno')
        // ]);

        // User::factory()->create([
        //     'username' => 'elise',
        //     'name' => 'Elise Fredriksson',
        //     'email' => 'elise.fredriksson@example.com',
        //     'password' => Hash::make('elise')
        // ]);

        // User::factory()->create([
        //     'username' => 'antonietta',
        //     'name' => 'Antonietta Pirozzi',
        //     'email' => 'antonietta.pirozzi@example.com',
        //     'password' => Hash::make('antonietta')
        // ]);

        // User::factory()->create([
        //     'username' => 'vincenzo',
        //     'name' => 'Vincenzo Arcuri',
        //     'email' => 'vincenzo.arcuri@example.com',
        //     'password' => Hash::make('vincenzo')
        // ]);

        // User::factory()->create([
        //     'username' => 'yunus',
        //     'name' => 'Yunus van Rijn',
        //     'email' => 'yunus.van.rijn@example.com',
        //     'password' => Hash::make('yunus')
        // ]);

        // User::factory()->create([
        //     'username' => 'rilana',
        //     'name' => 'Rilana van Milligen',
        //     'email' => 'rilana.van.milligen@example.com',
        //     'password' => Hash::make('rilana')
        // ]);

        // User::factory()->create([
        //     'username' => 'archie',
        //     'name' => 'Archie Henderson',
        //     'email' => 'archie.henderson@example.com',
        //     'password' => Hash::make('archie')
        // ]);

        // User::factory()->create([
        //     'username' => 'lily',
        //     'name' => 'Lily Dalley',
        //     'email' => 'lily.dalley@example.com',
        //     'password' => Hash::make('lily')
        // ]);

        // User::factory()->create([
        //     'username' => 'koen',
        //     'name' => 'Koen van Dieren',
        //     'email' => 'koen.van.dieren@example.com',
        //     'password' => Hash::make('koen')
        // ]);

        // User::factory()->create([
        //     'username' => 'kelly',
        //     'name' => 'Kelly Koppenol',
        //     'email' => 'kelly.koppenol@example.com',
        //     'password' => Hash::make('kelly')
        // ]);

        // User::factory()->create([
        //     'username' => 'jakub',
        //     'name' => 'Jakub Doan',
        //     'email' => 'jakub.doan@example.com',
        //     'password' => Hash::make('jakub')
        // ]);

        // User::factory()->create([
        //     'username' => 'camille',
        //     'name' => 'Camille Croteau',
        //     'email' => 'camille.croteau@example.com',
        //     'password' => Hash::make('camille')
        // ]);

        // User::factory()->create([
        //     'username' => 'henri',
        //     'name' => 'Henri Paimboeuf',
        //     'email' => 'henri.paimboeuf@example.com',
        //     'password' => Hash::make('henri')
        // ]);

        // User::factory()->create([
        //     'username' => 'bertram',
        //     'name' => 'Bertram A. Schou',
        //     'email' => 'bertram.schou@example.com',
        //     'password' => Hash::make('bertram')
        // ]);

        // User::factory()->create([
        //     'username' => 'tiina',
        //     'name' => 'Tiina Virolainen',
        //     'email' => 'tiina.virolainen@example.com',
        //     'password' => Hash::make('tiina')
        // ]);

        // User::factory()->create([
        //     'username' => 'kalervo',
        //     'name' => 'Kalervo Paasivirta',
        //     'email' => 'kalervo.paasivirta@example.com',
        //     'password' => Hash::make('kalervo')
        // ]);

        // User::factory()->create([
        //     'username' => 'rowan',
        //     'name' => 'Rowan Laurens',
        //     'email' => 'rowan.laurens@example.com',
        //     'password' => Hash::make('rowan')
        // ]);

        // User::factory()->create([
        //     'username' => 'beatriz',
        //     'name' => 'Beatriz Dias Carvalho',
        //     'email' => 'beatriz.dias.carvalho@example.com',
        //     'password' => Hash::make('beatriz')
        // ]);

        // User::factory()->create([
        //     'username' => 'gustavo',
        //     'name' => 'Gustavo Souza Araujo',
        //     'email' => 'gustavo.souza.araujo@example.com',
        //     'password' => Hash::make('gustavo')
        // ]);
    }
}
