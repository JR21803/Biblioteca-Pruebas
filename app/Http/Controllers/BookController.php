<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Book::class);

        $books = Book::when($request->has('title'), function ($query) use ($request) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        })
        ->when($request->has('isbn'), function ($query) use ($request) {
            $query->where('ISBN', 'like', '%' . $request->input('isbn') . '%');
        })
        ->when($request->has('is_available'), function ($query) use ($request) {
            $query->where('is_available', $request->boolean('is_available'));
        })
        ->paginate();

        return response()->json(BookResource::collection($books));
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);

        return response()->json(BookResource::make($book));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);
        
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'required|string',
            'ISBN' => 'required|string',
            'total_copies' => 'required|integer',
            'available_copies' => 'required|integer',
            'is_available' => 'required|boolean',    
        ]);
            
            $book = Book::create($validated);
            
            return response()->json($book, 200);
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'ISBN' => 'nullable|string|max:50',
            'is_available' => 'boolean'
        ]);

        $book->update($data);

        return response()->json([
            'message' => 'Libro actualizado correctamente',
            'data' => BookResource::make($book)
        ]);
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete();

        return response()->json([
            'message' => 'El libro ha sido eliminado correctamente'
        ]);
    }
}