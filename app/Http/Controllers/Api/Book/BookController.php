<?php

namespace App\Http\Controllers\Api\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get all books from database with pagination and search by title
        $pagination = $request->pagination ?? 5;

        $search = $request->search ?? '';

        $books = Book::where('name', 'like', '%'.$search.'%')->with('categoryId')->paginate($pagination);

        //response data books
        return response()->json(new BookResource(true, 'Data Book', $books), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request
        $this->validate($request, [
            'category_id'        => 'required|integer',
            'name'               => 'required|string',
            'image'              => 'required|image',
            'stock'              => 'required|integer',
            'publication_year'   => 'required|string',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/images', $image->hashName());

        //create data book

        $book = Book::create([
            'category_id'        => $request->category_id,
            'name'               => $request->name,
            'image'              => $image->hashName(),
            'stock'              => $request->stock,
            'publication_year'   => $request->publication_year,
        ]);

        //response data book
        return response()->json(new BookResource(true, 'Book Created', $book), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //get book by id
        $book = Book::find($id);

        if (!$book) {
            return response()->json(new BookResource(false, 'Book Not Found', null), 404);
        }

        //response data book
        return response()->json(new BookResource(true, 'Data Book', $book->load('categoryId')), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //get book by id
        $book = Book::find($id);

        if (!$book) {
            return response()->json(new BookResource(false, 'Book Not Found', null), 404);
        }

        //cek apakah user ada menginputkan image 
        if ($request->hasFile('image')) {
            //upload image
            $image = $request->file('image');
            $image->storeAs('public/images', $image->hashName());

            //update data book
            $book->update([
                'category_id'        => $request->input('category_id') ?? $book->category_id,
                'name'               => $request->input('name') ?? $book->name,
                'image'              => $image->hashName(),
                'stock'              => $request->input('stock') ?? $book->stock,
                'publication_year'   => $request->input('publication_year') ?? $book->publication_year,
            ]);
        } else {
            //update data book
            $book->update([
                'category_id'        => $request->input('category_id') ?? $book->category_id,
                'name'               => $request->input('name') ?? $book->name,
                'stock'              => $request->input('stock') ?? $book->stock,
                'publication_year'   => $request->input('publication_year') ?? $book->publication_year,
            ]);
        }

        //response data book
        return response()->json(new BookResource(true, 'Book Updated', $book), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //get book by id
        $book = Book::find($id);

        if (!$book) {
            return response()->json(new BookResource(false, 'Book Not Found', null), 404);
        }

        //delete book
        $book->delete();

        //response data book
        return response()->json(new BookResource(true, 'Book Deleted', null), 200);
    }
}
