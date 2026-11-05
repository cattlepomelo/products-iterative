<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake();
        $name = $faker->randomElement([
            'Ābolu sula 1 L',
            'Auzu pārslas 500 g',
            'Kafijas pupiņas 250 g',
            'Zaļā tēja 20 paciņas',
            'Pilngraudu makaroni 500 g',
            'Tomātu mērce 350 g',
            'Medus 400 g',
            'Olīveļļa 500 ml',
            'Basmati rīsi 1 kg',
            'Tumšā šokolāde 100 g',
            'Minerālūdens 1,5 L',
            'Zemeņu ievārījums 300 g',
            'Mandeles 200 g',
            'Kokosriekstu piens 400 ml',
            'Linsēklas 300 g',
            'Kviešu milti 1 kg',
            'Dārzeņu buljons 10 kubiņi',
            'Bērnu ziepes 100 g',
            'Trauku mazgāšanas līdzeklis 500 ml',
            'Papīra dvieļi 2 ruļļi',
            'Zobu pasta 75 ml',
            'Šampūns 250 ml',
            'Veļas pulveris 1 kg',
            'Medikamentu aptieciņa 1 gab.',
            'Atkārtoti lietojama ūdens pudele 750 ml',
        ]);
        $quantity = $faker->numberBetween(0, 250);
        $expirationDate = $faker->optional(0.8)->dateTimeBetween('-6 months', '+2 years');

        return [
            'name' => $name,
            'quantity' => $quantity,
            'description' => "Kvalitatīvs produkts ikdienas lietošanai: {$name}.",
            'expiration_date' => $expirationDate?->format('Y-m-d'),
            'status' => match (true) {
                $expirationDate !== null && $expirationDate < new \DateTime => 'Beidzies derīguma termiņš',
                $quantity === 0 => 'Nav pieejams',
                $quantity <= 5 => 'Maz atlikumā',
                default => 'Pieejams',
            },
        ];
    }
}
