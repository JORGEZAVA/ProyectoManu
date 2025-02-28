<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RecetasFactory extends Factory
{
    
    public function definition(): array{
        $tipo = ['desayuno', 'comida', 'cena'];
        $recetasFotos = [
            "CerdoAhumado.png",
            "Cocido.png",
            "Paella.png",
        ];
        $imageName = $this->faker->randomElement($recetasFotos);
        $imageUrl = asset('storage/' . 'img/' . $imageName); 
        $imageUrl = "http://localhost/ProyectoFinalCurso/public/storage/img/". $imageName; 
        return [
            'nombre' => $this->faker->name(),
            'descripcion' => $this->faker->paragraph,
            'imagen' => $imageUrl,
            'precio' => $this->faker->randomFloat(2, 1, 100),
            'tipo' => $this->faker->randomElement($tipo),
        ];
    }
}
