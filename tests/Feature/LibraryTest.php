<?php

use App\Models\Book;
use App\Models\Quote;
use App\Models\User;

test('the library lists every book with its quote count', function () {
    $book = Book::factory()->create(['title' => 'ديوان المتنبي']);
    Quote::factory()->count(2)->create(['book_id' => $book->id]);

    $this->get(route('books.index'))
        ->assertOk()
        ->assertSee('ديوان المتنبي')
        ->assertSee('2 اقتباس');
});

test('a book page shows only the quotes shared from that book', function () {
    $book = Book::factory()->create();
    Quote::factory()->create(['content' => 'من هذا الكتاب', 'book_id' => $book->id]);
    Quote::factory()->create(['content' => 'من كتاب آخر', 'book_id' => Book::factory()->create()->id]);

    $this->get(route('books.show', $book))
        ->assertOk()
        ->assertSee($book->title)
        ->assertSee('من هذا الكتاب')
        ->assertDontSee('من كتاب آخر');
});

test('a quote can be shared from a book', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    $this->actingAs($user)->post(route('quotes.store'), [
        'content' => 'اقتباس من الكتاب',
        'book_id' => $book->id,
    ])->assertRedirect();

    expect($user->quotes()->first()->book_id)->toBe($book->id);
});

test('a quote cannot point to a book that is not in the library', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('quotes.store'), ['content' => 'اقتباس', 'book_id' => 999])
        ->assertSessionHasErrors('book_id');

    expect(Quote::count())->toBe(0);
});

test('quote cards link to their book', function () {
    $book = Book::factory()->create(['title' => 'مقدمة ابن خلدون']);
    Quote::factory()->create(['book_id' => $book->id]);

    $this->get(route('quotes.index'))->assertSee('من كتاب «مقدمة ابن خلدون»');
});

test('quotes can be searched by book title', function () {
    $book = Book::factory()->create(['title' => 'الشوقيات']);
    Quote::factory()->create(['content' => 'بيت من الديوان', 'book_id' => $book->id]);
    Quote::factory()->create(['content' => 'لا علاقة له']);

    $this->get(route('quotes.index', ['q' => 'الشوقيات']))->assertSee('بيت من الديوان')->assertDontSee('لا علاقة له');
});
