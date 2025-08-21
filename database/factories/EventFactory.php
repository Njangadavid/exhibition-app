<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+6 months');
        $endDate = Carbon::parse($startDate)->addDays($this->faker->numberBetween(1, 14));
        
        $eventTypes = [
            'Modern Art Exhibition',
            'Photography Showcase',
            'Sculpture Garden',
            'Digital Art Festival',
            'Contemporary Masters',
            'Emerging Artists',
            'International Art Fair',
            'Student Art Show',
            'Gallery Opening',
            'Art Workshop Series'
        ];

        $descriptions = [
            'A stunning collection of contemporary artworks that push the boundaries of traditional art forms.',
            'Explore the world through the lens of talented photographers capturing life\'s beautiful moments.',
            'Immerse yourself in three-dimensional art that transforms spaces and challenges perceptions.',
            'Experience the future of art with cutting-edge digital installations and interactive exhibits.',
            'Discover masterpieces from renowned artists who have shaped the contemporary art landscape.',
            'Support the next generation of artists as they showcase their innovative and creative works.',
            'Join us for a global celebration of art, culture, and creative expression.',
            'Celebrate the creativity and talent of emerging student artists from local institutions.',
            'Be among the first to experience this exciting new gallery space and its inaugural exhibition.',
            'Learn new techniques and expand your artistic skills in our hands-on workshop series.'
        ];

        return [
            'title' => $this->faker->randomElement($eventTypes),
            'description' => $this->faker->randomElement($descriptions),
            'status' => $this->faker->randomElement(['draft', 'published', 'active', 'completed']),
            'logo' => null, // We'll handle logo uploads separately
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5),
        ]);
    }

    /**
     * Indicate that the event is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'start_date' => Carbon::now()->addDays(7),
            'end_date' => Carbon::now()->addDays(14),
        ]);
    }

    /**
     * Indicate that the event is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->subDays(20),
        ]);
    }
}
