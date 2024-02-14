<?php

use App\Middleware\JsonApiMiddleware;
use App\Repositories\AnnotationSetModel;
use App\Services\AuthService;
use App\Services\AuthUserService;
use App\Services\DashboardService;
use App\Services\GrapherFrameService;
use App\Services\LanguageService;
use App\Repositories\DocumentMMModel;
use App\Repositories\ImageMMModel;
use App\Repositories\ObjectFrameMMModel;
use App\Repositories\ObjectMMModel;
use App\Repositories\ObjectSentenceMMModel;
use App\Repositories\OriginMMModel;
use App\Repositories\SentenceMMModel;
use App\Repositories\StatusMMModel;
use App\Repositories\ColorModel;
use App\Repositories\ConceptModel;
use App\Repositories\ConstraintModel;
use App\Repositories\ConstructionElementModel;
use App\Repositories\ConstructionModel;
use App\Repositories\CorpusModel;
use App\Repositories\DocumentModel;
use App\Repositories\DomainModel;
use App\Repositories\EntityModel;
use App\Repositories\EntryModel;
use App\Repositories\FrameElementModel;
use App\Repositories\FrameModel;
use App\Repositories\GenericLabelModel;
use App\Repositories\GenreModel;
use App\Repositories\GenreTypeModel;
use App\Repositories\GroupModel;
use App\Repositories\LabelFECETargetModel;
use App\Repositories\LabelModel;
use App\Repositories\LanguageModel;
use App\Repositories\LayerGroupModel;
use App\Repositories\LayerModel;
use App\Repositories\LayerTypeModel;
use App\Repositories\LemmaModel;
use App\Repositories\LexemeModel;
use App\Repositories\LUModel;
use App\Repositories\ParagraphModel;
use App\Repositories\POSModel;
use App\Repositories\QualiaModel;
use App\Repositories\RelationGroupModel;
use App\Repositories\RelationModel;
use App\Repositories\SemanticTypeModel;
use App\Repositories\SentenceModel;
use App\Repositories\TimelineModel;
use App\Repositories\TypeModel;
use App\Repositories\UDFeatureModel;
use App\Repositories\UDPOSModel;
use App\Repositories\UDRelationModel;
use App\Repositories\UserModel;
use App\Repositories\WordFormModel;
use App\Services\FrameService;
use App\Services\MultimodalService;
use App\Services\ReportMultimodalService;
use App\Services\UIService;
use Orkester\Resource\BasicResource;

return [
    'api' => [
        'root' => '/api',
        'middleware' => JsonApiMiddleware::class,
        'resources' => [
        ],
        'services' => [
            'ui' => UIService::class,
            'auth' => AuthService::class,
            'authuser' => AuthUserService::class,
            'language' => LanguageService::class,
            'dashboard' => DashboardService::class,
            'frame' => FrameService::class,
            'reportMultimodal' => ReportMultimodalService::class,
            'grapherFrame' => GrapherFrameService::class,
        ]
    ],
    'resources' => [
        'annotationsets' => fn() => new BasicResource(AnnotationSetModel::class),
        'ces' => fn() => new BasicResource(ConstructionElementModel::class),
        'colors' => fn() => new BasicResource(ColorModel::class),
        'concepts' => fn() => new BasicResource(ConceptModel::class),
        'constraints' => fn() => new BasicResource(ConstraintModel::class),
        'constructions' => fn() => new BasicResource(ConstructionModel::class),
        'corpora' => fn() => new BasicResource(CorpusModel::class),
        'documents' => fn() => new BasicResource(DocumentModel::class),
        'domains' => fn() => new BasicResource(DomainModel::class),
        'entities' => fn() => new BasicResource(EntityModel::class),
        'entries' => fn() => new BasicResource(EntryModel::class),
        'fes' => fn() => new BasicResource(FrameElementModel::class),
        'frames' => fn() => new BasicResource(FrameModel::class),
        'genericlabels' => fn() => new BasicResource(GenericLabelModel::class),
        'genres' => fn() => new BasicResource(GenreModel::class),
        'genretypes' => fn() => new BasicResource(GenreTypeModel::class),
        'groups' => fn() => new BasicResource(GroupModel::class),
        'labelfecetarget' => fn() => new BasicResource(LabelFECETargetModel::class),
        'labels' => fn() => new BasicResource(LabelModel::class),
        'languages' => fn() => new BasicResource(LanguageModel::class),
        'layergroups' => fn() => new BasicResource(LayerGroupModel::class),
        'layers' => fn() => new BasicResource(LayerModel::class),
        'layertypes' => fn() => new BasicResource(LayerTypeModel::class),
        'lemmas' => fn() => new BasicResource(LemmaModel::class),
        'lexemes' => fn() => new BasicResource(LexemeModel::class),
        'lus' => fn() => new BasicResource(LUModel::class),
        'paragraphs' => fn() => new BasicResource(ParagraphModel::class),
        'pos' => fn() => new BasicResource(POSModel::class),
        'qualias' => fn() => new BasicResource(QualiaModel::class),
        'relationgroups' => fn() => new BasicResource(RelationGroupModel::class),
        'relations' => fn() => new BasicResource(RelationModel::class),
        'semantictypes' => fn() => new BasicResource(SemanticTypeModel::class),
        'sentences' => fn() => new BasicResource(SentenceModel::class),
        'timelines' => fn() => new BasicResource(TimelineModel::class),
        'types' => fn() => new BasicResource(TypeModel::class),
        'udfeatures' => fn() => new BasicResource(UDFeatureModel::class),
        'udpos' => fn() => new BasicResource(UDPOSModel::class),
        'udrelations' => fn() => new BasicResource(UDRelationModel::class),
        'users' => fn() => new BasicResource(UserModel::class),
        'wordforms' => fn() => new BasicResource(WordformModel::class),
        //
        //charon - singular
        //
        'documentmm' => fn() => new BasicResource(DocumentMMModel::class),
        'imagemm' => fn() => new BasicResource(ImageMMModel::class),
        'objectmm' => fn() => new BasicResource(ObjectMMModel::class),
        'objectframemm' => fn() => new BasicResource(ObjectFrameMMModel::class),
        'objectsentencemm' => fn() => new BasicResource(ObjectSentenceMMModel::class),
        'originmm' => fn() => new BasicResource(OriginMMModel::class),
        'sentencemm' => fn() => new BasicResource(SentenceMMModel::class),
        'statusmm' => fn() => new BasicResource(StatusMMModel::class),
    ],
    'services' => [
        'auth0Login' => [AuthUserService::class, 'auth0Login'],
        'frameSearch' => [FrameService::class, 'search'],
        'mmReportByTimeInterval' => [MultimodalService::class, 'mmReportByTimeInterval'],
        'mmReportSentences' => [MultimodalService::class, 'mmReportSentences'],
        'mmReportBySentence' => [MultimodalService::class, 'mmReportBySentence'],
        'mmReportByPointInTime' => [MultimodalService::class, 'mmReportByPointInTime'],
        'mmReportByImageText' => [MultimodalService::class, 'mmReportByImageText'],
        'mmReportSankey' => [MultimodalService::class, 'mmReportSankey'],
        'mmObjectMMListForDynamicAnnotation' => [MultimodalService::class, 'listObjectMMForDynamicAnnotation'],
    ],
];

