<?php

return [
    'db' => env('DB_CONNECTION', 'fnbr'),
    'lang' => 1,
    'language' => 'pt',
    'defaultIdLanguage' => 1,
    'defaultPassword' => 'default',
    'pageTitle' => 'Webtool 3.8 - htmx',
    'mainTitle' => 'FrameNet Brasil Webtool 3.8',
    'login' => [
        'handler' => env('APP_AUTH'),
        'AUTH0_CLIENT_ID' => env('AUTH0_CLIENT_ID'),
        'AUTH0_CLIENT_SECRET' => env('AUTH0_CLIENT_SECRET'),
        'AUTH0_COOKIE_SECRET' => env('AUTH0_COOKIE_SECRET'),
        'AUTH0_DOMAIN' => env('AUTH0_DOMAIN'),
        'AUTH0_CALLBACK_URL' => env('AUTH0_CALLBACK_URL'),
        'AUTH0_BASE_URL' => env('AUTH0_BASE_URL'),
    ],
    'actions' => [
        'admin' => ['Admin', '/admin', 'construction', 'ADMIN', 1, [
            'user' => ['Users', '/user', 'edit', 'ADMIN', 1, []],
            'group' => ['Groups', '/group', 'edit', 'ADMIN', 1, []],
            'type' => ['Types', '/type', 'edit', 'ADMIN', 1, []],
            'relation' => ['Relations', '/relationgroup', 'edit', 'ADMIN', 1, []],
            'genre' => ['Genres', '/genre', 'edit', 'ADMIN', 1, []],
            'layer' => ['Layers', '/layer', 'edit', 'ADMIN', 1, []],
            'constraint' => ['Constraints', '/constraint', 'edit', 'ADMIN', 1, []],
        ]],
        'structure' => ['Structure', '/structure', 'account_tree', 'MASTER', 1, [
            'framestructure' => ['Frame', '/frame', 'menu-frame', 'MASTER', 1, []],
            'corpusstructure' => ['Corpus', '/corpus', 'menu-corpus', 'MASTER', 1, []],
//            'cxnstructure' => ['Construction', '/cxn', 'menu-cxn', 'MASTER', 1, []],
            'lexiconstructure' => ['Lexicon', '/lexicon', 'menu-lemma', 'MASTER', 1, []],
//            'qualia' => ['Qualia', '/qualia', 'menu-qualia', 'MASTER', 1, []],
//            'constrainttype' => ['Constraint Type', '/constrainttype', 'menu-constraint', 'MASTER', 1, []],
//            'conceptstructure' => ['Concept', '/concept', 'menu-concept', '', 1, []],
            'semantictypetructure' => ['Semantic Type', '/semantictype', 'menu-semantictype', 'MASTER', 1, []],
        ]],
        'annotation' => ['Annotation', '/annotation', 'edit_note', 'MASTER', 1, [
//            'lexicalAnnotation' => ['Frame Mode', '/lexicalAnnotation', 'lexicalAnnotation', '', 1, []],
//            'cnxAnnotation' => ['Construction Mode', '/constructionalAnnotation', 'cxnAnnotation', '', 1, []],
            'corpusAnnotation' => ['Corpus Mode', '/annotation/corpus', 'corpusAnnotation', 'MASTER', 1, []],
//            'staticFrameMode1' => ['Static Frame Mode 1', '/annotation/staticFrameMode1', 'staticFrameMode1', 'MASTER', 1, []],
//            'staticFrameMode2' => ['Static Frame Mode 2', '/annotation/staticFrameMode2', 'staticFrameMode2', 'MASTER', 1, []],
//            'layers' => ['Manage Layers', '/layer/formManager', 'fa fa-list fa16px', 'JUNIOR', 1, []],
        ]],
        'report' => ['Report', '/report', 'summarize', '', '', [
            'reportframe' => ['Frames', '/report/frames', 'summarize', '', '', []],
//            'lureport' => ['LU', '/lu/report', 'lureport', '', '', []],
//            'cxnreport' => ['Constructions', '/cxn/report', 'cxnreport', '', '', []],
//            'corpusAnnotationReport' => ['Corpus Annotation', '/corpus/report', 'corpusreport', '', 1, []],
        ]],
        'grapher' => ['Grapher', '/grapher', 'hub', '', '', [
            'framegrapher' => ['Frames', '/grapher/frame', 'grapher', '', '', []],
//            'domaingrapher' => ['Domain', '/grapher/domain', 'grapher', '', '', []],
//            'fullgrapher' => ['Frames & CxN', '/grapher', 'fullgrapher', '', '', []],
//            'domaingrapher' => ['Frames by Domain', '/domain/grapher', 'domaingrapher', '', '', []],
//            'ccngrapher' => ['Constructicon', '/ccn/grapher', 'ccngrapher', '', '', []],
        ]],
//        'editor' => ['Editor', 'main/visualeditor', 'edit', 'MASTER', 1, [
//            'frameeditor' => ['Frame Relation', '/visualeditor/frame/main', 'fa fa-list-alt fa16px', 'MASTER', 1, []],
//            'corenesseditor' => ['Coreness', '/visualeditor/frame/coreness', 'fa fa-th-list fa16px', 'MASTER', 1, []],
//            'cxneditor' => ['CxN Relation', '/visualeditor/cxn/main', 'fa fa-list-alt fa16px', 'MASTER', 1, []],
//            'cxnframeeditor' => ['CxN-Frame Relation', '/visualeditor/cxnframe/main', 'fa fa-list-alt fa16px', 'MASTER', 1, []],
//        ]],
//        'utils' => ['Utils', '/utils', 'construction', 'MASTER', 1, [
//            'importLexWf' => ['Import Wf-Lexeme', '/utils/importLexWf', 'utilimport', 'MASTER', 1, []],
//            'wflex' => ['Search Wf-Lexeme', '/admin/wflex', 'utilwflex', '', 1, []],
//            'registerWfLex' => ['Register Wf-Lexeme', '/utils/registerLexWf', 'registerwflex', 'MASTER', 1, []],
//            'registerLemma' => ['Register Lemma', '/utils/registerLemma', 'registerlemma', 'MASTER', 1, []],
//            'importFullText' => ['Import FullText', '/utils/importFullText', 'importfulltext', 'MASTER', 1, []],
//            'exportCxnFS' => ['Export Cxn as FS', '/utils/exportCxnFS', 'exportcxnfs', 'ADMIN', 1, []],
//            'exportCxnJson' => ['Export Cxn', '/utils/exportCxn', 'exportcxnjson', 'ADMIN', 1, []],
//        ]],
    ],
    'user' => ['userPanel', '/admin/user/main', 'fnbrIconForm', '', [
        'language' => ['Language', '/language', 'fa fa-flag-checkered fa16px', '', 1, [
            '2' => ['English', '/changeLanguage/en', 'fnbrFlagEn', '', 1, []],
            '1' => ['Portuguese', '/changeLanguage/pt', 'fnbrFlagPt', '', 1, []],
            '3' => ['Spanish', '/changeLanguage/es', 'fnbrFlagEs', '', 1, []],
        ]],
        'profile' => ['Profile', '/profile', 'account_circle', '', 1, [
            'myprofile' => ['My Profile', '/profile', 'fa fa-credit-card fa16px', '', 1, []],
            'logout' => ['Logout', '/logout', 'fa fa-sign-out fa16px', '', 1, []],
        ]],
    ]],
    'relations' => [
        'rel_inheritance' => [
            'direct' => "Is inherited by",
            'inverse' => "Inherits from",
            'color' => '#FF0000'
        ],
        'rel_subframe' => [
            'direct' => "Has as subframe",
            'inverse' => "Is subframe of",
            'color' => '#0000FF'
        ],
        'rel_perspective_on' => [
            'direct' => "Is perspectivized in",
            'inverse' => "Perspective on",
            'color' => '#fdbeca'
        ],
        'rel_using' => [
            'direct' => "Uses",
            'inverse' => "Is used by",
            'color' => '#006301'
        ],
        'rel_precedes' => [
            'direct' => "Precedes",
            'inverse' => "Is preceded by",
            'color' => '#000000'
        ],
        'rel_causative_of' => [
            'direct' => "Is causative of",
            'inverse' => "Has as causative",
            'color' => '#fdd101'
        ],
        'rel_inchoative_of' => [
            'direct' => "Is inchoative of",
            'inverse' => "Has as inchoative",
            'color' => '#897201'
        ],
        'rel_see_also' => [
            'direct' => "See also",
            'inverse' => "Has as see_also",
            'color' => '#9e1fee'
        ],
        'rel_inheritance_cxn' => [
            'direct' => "Is inherited by",
            'inverse' => "Inherits from",
            'color' => '#FF0000'
        ],
        'rel_daughter_of' => [
            'direct' => "Is daughter of",
            'inverse' => "Has as daughter",
            'color' => '#0000FF'
        ],
        'rel_subtypeof' => [
            'direct' => "Is subtype of",
            'inverse' => "Has as subtype",
            'color' => '#9e1fee'
        ],
        'rel_standsfor' => [
            'direct' => "Stands for",
            'inverse' => "Has as stands_for",
            'color' => '#9e1fee'
        ],
        'rel_coreset' => [
            'direct' => "CoreSet",
            'inverse' => "CoreSet",
            'color' => '#000'
        ],
        'rel_excludes' => [
            'direct' => "Excludes",
            'inverse' => "Excludes",
            'color' => '#000'
        ],
        'rel_requires' => [
            'direct' => "Requires",
            'inverse' => "Requires",
            'color' => '#000'
        ],
    ],
    'fe' => [
        'icon' => [
            'tree' => [
                "cty_core" => "material-icons wt-tree-icon wt-icon-fe-core",
                "cty_core-unexpressed" => "material-icons-outlined wt-tree-icon wt-icon-fe-core-unexpressed",
                "cty_peripheral" => "material-icons-outlined wt-tree-icon wt-icon-fe-peripheral",
                "cty_extra-thematic" => "material-icons-outlined wt-tree-icon wt-icon-fe-extra-thematic",
            ],
            'grid' => [
                "cty_core" => "material-icons wt-icon wt-icon-fe-core",
                "cty_core-unexpressed" => "material-icons-outlined wt-icon wt-icon-fe-core-unexpressed",
                "cty_peripheral" => "material-icons-outlined wt-icon wt-icon-fe-peripheral",
                "cty_extra-thematic" => "material-icons-outlined wt-icon wt-icon-fe-extra-thematic",
            ],
            'grapher' => [
                "cty_core" => "material-icons wt-grapher-icon wt-icon-fe-core",
                "cty_core-unexpressed" => "material-icons-outlined wt-grapher-icon wt-icon-fe-core-unexpressed",
                "cty_peripheral" => "material-icons-outlined wt-grapher-icon wt-icon-fe-peripheral",
                "cty_extra-thematic" => "material-icons-outlined wt-grapher-icon wt-icon-fe-extra-thematic",
            ],
        ],
        'coreness' => [
            "cty_core" => "Core",
            "cty_core-unexpressed" => "Core-Unexpressed",
            "cty_peripheral" => "Non-core",
            "cty_extra-thematic" => "Extra-thematic",
        ]
    ]
];
