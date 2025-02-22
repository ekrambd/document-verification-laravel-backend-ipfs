<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Repositories\Document\DocumentInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $document;

    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    public function index(Request $request)
    {
        $query = $this->document->fetch();
        if($request->has('term'))
        {
            $query->where('documents.title', 'LIKE', "%$request->term%")->orWhere('documents.ipfs_hash','LIKE', "%$request->term%");
        }
        $documents = $query->latest()->paginate(10);
        return $documents;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {   
        $store = $this->document->store($request);
        return $store;
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        return response()->json(['status'=>true, 'document'=>$document]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}
