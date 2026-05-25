<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::published()
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($doc) => [
                'id'         => $doc->id,
                'title'      => $doc->title,
                'file_url'   => $doc->file_url,
                'created_at' => $doc->created_at->format('d.m.Y'),
            ]);

        return Inertia::render('Documents/Index', [
            'documents' => $documents,
        ]);
    }
}
