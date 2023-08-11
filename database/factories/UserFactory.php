<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    // define the model class for which this factory creates elements,
    // otherwise Laravel will try to use naming conventions to figure out
    // the corresponding model class
    protected $model = User::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $firstName = fake()->unique()->firstName();
        $lastName = fake()->lastName();

        $username = strtolower($firstName);
        $name = $firstName . ' ' . $lastName;
        $email = strtolower($firstName) . '.' . strtolower($lastName) . '@example.com';
        $password = Hash::make($username);

        return [
            'username' => $username,
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => $password,
            'remember_token' => Str::random(10)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
