<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Listar préstamos activos
     */
    public function index()
    {
        $this->authorize('viewAny', Loan::class);

        $loans = Loan::with('book')->paginate();

        return response()->json(LoanResource::collection($loans));
    }

    /**
     * Crear préstamo
     */
    public function store(StoreLoanRequest $request)
    {
        $this->authorize('create', Loan::class);

        $book = Book::find($request->input('book_id'));

        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        if (!$book->is_available || $book->available_copies === 0) {
            return response()->json([
                'message' => 'Book is not available'
            ], 422);
        }

        $loan = Loan::create([
            'requester_name' => $request->input('requester_name'),
            'book_id' => $book->id,
        ]);

        $book->update([
            'available_copies' => $book->available_copies - 1,
            'is_available' => $book->available_copies - 1 > 0,
        ]);

        return response()->json([
            'message' => 'Loan created successfully',
            'data' => new LoanResource($loan->load('book'))
        ], 201);
    }

    /**
     * Ver préstamo específico
     */
    public function show(Loan $loan)
    {
        $this->authorize('view', $loan);

        return response()->json(
            new LoanResource($loan->load('book'))
        );
    }

    /**
     * Historial de préstamos
     */
    public function history(Request $request)
    {
        $this->authorize('viewAny', Loan::class);

        $loans = Loan::with('book')
            ->when($request->has('user_id'), function ($query) use ($request) {
                $query->where('requester_name', $request->input('user_id'));
            })
            ->latest()
            ->paginate();

        return response()->json(LoanResource::collection($loans));
    }
}