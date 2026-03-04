<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Ticket;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Flags whether tests should be skipped due to missing driver.
     */
    protected static bool $skipDb = false;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $conn = getenv('DB_CONNECTION') ?: null;
        if ($conn === 'sqlite' && !extension_loaded('pdo_sqlite')) {
            self::$skipDb = true;
        }
    }

    public function setUp(): void
    {
        if (self::$skipDb) {
            $this->markTestSkipped('SQLite driver not available.');
        }
        parent::setUp();
        // create roles
        Role::create(['name'=>'user']);
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'superadmin']);
    }

    public function test_user_can_create_and_view_ticket(): void
    {
        if (config('database.default') === 'sqlite' && !extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('SQLite driver not available');
        }

        $user = User::factory()->create(['role_id' => Role::firstWhere('name','user')->id]);

        $this->actingAs($user)->post('/tickets', [
            'title' => 'My ticket',
            'description' => 'details',
            'priority' => 'low',
        ]);

        $this->assertDatabaseHas('tickets', ['title' => 'My ticket', 'user_id' => $user->id]);
        $this->assertDatabaseMissing('tickets', ['ticket_number' => null]);

        $ticket = Ticket::first();
        $this->assertStringStartsWith('T - ', $ticket->ticket_number);

        $ticket = Ticket::first();
        $this->actingAs($user)->get("/tickets/{$ticket->id}")->assertStatus(200)->assertSee($ticket->ticket_number);

        // create a second ticket and verify numbering increments
        $this->actingAs($user)->post('/tickets', [
            'title' => 'Another ticket',
            'description' => 'more details',
            'priority' => 'medium',
        ]);
        $second = Ticket::latest()->first();
        $this->assertTrue(substr($second->ticket_number, -2) === '02');
    }

    public function test_admin_can_see_all_tickets_and_update_status(): void
    {
        if (config('database.default') === 'sqlite' && !extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('SQLite driver not available');
        }

        $user = User::factory()->create(['role_id' => Role::firstWhere('name','user')->id]);
        $admin = User::factory()->create(['role_id' => Role::firstWhere('name','admin')->id]);

        $ticket = Ticket::create([
            'title'=>'foo',
            'description'=>'bar',
            'priority'=>'medium',
            'status'=>'open',
            'user_id'=>$user->id,
        ]);
        $this->assertNotNull($ticket->ticket_number);
        $this->assertStringStartsWith('T - ', $ticket->ticket_number);

        $this->actingAs($admin)->get('/tickets')->assertSee('foo');

        $this->actingAs($admin)->patch("/tickets/{$ticket->id}", ['status' => 'in_progress']);
        $ticket->refresh();
        $this->assertEquals('in_progress', $ticket->status);

        // admin dashboard should still show the ticket after update
        $this->actingAs($admin)->get('/admin')->assertSee('foo');
    }
}
