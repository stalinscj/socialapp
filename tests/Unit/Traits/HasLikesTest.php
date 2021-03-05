<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\Like;
use App\Traits\HasLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasLikesTest extends TestCase
{
    use RefreshDatabase;

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

        $modelWithLikes = new ModelWithLikes(['id' => 1]);

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

        $modelWithLikes = new ModelWithLikes(['id' => 1]);

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
        
        $modelWithLikes = new ModelWithLikes(['id' => 1]);

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
}
