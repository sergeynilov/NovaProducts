<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
//        $table->enum('membership_mark', ['N', 'M', 'S', 'G'])->default("N")->comment(' N => No membership, M - Member, S=>Silver Membership, G=>Gold Membership');
        return [
            'name' => 'User ' . $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'status' => $this->faker->randomElement(['N', 'A', 'I', 'B']),
//            'membership_mark' => $this->faker->randomElement(['N', 'M', 'S', 'G']),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-1 month', '-1 hour'),
        ];
    }

//    public function configure()
//    {
//        return $this->afterCreating(function (User $user) { // Ad model is returned here
//
//            \Log::info(  varDump($user->id, ' -1 afterCreating $user->id::') );
//            $userProfile = UserProfile::factory()->count(1)->create([
//                'user_id' => $user->id,
//                'membership_mark' => $this->faker->randomElement(['N', 'M', 'S', 'G']),
//                'phone' => $this->faker->phoneNumber(),
//                'website' => $this->faker->url(),
//                'notes' => $this->faker->paragraphs(rand(1, 4), true),
//
//            ]);
//            \Log::info(  varDump($userProfile, ' -1 userProfile::') );
////            $ad_factory->category()->attach($this->faker->randomElement(Category::all())['id']);
//        });
//    }


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
