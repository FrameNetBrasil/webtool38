<?php

use App\Services\AnnotationCorpusService;
use App\Services\AnnotationStaticFrameMode1Service;
use App\Services\AnnotationStaticFrameMode2Service;
use App\Services\FrameService;
use App\Services\QualiaService;
use App\Services\RelationService;
use App\Services\UserService;
use App\Services\SemanticTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Services\Data\FrameService as DataFrameService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::post('/graphql', function(Request $request, \Orkester\GraphQL\Executor $executor) {
    $query = $request->input('query');
    $variables = $request->input('variables', []);
    return $executor->execute($query, $variables);
});

/*
Route::controller(DataFrameService::class)->group(function () {
    Route::post('/frame/listForTree', 'listForTree');
    Route::post('/frame/listRelations/{id}', 'listRelations');
    Route::post('/frame/listFERelations/{idFrame}/{idRelatedFrame}', 'listFERelations');
});
*/
//Route::post('/user/listByFilter', [UserService::class,'listByFilter']);
//Route::post('/frames/listForTree', [FrameService::class,'listForTree']);
//Route::post('/frames/listForSelect', [FrameService::class,'listForSelect']);
Route::post('/qualia/listRelationsForSelect', [QualiaService::class,'listRelationsForSelect']);
Route::post('/semanticType/listForComboGrid', [SemanticTypeService::class,'listForComboGrid']);
//Route::post('/annotation/corpus/listForTree', [AnnotationCorpusService::class,'listForTree']);
Route::post('/annotation/staticFrameMode1/listForTree', [AnnotationStaticFrameMode1Service::class,'listForTree']);
Route::post('/annotation/staticFrameMode2/listForTree', [AnnotationStaticFrameMode2Service::class,'listForTree']);
//Route::post('/frames/{idFrame}/listFEForGrid', [FrameService::class,'listFEForGrid']);
//Route::post('/frames/{idFrame}/listRelations', [FrameService::class,'listRelations']);
//Route::post('/frames/{idFrame}/listFERelations/{idRelatedFrame}', [FrameService::class,'listFERelations']);

/*

Route::controller(FrameService::class)->group(function () {
    Route::post('/frames/listForSelect', 'listForSelect');
    Route::post('/frames/listForTree', 'listForTree');
    Route::post('/frames/entries', 'entries');
    Route::post('/frames/{idFrame}/listRelations', 'listRelations');
    Route::post('/frames/{idFrame}/newFrameRelation', 'newFrameRelation');
    Route::post('/frames/{idFrame}/newFEFrameRelation', 'newFEFrameRelation');
    Route::post('/frames/{idFrame}/listFERelations/{idRelatedFrame}', 'listFERelations');
    Route::post('/frames/{idFrame}/createFE', 'createFE');
});

Route::controller(RelationService::class)->group(function () {
    Route::delete('/relations/{id}', 'delete');
});
*/
