<?php
namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_event()
    {
        $user = User::factory()->create(); // ユーザーを作成
        $this->actingAs($user); // ユーザーとしてログイン

        $response = $this->post(route('events.store'), [
            'name' => 'Test Event',
        ]);

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseHas('events', [
            'name' => 'Test Event',
            'user_id' => $user->id,
        ]);
    }

    public function test_destroy_deletes_event()
    {
        $user = User::factory()->create(); // ユーザーを作成
        $this->actingAs($user); // ユーザーとしてログイン

        $event = Event::factory()->create(['user_id' => $user->id]); // イベントを作成

        $response = $this->delete(route('events.destroy', $event->uuid));

        $response->assertRedirect(route('events.index'));
        $this->assertDatabaseMissing('events', [
            'uuid' => $event->uuid,
        ]);
    }
}
