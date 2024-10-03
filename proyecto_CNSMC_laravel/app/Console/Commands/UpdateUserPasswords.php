<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswords extends Command
{
    // Nombre del comando
    protected $signature = 'update:passwords';
    // Descripción del comando
    protected $description = 'Actualiza todas las contraseñas de usuarios para usar Bcrypt';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener todos los usuarios
        $users = User::all();

        foreach ($users as $user) {
            // Verificar si la contraseña ya está hasheada (bcrypt usa "$2y$" como prefijo)
            if (!Hash::needsRehash($user->password)) {
                continue; // Si la contraseña ya está hasheada, saltar al siguiente usuario
            }

            // Actualizar la contraseña con Bcrypt
            $user->password = Hash::make($user->password);
            $user->save();

            $this->info("Contraseña actualizada para el usuario: " . $user->email);
        }

        $this->info('Contraseñas actualizadas exitosamente.');
    }
}
