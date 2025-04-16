<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $documents = Document::query()
            ->with(['patient'])
            ->when($request->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->patient_id, function ($query, $patientId) {
                return $query->where('patient_id', $patientId);
            })
            ->when($request->type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return DocumentResource::collection($documents);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DocumentRequest $request): DocumentResource
    {
        $data = $request->validated();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('documents');
            
            $data['file_path'] = $path;
            $data['file_size'] = $file->getSize() / 1024; // Convert to KB
            $data['file_type'] = $file->getClientMimeType();
        }
        
        $document = Document::create($data);

        return new DocumentResource($document);
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): DocumentResource
    {
        return new DocumentResource($document->load('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DocumentRequest $request, Document $document): DocumentResource
    {
        $data = $request->validated();
        
        if ($request->hasFile('file')) {
            // Delete old file
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
            
            $file = $request->file('file');
            $path = $file->store('documents');
            
            $data['file_path'] = $path;
            $data['file_size'] = $file->getSize() / 1024; // Convert to KB
            $data['file_type'] = $file->getClientMimeType();
        }
        
        $document->update($data);

        return new DocumentResource($document->load('patient'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): Response
    {
        // Delete file
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
        
        $document->delete();

        return response()->noContent();
    }

    /**
     * Download the document file.
     */
    public function download(Document $document)
    {
        if (!Storage::exists($document->file_path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return Storage::download($document->file_path, $document->title . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));
    }
}