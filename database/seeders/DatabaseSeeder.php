<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        // Create Sample Data for Web Catalogue
        $cat1 = \App\Models\Categoria::firstOrCreate(['name' => 'Desarrollo Web']);
        $cat2 = \App\Models\Categoria::firstOrCreate(['name' => 'Administración']);

        $tema1 = \App\Models\Tema::firstOrCreate(['name' => 'Laravel Framework']);
        $tema2 = \App\Models\Tema::firstOrCreate(['name' => 'Excel para Negocios']);

        \App\Models\Producto::firstOrCreate([
            'sku' => 'CUR-001',
        ], [
            'tema_id' => $tema1->id,
            'categoria_id' => $cat1->id,
            'tipo_producto' => 'curso',
            'descripcion' => 'Domina el desarrollo web profesional con Laravel 12.',
            'precio' => 250.00,
            'status' => 'active',
            'fecha_inicio' => now()->addDays(7),
        ]);

        \App\Models\Producto::firstOrCreate([
            'sku' => 'CUR-002',
        ], [
            'tema_id' => $tema2->id,
            'categoria_id' => $cat2->id,
            'tipo_producto' => 'curso',
            'descripcion' => 'Herramientas esenciales de Excel para la gestión empresarial.',
            'precio' => 150.00,
            'status' => 'active',
            'fecha_inicio' => now()->addDays(14),
        ]);
    }
}
