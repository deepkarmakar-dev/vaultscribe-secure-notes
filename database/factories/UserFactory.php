<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            
            // Jetstream columns hata kar aapke custom columns add kar diye hain:
            'google2fa_secret' => null,
            'google2fa_enabled' => false,
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

    /**
     * Indicate that the model has custom two-factor authentication configured.
     */
/**
 * Indicate that the model has custom two-factor authentication configured.
 */
public function withTwoFactor(): static
{
    return $this->state(fn (array $attributes) => [
        // 1. User model automatic encrypt kar dega, isliye yahan plain text bhej rahe hain.
        // 2. Har user ke liye ek naya aur unique valid Base32 secret generate hoga.
        'google2fa_secret' => \PragmaRX\Google2FALaravel\Facade::generateSecretKey(),
        'google2fa_enabled' => true,
    ]);
}
}