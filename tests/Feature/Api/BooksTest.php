<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BooksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_get_all_books()
    {
        Book::factory()->count(5)->create();

        $this->getJson(route('books.index'))
            ->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertStatus(200)
            ->assertJson([
            'title' => $book->title,
        ]);
    }

    /** @test */
    public function can_create_a_book()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'My Book'
        ])->assertStatus(201)
            ->assertJson([
                'title' => 'My Book'
            ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My Book'
        ]);
    }

    /** @test */
    public function can_update_a_book()
    {
        $book = Book::factory()->create();

        $this->putJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->putJson(route('books.update', $book), [
            'title' => 'My Book'
        ])->assertStatus(200)->assertJson([
            'title' => 'My Book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'My Book'
        ]);
    }

    /** @test */
    public function can_delete_a_book()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseMissing('books', [
            'id' => $book->id
        ]);
    }
}
