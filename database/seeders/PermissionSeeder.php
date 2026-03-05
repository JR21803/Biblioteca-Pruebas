<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bibliotecario = Role::firstOrCreate(['name' => 'bibliotecario', 'guard_name' => 'api']);

        $docente = Role::firstOrCreate(['name' => 'docente', 'guard_name' => 'api']);

        $estudiante = Role::firstOrCreate(['name' => 'estudiante', 'guard_name' => 'api']);


        collect([
            'create book',
            'update book',
            'delete book',
            'create loan',
            'return loan',
        
        ])->each(function (string $permission) use ($bibliotecario, $docente, $estudiante): void {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        });

        $bibliotecario->givePermissionTo(['create book', 'update book', 'delete book']);

        $docente->givePermissionTo(['create loan', 'return loan']);
    
        $estudiante->givePermissionTo(['create loan', 'return loan']);

        $userBibliotecario = User::firstOrCreate(['email' => 'bibliotecario@example.com',
        ], [
            'name' => 'Bibliotecario',
            'password' => bcrypt('password'),
        ]);

        $userDocente = User::firstOrCreate(['email' => 'docente@example.com',
        ], [
            'name' => 'Docente',
            'password' => bcrypt('password'),
        ]);

        $userEstudiante = User::firstOrCreate(['email' => 'estudiante@example.com',
        ], [
            'name' => 'Estudiante',
            'password' => bcrypt('password'),
        ]);

        $userBibliotecario->assignRole($bibliotecario);
        $userDocente->assignRole($docente);
        $userEstudiante->assignRole($estudiante);

    }
}
