<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Like;
use App\Traits\HasLikes;
use App\Events\ModelLikedEvent;
use App\Events\ModelUnlikedEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasLikesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('model_with_likes', function ($table) {
            $table->id();
        });

        Event::fake([ModelLikedEvent::class, ModelUnlikedEvent::class]);
    }

    /**
     * @test
     */
    public function a_model_with_likes_morph_many_likes()
    {
        $modelWithLikes = new ModelWithLikes(['id' => 1]);

        Like::factory(2)->for($modelWithLikes, 'likeable')->create();

        $this->assertInstanceOf(Like::class, $modelWithLikes->likes->first());
    }

    /**
     * @test
     */
    public function a_model_with_likes_can_be_liked_and_unliked()
    {
        $user = $this->signIn();

        $modelWithLikes = ModelWithLikes::create();

        $modelWithLikes->like($user);

        $this->assertEquals(1, $modelWithLikes->likes->count());

        $modelWithLikes->unlike($user);

        $this->assertEquals(0, $modelWithLikes->likes()->count());
    }

    /**
     * @test
     */
    public function a_model_with_likes_can_be_liked_once()
    {
        $user = $this->signIn();

        $modelWithLikes = ModelWithLikes::create();

        $modelWithLikes->like($user);

        $this->assertEquals(1, $modelWithLikes->likes->count());

        $modelWithLikes->like($user);

        $this->assertEquals(1, $modelWithLikes->likes()->count());
    }

    /**
     * @test
     */
    public function a_model_with_likes_knows_if_has_been_liked()
    {
        $user = $this->signIn();
        
        $modelWithLikes = ModelWithLikes::create();

        $this->assertFalse($modelWithLikes->isLiked($user));

        $modelWithLikes->like($user);

        $this->assertTrue($modelWithLikes->isLiked($user));
    }

    /**
     * @test
     */
    public function a_model_with_likes_knows_how_many_likes_it_has()
    {
        $modelWithLikes = new ModelWithLikes(['id' => 1]);

        $this->assertEquals(0, $modelWithLikes->likesCount());

        Like::factory(2)->for($modelWithLikes, 'likeable')->create();

        $this->assertEquals(2, $modelWithLikes->likesCount());
    }

    /**
     * @test
     */
    function an_event_is_fired_when_a_model_is_liked()
    {
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $likeSender = $this->signIn();

        $modelWithLikes = new ModelWithLikes(['id' => 1]);

        $modelWithLikes->like($likeSender);

        Event::assertDispatched(ModelLikedEvent::class, function ($modelLikedEvent) use ($likeSender) {

            $this->assertInstanceOf(Model::class, $modelLikedEvent->model);
            
            $this->assertTrue($modelLikedEvent->likeSender->is($likeSender));

            $this->assertEventChannelType('public', $modelLikedEvent);

            $this->assertEventChannelName($modelLikedEvent->model->getEventChannelName(), $modelLikedEvent);

            $this->assertDontBroadcastToCurrentUser($modelLikedEvent);

            return true;
        });
    }

    /**
     * @test
     */
    function an_event_is_fired_when_a_model_is_unliked()
    {
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $user = $this->signIn();

        $modelWithLikes = new ModelWithLikes(['id' => 1]);

        Like::factory()
            ->for($modelWithLikes, 'likeable')
            ->for($user)
            ->create();

        $modelWithLikes->unlike($user);

        Event::assertDispatched(ModelUnlikedEvent::class, function ($modelUnlikedEvent) {

            $this->assertInstanceOf(Model::class, $modelUnlikedEvent->model);

            $this->assertEventChannelType('public', $modelUnlikedEvent);

            $this->assertEventChannelName($modelUnlikedEvent->model->getEventChannelName(), $modelUnlikedEvent);

            $this->assertDontBroadcastToCurrentUser($modelUnlikedEvent);

            return true;
        });
    }

    /**
     * @test
     */
    function can_get_the_event_channel_name()
    {
        $modelWithLikes = new ModelWithLikes(['id' => 1]);

        $this->assertEquals('model-with-likes.1.likes', $modelWithLikes->getEventChannelName());
    }

}

class ModelWithLikes extends Model
{
    use HasLikes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Returns the model with likes link
     *
     * @return string
     */
    public function getPath(): string
    {
        return '';
    }
}
