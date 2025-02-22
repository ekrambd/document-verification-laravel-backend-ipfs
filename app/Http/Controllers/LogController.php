<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Document\DocumentInterface;

class LogController extends Controller
{   

	protected $document;

    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    public function saveDocumentLog(Request $request)
    {
    	$saveLog = $this->document->saveDocLog($request);
    	return $saveLog;
    }
}
