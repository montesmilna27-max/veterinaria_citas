<?php
// stubs.php - declaraciones para que PHPStan conozca funciones/procedimientos usados en el proyecto

if (!function_exists('require_role')) {
    /**
     * Stub de require_role solo para análisis estático.
     * Implementa la lógica real en tu proyecto si lo necesitas.
     *
     * @param string $role
     * @return void
     */
    function require_role(string $role): void
    {
        // no hace nada (solo existe para que PHPStan no marque error)
    }
}

// Añade aquí otras funciones que PHPStan diga que faltan
// if (!function_exists('otra_funcion')) { function otra_funcion(...) { } }
