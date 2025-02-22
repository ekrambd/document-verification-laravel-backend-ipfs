<?php
 namespace App\Repositories\Document;
 
 interface DocumentInterface
 {
 	public function fetch();
 	public function store($request);
 	public function update($request,$document);
 	public function delete($document);
 	public function saveDocLog($request);
 	public function documentLogs();
 }