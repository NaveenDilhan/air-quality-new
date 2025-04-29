<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Sensor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SensorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_sensor()
    {
        $sensor = Sensor::create([
            'name' => 'Test Sensor',
            'location' => 'Colombo',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('sensors', [
            'name' => 'Test Sensor',
            'location' => 'Colombo',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function it_can_update_a_sensor()
    {
        $sensor = Sensor::factory()->create();

        $sensor->update([
            'name' => 'Updated Sensor Name',
        ]);

        $this->assertDatabaseHas('sensors', [
            'id' => $sensor->id,
            'name' => 'Updated Sensor Name',
        ]);
    }

    /** @test */
    public function it_can_delete_a_sensor()
    {
        $sensor = Sensor::factory()->create();

        $sensor->delete();

        $this->assertDatabaseMissing('sensors', [
            'id' => $sensor->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Sensor::create([
            // missing required fields
        ]);
    }
}
