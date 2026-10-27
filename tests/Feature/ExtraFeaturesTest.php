<?php

use App\Models\Category;
use App\Models\Comment;
use App\Models\Quote;
use App\Models\User;

test('a user can like and unlike a quote', function () {
    $user = User::factory()->create();
    $quote = Quote::factory()->create();

    $this->actingAs($user)->postJson(route('quotes.like', $quote))->assertJson(['active' => true, 'count' => 1]);
    $this->actingAs($user)->postJson(route('quotes.like', $quote))->assertJson(['active' => false, 'count' => 0]);
});

test('guests cannot like quotes', function () {
    $this->postJson(route('quotes.like', Quote::factory()->create()))->assertUnauthorized();
});

test('a user can bookmark a quote and see it on the bookmarks page', function () {
    $user = User::factory()->create();
    $quote = Quote::factory()->create(['content' => 'اقتباس محفوظ']);

    $this->actingAs($user)->postJson(route('quotes.bookmark', $quote))->assertJson(['active' => true]);

    $this->actingAs($user)->get(route('bookmarks'))->assertOk()->assertSee('اقتباس محفوظ');
});

test('a user can follow another user and see their quotes in the feed', function () {
    $user = User::factory()->create();
    $author = User::factory()->create();
    Quote::factory()->for($author)->create(['content' => 'من شخص أتابعه']);
    Quote::factory()->create(['content' => 'من شخص لا أتابعه']);

    $this->actingAs($user)->postJson(route('users.follow', $author))->assertJson(['active' => true, 'count' => 1]);

    $this->actingAs($user)->get(route('feed'))
        ->assertSee('من شخص أتابعه')
        ->assertDontSee('من شخص لا أتابعه');
});

test('a user cannot follow themselves', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson(route('users.follow', $user))->assertStatus(422);
});

test('quotes can be searched by text, author or sharer', function () {
    Quote::factory()->create(['content' => 'الوقت كالسيف', 'author' => null]);
    Quote::factory()->create(['content' => 'نص آخر', 'author' => 'المتنبي']);
    Quote::factory()->create(['content' => 'لا علاقة له']);

    $this->get(route('quotes.index', ['q' => 'السيف']))->assertSee('الوقت كالسيف')->assertDontSee('لا علاقة له');
    $this->get(route('quotes.index', ['q' => 'المتنبي']))->assertSee('نص آخر')->assertDontSee('لا علاقة له');
});

test('quotes can be filtered by category', function () {
    $category = Category::factory()->create();
    Quote::factory()->create(['content' => 'ضمن التصنيف', 'category_id' => $category->id]);
    Quote::factory()->create(['content' => 'خارج التصنيف']);

    $this->get(route('quotes.index', ['category' => $category->slug]))
        ->assertSee('ضمن التصنيف')
        ->assertDontSee('خارج التصنيف');
});

test('quotes can be sorted by popularity', function () {
    $popular = Quote::factory()->create(['content' => 'الأكثر إعجاباً']);
    Quote::factory()->create(['content' => 'الأحدث بلا إعجاب']);
    $popular->likers()->attach(User::factory()->count(3)->create());

    $this->get(route('quotes.index', ['sort' => 'popular']))->assertSeeInOrder(['الأكثر إعجاباً', 'الأحدث بلا إعجاب']);
});

test('a quote can be shared with a category and original author', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($user)->post(route('quotes.store'), [
        'content' => 'على قدر أهل العزم تأتي العزائم',
        'author' => 'المتنبي',
        'category_id' => $category->id,
    ])->assertRedirect();

    expect($user->quotes()->first())
        ->author->toBe('المتنبي')
        ->category_id->toBe($category->id);
});

test('users can comment on a quote and only delete their own comments', function () {
    $user = User::factory()->create();
    $quote = Quote::factory()->create();

    $this->actingAs($user)->post(route('comments.store', $quote), ['body' => 'تعليق رائع'])->assertRedirect();
    $this->get(route('quotes.show', $quote))->assertSee('تعليق رائع');

    $comment = Comment::first();
    $this->actingAs(User::factory()->create())->delete(route('comments.destroy', $comment))->assertForbidden();
    $this->actingAs($user)->delete(route('comments.destroy', $comment))->assertRedirect();

    expect(Comment::count())->toBe(0);
});

test('the profile page shows a user\'s quotes and stats', function () {
    $quote = Quote::factory()->create(['content' => 'اقتباسي الأول']);

    $this->get(route('profile.show', $quote->user))->assertOk()->assertSee($quote->user->name)->assertSee('اقتباسي الأول');
});

test('a user can update their name and bio', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('profile.update'), ['name' => 'اسم جديد', 'bio' => 'نبذة قصيرة'])->assertRedirect();

    expect($user->fresh())->name->toBe('اسم جديد')->bio->toBe('نبذة قصيرة');
});

test('the trending page ranks quotes by recent likes', function () {
    $hot = Quote::factory()->create(['content' => 'رائج هذا الأسبوع']);
    Quote::factory()->create(['content' => 'هادئ']);
    $hot->likers()->attach(User::factory()->count(2)->create());

    $this->get(route('trending'))->assertOk()->assertSeeInOrder(['رائج هذا الأسبوع', 'هادئ']);
});

test('the quote of the day stays the same within a day', function () {
    Quote::factory()->count(5)->create();

    expect(Quote::ofTheDay()->id)->toBe(Quote::ofTheDay()->id);
});
