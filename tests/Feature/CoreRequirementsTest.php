<?php

use App\Models\Quote;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('FR-01 guests see every quote with text, sharer name and date', function () {
    $quote = Quote::factory()->create(['content' => 'كلمات يبقى صداها']);

    $this->get(route('quotes.index'))
        ->assertOk()
        ->assertSee('كلمات يبقى صداها')
        ->assertSee($quote->user->name)
        ->assertSee($quote->created_at->translatedFormat('j F Y'))
        ->assertDontSee('data-edit-toggle', false);
});

test('the landing page loads for guests', function () {
    Quote::factory()->count(3)->create();

    $this->get(route('home'))->assertOk()->assertSee('كلمات يبقى صداها');
});

test('FR-02 a guest can sign up and the password is stored hashed', function () {
    $this->post('/signup', [
        'name' => 'سارة العتيبي',
        'email' => 'sara@example.com',
        'password' => 'secret123',
    ])->assertRedirect(route('quotes.index'));

    $user = User::where('email', 'sara@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->password)->not->toBe('secret123')
        ->and(Hash::check('secret123', $user->password))->toBeTrue();
    $this->assertAuthenticatedAs($user);
});

test('FR-02 sign up rejects invalid names, emails, short passwords and duplicates', function (array $input, string $field) {
    User::factory()->create(['email' => 'taken@example.com']);

    $this->post('/signup', array_merge([
        'name' => 'نورة',
        'email' => 'new@example.com',
        'password' => 'secret123',
    ], $input))->assertSessionHasErrors($field);

    $this->assertGuest();
})->with([
    'name too short' => [['name' => 'ن'], 'name'],
    'name too long' => [['name' => str_repeat('ا', 51)], 'name'],
    'name with numbers' => [['name' => 'نورة123'], 'name'],
    'invalid email' => [['email' => 'not-an-email'], 'email'],
    'duplicate email' => [['email' => 'taken@example.com'], 'email'],
    'short password' => [['password' => 'short'], 'password'],
]);

test('FR-03 a registered user can log in with email and password', function () {
    $user = User::factory()->create(['password' => 'secret123']);

    $this->post('/login', ['email' => $user->email, 'password' => 'secret123'])
        ->assertRedirect(route('quotes.index'));

    $this->assertAuthenticatedAs($user);
});

test('FR-03 wrong credentials show an error and keep the user a guest', function () {
    $user = User::factory()->create(['password' => 'secret123']);

    $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong-pass'])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('FR-04 a logged-in user can log out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

test('FR-05 a logged-in user can share a quote of 3 to 155 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('quotes.store'), ['content' => 'اقتباس جديد'])->assertRedirect();

    expect($user->quotes()->first()->content)->toBe('اقتباس جديد');
});

test('FR-05 quotes outside 3 to 155 characters are rejected', function (string $content) {
    $this->actingAs(User::factory()->create())
        ->post(route('quotes.store'), ['content' => $content])
        ->assertSessionHasErrors('content');

    expect(Quote::count())->toBe(0);
})->with([
    'too short' => ['اب'],
    'too long' => [str_repeat('ا', 156)],
]);

test('FR-05 guests cannot share quotes', function () {
    $this->post(route('quotes.store'), ['content' => 'اقتباس'])->assertRedirect(route('login'));

    expect(Quote::count())->toBe(0);
});

test('FR-06 users can edit their own quotes', function () {
    $quote = Quote::factory()->create();

    $this->actingAs($quote->user)
        ->put(route('quotes.update', $quote), ['content' => 'نص معدّل'])
        ->assertRedirect();

    expect($quote->fresh()->content)->toBe('نص معدّل');
});

test('FR-06 / NFR-03 users cannot edit other people\'s quotes', function () {
    $quote = Quote::factory()->create(['content' => 'الأصل']);

    $this->actingAs(User::factory()->create())
        ->put(route('quotes.update', $quote), ['content' => 'محاولة تعديل'])
        ->assertForbidden();

    expect($quote->fresh()->content)->toBe('الأصل');
});

test('FR-07 users can delete their own quotes immediately', function () {
    $quote = Quote::factory()->create();

    $this->actingAs($quote->user)->delete(route('quotes.destroy', $quote))->assertRedirect();

    expect(Quote::find($quote->id))->toBeNull();
});

test('FR-07 / NFR-03 users cannot delete other people\'s quotes', function () {
    $quote = Quote::factory()->create();

    $this->actingAs(User::factory()->create())->delete(route('quotes.destroy', $quote))->assertForbidden();

    expect(Quote::find($quote->id))->not->toBeNull();
});

test('NFR-05 every form field shows a short hint', function () {
    $this->get(route('signup'))
        ->assertSee('من 2 إلى 50 حرفاً')
        ->assertSee('8 أحرف على الأقل');
});
