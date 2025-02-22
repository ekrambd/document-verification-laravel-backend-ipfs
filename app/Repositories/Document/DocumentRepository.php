<?php
 namespace App\Repositories\Document;
 use App\Models\Document;
 use App\Models\Documentlog;
 use DB;
 use Validator;

 class DocumentRepository implements DocumentInterface
 {
 	public function fetch()
 	{
 		try
 		{
 			$documents = Document::query();
 			return $documents;
 		}catch(Exception $e){
 			return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
 		}
 	}

 	public function store($request)
 	{
 		DB::beginTransaction();
 		try
 		{   
 			$response = uploadDocument($request);
 			if($response->successful())
 			{   
 				$ipfsHash = $response->json()['IpfsHash'];

 				$count = $this->fetch()->where('ipfs_hash',$ipfsHash)->count();

	        	if($count > 0)
	        	{   
	        		DB::commit();
	        		return response()->json(['status'=>false, 'nft_id'=>0, 'message'=>'Already the document has been taken'],500);
	        	}

 				$document = Document::create([
 				  'user_id' => user()->id,
 				  'title' => $request->title,
 				  'ipfs_hash' => $ipfsHash,
 				  'description' => $request->description,
 			    ]);

 				DB::commit();

 				return response()->json(['status'=>true, 'document_id'=>intval($document->id), 'ipfs_hash'=>$ipfsHash, 'message'=>'Successfully a document has been added']);

 			}
 			
 		}catch(Exception $e){
 			DB::rollback();
 			return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
 		}
 	}

 	public function update($request,$document)
 	{
 		//
 	}

 	public function delete($document)
 	{
 		//
 	}

 	public function saveDocLog($request)
 	{
 		try
 		{   
 			$validator = Validator::make($request->all(), [
                'document_id' => 'required|integer',
                'ipfs_hash' => 'required|string',
                'transaction_hash' => 'required|string|unique:documentlogs',
                'action' => 'required|in:Add,Edit,Delete'
            ]);

            if($validator->fails()){
                return response()->json(['status'=>false, 'message'=>'The given data was invalid', 'data'=>$validator->errors()],422);  
            }

 			$log = new Documentlog();
 			$log->user_id = user()->id;
 			$log->document_id = $request->document_id;
 			$log->ipfs_hash = $request->ipfs_hash;
 			$log->action = $request->action;
 			$log->transaction_hash = $request->transaction_hash;
 			$log->date = date('Y-m-d');
 			$log->time = date('h:i:s A');
 			$log->save();
 			return response()->json(['status'=>true, 'message'=>'Successfully save']);
 		}catch(Exception $e){
 			return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
 		}
 	}

 	public function documentLogs()
 	{
 		try
 		{
 			$logs = Documentlog::query();
 			return $logs;
 		}catch(Exception $e){
 			return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
 		}
 	}
 }