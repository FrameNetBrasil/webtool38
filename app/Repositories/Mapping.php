<?php

namespace App\Repositories;

use Orkester\Persistence\Enum\Join;
use Orkester\Persistence\Enum\Key;
use Orkester\Persistence\Enum\Type;
use Orkester\Persistence\Map\ClassMap;

class Mapping extends \Orkester\Persistence\Mapping
{
    public static function Access(ClassMap $classMap): void
    {
        $classMap->table('access')
        ->attribute('idAccess', key: Key::PRIMARY)
        ->attribute('rights', type: Type::INTEGER)
        ->attribute('idGroup', type: Type::INTEGER)
        ->attribute('idTransaction' ,type: Type::INTEGER)
        ->associationOne('group', model:  'Group', key: 'idGroup')
        ->associationOne('transaction', model:  'Transaction', key: 'idTransaction');
    }

    public static function AnnotationMM(ClassMap $classMap): void
    {

        $classMap->table('annotationmm')
        ->attribute('idAnnotationMM', key: Key::PRIMARY)
        ->attribute('idObjectSentenceMM', key: Key::FOREIGN)
        ->attribute('idFrameElement', key: Key::FOREIGN)
        ->attribute('idFrame', key: Key::FOREIGN)
        ->associationOne('objectSentenceMM', model:  'ObjectSentenceMM', key: 'idObjectSentenceMM')
        ->associationOne('frameElement', model:  'FrameElement', key: 'idFrameElement')
        ->associationOne('frame', model:  'Frame', key: 'idFrame');
    }

    public static function AnnotationSet(ClassMap $classMap): void
    {

        $classMap->table('annotationset')
        ->attribute('idAnnotationSet', key: Key::PRIMARY)
        ->attribute('idSentence', key: Key::FOREIGN)
        ->attribute('idAnnotationStatus', key: Key::FOREIGN)
        ->attribute('idEntityRelated', key: Key::FOREIGN)
//        ->attribute('idEntityLU', field: 'idEntityRelated', type: Type::INTEGER)
//        ->attribute('idEntityCxn', field: 'idEntityRelated', type: Type::INTEGER)
        ->associationMany('lu', model:  'LU', keys: 'idEntityRelated:idEntity')
        ->associationMany('cxn', model:  'Construction', keys: 'idEntityRelated:idEntity')
        ->associationOne('sentence', model:  'Sentence', key: 'idSentence')
        ->associationOne('annotationStatus', model:  'TypeInstance', key: 'idAnnotationStatus:idTypeInstance')
        ->associationMany('layers', model:  'Layer', keys: 'idAnnotationSet');
    }

    public static function ASComment(ClassMap $classMap): void
    {

        $classMap->table('ascomments')
        ->attribute('idASComments', key: Key::PRIMARY)
        ->attribute('extraThematicFE')
        ->attribute('extraThematicFEOther')
        ->attribute('comment')
        ->attribute('construction')
        ->attribute('idAnnotationSet', key: Key::FOREIGN)
        ->associationOne('annotationSet', model:  'AnnotationSet', key: 'idAnnotationSet');
    }

    public static function Color(ClassMap $classMap): void
    {
        $classMap->table('color')
        ->attribute('idColor', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('rgbFg')
        ->attribute('rgbBg');
    }

    public static function Concept(ClassMap $classMap): void
    {

        $classMap->table('concept')
        ->attribute('idConcept', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('keyword')
        ->attribute('aka')
        ->attribute('idTypeInstance', key: Key::FOREIGN)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('typeInstance', model:  'TypeInstance')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('relations', model:  'Relation', keys: 'idEntity:idEntity1')
        ->associationMany('inverseRelations', model:  'Relation', keys: 'idEntity:idEntity2');
    }

    public static function ConstraintInstance(ClassMap $classMap): void
    {
        $classMap->table('entityrelation')
        ->attribute('idConstraintInstance', field:'idEntityRelation', key: Key::PRIMARY)
        ->attribute('idConstraintType', field:'idRelationType', type: Type::INTEGER)
        ->attribute('idConstraint', field:'idEntity1', type: Type::INTEGER)
        ->attribute('idConstrained', field:'idEntity2',type: Type::INTEGER)
        ->attribute('idConstrainedBy', field:'idEntity3',type: Type::INTEGER)
        ->associationOne('constraintType', model:  'RelationType', key: 'idConstraintType:idRelationType')
        ->associationOne('entityConstraint', model:  'Entity', key: 'idConstraint:idEntity')
        ->associationOne('entityConstrained', model:  'Entity', key: 'idConstrained:idEntity')
        ->associationOne('entityConstrainedBy', model:  'Entity', key: 'idConstrainedBy:idEntity')
        ->associationOne('constrainedFE', model:  'FrameElement', key: 'idConstrained:idEntity');
    }

    public static function ConstraintType(ClassMap $classMap): void
    {
        $classMap->table('relationtype')
        ->attribute('idConstraintType', field:'idRelationType', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('prefix')
        ->attribute('idTypeInstance', key: Key::FOREIGN)
        ->attribute('idRelationGroup', key: Key::FOREIGN);
    }

    public static function ConstructionElement(ClassMap $classMap): void
    {
        $classMap->table('constructionelement')
        ->attribute('idConstructionElement', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('color', model:  'Color', key: 'idColor')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('relations', model:  'Relation', keys: 'idEntity:idEntity1');
    }

    public static function Construction(ClassMap $classMap): void
    {

        $classMap->table('construction')
        ->attribute('idConstruction', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('active', type: Type::INTEGER)
        ->attribute('abstract', type: Type::INTEGER)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguageCxn', field: 'idLanguage')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('relations', model:  'Relation', keys: 'idEntity:idEntity1')
        ->associationMany('inverseRelations', model:  'Relation', keys: 'idEntity:idEntity2')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('language', model:  'Language');

    }

    public static function Corpus(ClassMap $classMap): void
    {

        $classMap->table('corpus')
        ->attribute('idCorpus', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('active')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('documents', model:  'Document', keys: 'idCorpus');
    }

    public static function DocumentMM(ClassMap $classMap): void
    {

        $classMap->table('documentmm')
        ->attribute('idDocumentMM', key: Key::PRIMARY)
        ->attribute('name', reference: 'document.name')
        ->attribute('title')
        ->attribute('originalFile')
        ->attribute('sha1Name')
        ->attribute('audioPath')
        ->attribute('videoPath')
        ->attribute('videoWidth', type: Type::INTEGER)
        ->attribute('videoHeight', type: Type::INTEGER)
        ->attribute('alignPath')
        ->attribute('flickr30k')
        ->attribute('enabled')
        ->attribute('url')
        ->attribute('idDocument', type: Type::INTEGER, key: Key::FOREIGN)
        ->attribute('idLanguage', type: Type::INTEGER, key: Key::FOREIGN)
        ->associationOne('document', model:  'Document', key: 'idDocument:idDocument')
        ->associationMany('sentenceMM', model:  'SentenceMM', keys: 'idDocumentMM:idDocumentMM');
    }

    public static function Document(ClassMap $classMap): void
    {
        $classMap->table('document')
        ->attribute('idDocument', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('active')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idGenre', key: Key::FOREIGN)
        ->attribute('author')
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationOne('corpus', model:  'Corpus', key: 'idCorpus')
        ->associationMany('paragraphs', model:  'Paragraph', keys: 'idDocument')
        ->associationMany('sentences', model:  'Sentence', associativeTable: 'document_sentence')
        ->associationMany('documentMM', model:  'DocumentMM', keys: 'idDocument')
        ->associationMany('genre', model:  'Genre', keys: 'idGenre');
    }

    public static function Domain(ClassMap $classMap): void
    {
        $classMap->table('domain')
        ->attribute('idDomain', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity', key: 'idEntity')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');
    }

    public static function DynamicBoxMM(ClassMap $classMap): void
    {

        $classMap->table('dynamicbboxmm')
        ->attribute('idDynamicBBoxMM', key: Key::PRIMARY)
        ->attribute('frameNumber', type: Type::INTEGER)
        ->attribute('frameTime', type: Type::FLOAT)
        ->attribute('x', type: Type::INTEGER)
        ->attribute('y', type: Type::INTEGER)
        ->attribute('width', type: Type::INTEGER)
        ->attribute('height', type: Type::INTEGER)
        ->attribute('blocked', type: Type::INTEGER)
        ->attribute('idDynamicObjectMM', type: Type::INTEGER, key: Key::FOREIGN);
    }

    public static function DynamicObjectMM(ClassMap $classMap): void
    {

        $classMap->table('dynamicobjectmm')
        ->attribute('idDynamicObjectMM', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('startFrame', type: Type::INTEGER)
        ->attribute('endFrame', type: Type::INTEGER)
        ->attribute('startTime', type: Type::FLOAT)
        ->attribute('endTime', type: Type::FLOAT)
        ->attribute('status', type: Type::INTEGER)
        ->attribute('origin', type: Type::INTEGER)
        ->attribute('idDocument', type: Type::INTEGER)
        ->attribute('idFrameElement', type: Type::INTEGER)
        ->attribute('idLU', type: Type::INTEGER)
        ->associationOne('document', model:  'Document', key: 'idDocument')
        ->associationOne('frameElement', model:  'FrameElement', key: 'idFrameElement')
        ->associationOne('lu', model:  'LU', key: 'idLU');
    }

    public static function DynamicSentenceMM(ClassMap $classMap): void
    {

        $classMap->table('dynamicsentencemm')
        ->attribute('idDynamicSentenceMM', key: Key::PRIMARY)
        ->attribute('startTime', type: Type::FLOAT)
        ->attribute('endTime', type: Type::FLOAT)
        ->attribute('origin', type: Type::INTEGER)
        ->attribute('idSentence', type: Type::INTEGER, key: Key::FOREIGN)
        ->attribute('idOriginMM', type: Type::INTEGER, key: Key::FOREIGN)
        ->associationOne('sentence', model:  'Sentence', key: 'idSentence:idSentence')
        ->associationOne('originMM', model:  'OriginMM', key: 'idOriginMM');
    }

    public static function Entity(ClassMap $classMap): void
    {
        $classMap->table('entity')
            ->attribute('idEntity', key: Key::PRIMARY)
            ->attribute('type')
            ->attribute('alias');
    }

    public static function Entry(ClassMap $classMap): void
    {

        $classMap->table('entry')
            ->attribute('idEntry', key: Key::PRIMARY)
            ->attribute('entry')
            ->attribute('name')
            ->attribute('description')
            ->attribute('idEntity', key: Key::FOREIGN)
            ->associationOne('language', model:  'Language')
            ->associationOne('entity', model:  'Entity', key: 'idEntity');
    }

    public static function FormEntry(ClassMap $classMap): void
    {
        $classMap->table('formentry')
        ->attribute('idFormEntry', key: Key::PRIMARY)
        ->attribute('idForm', key: Key::FOREIGN)
        ->attribute('idFormPart', key: Key::FOREIGN)
        ->attribute('lexemeOrder', type: Type::INTEGER)
        ->attribute('breakBefore', type: Type::INTEGER)
        ->attribute('headWord', type: Type::INTEGER)
        ->attribute('inflected', type: Type::INTEGER)
        ->associationOne('form', model:  'Form', key: "idForm")
        ->associationOne('formPart', model:  'Form', key: "idFormPart");
    }

    public static function Form(ClassMap $classMap): void
    {
        $classMap->table('form')
        ->attribute('idForm', key: Key::PRIMARY)
        ->attribute('form')
        ->attribute('md5')
        ->attribute('idPOS', key: Key::FOREIGN)
        ->attribute('idLanguage', key: Key::FOREIGN)
        ->associationOne('pos', model:  'POS', key: 'idPOS')
        ->associationOne('language', model:  'Language', key: 'idLanguage')
        ->associationOne('entry', model:  'FormEntry', key: 'idForm')
        ->associationMany('udFeatures', model:  'UDFeature', associativeTable: 'form_udfeature');
    }

    public static function FormRelation(ClassMap $classMap): void
    {

        $classMap->table('formrelation')
        ->attribute('idFormRelation', key: Key::PRIMARY)
        ->attribute('idRelationType', key: Key::FOREIGN)
        ->attribute('idForm1', key: Key::FOREIGN)
        ->attribute('idForm2', key: Key::FOREIGN)
        ->associationOne('relationType', model:  'RelationType', key: 'idRelationType')
        ->associationOne('form1', model:  'Form', key: 'idForm1:idForm')
        ->associationOne('form2', model:  'Form', key: 'idForm2:idForm');
    }

    public static function FrameElement(ClassMap $classMap): void
    {
        $classMap->table('frameelement')
        ->attribute('idFrameElement', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('coreType')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idFrame', key: Key::FOREIGN)
        ->attribute('idColor', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('frame', model:  'Frame', key: 'idFrame')
        ->associationOne('color', model:  'Color', key: 'idColor')
        ->associationMany('typeInstance', model:  'TypeInstance', keys: 'coreType:entry')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('relations', model:  'Relation', keys: 'idEntity:idEntity1');
    }

    public static function Frame(ClassMap $classMap): void
    {

        $classMap->table('frame')
        ->attribute('idFrame', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationMany('lus', model:  'LU', keys: 'idFrame')
        ->associationMany('fes', model:  'FrameElement', keys: 'idFrame')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('relations', model:  'Relation', keys: 'idEntity:idEntity1')
        ->associationMany('inverseRelations', model:  'Relation', keys: 'idEntity:idEntity2');
    }

    public static function GenericLabel(ClassMap $classMap): void
    {
        $classMap->table('genericlabel')
        ->attribute('idGenericLabel', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('definition')
        ->attribute('example')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idColor', key: Key::FOREIGN)
        ->attribute('idLanguage', key: Key::FOREIGN)
        ->associationOne('entity', model:  'Entity')
        ->associationOne('color', model:  'Color', key: 'idColor')
        ->associationOne('language', model:  'Language', key: 'idLanguage');
    }

    public static function Genre(ClassMap $classMap): void
    {

        $classMap->table('genre')
        ->attribute('idGenre', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->attribute('idGenreType', key: Key::FOREIGN)
        ->associationOne('genreType', model:  'GenreType')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');
    }

    public static function GenreType(ClassMap $classMap): void
    {

        $classMap->table('genretype')
        ->attribute('idGenreType', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');
    }

    public static function Group(ClassMap $classMap): void
    {
        $classMap->table('group')
        ->attribute('idGroup', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('description')
        ->associationMany('users', model:  'User', associativeTable: 'user_group')
        ->associationMany('access', model:  'Access', keys: 'idGroup');
    }

    public static function ImageMM(ClassMap $classMap): void
    {

        $classMap->table('imagemm')
        ->attribute('idImageMM', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('width', type: Type::INTEGER)
        ->attribute('height', type: Type::INTEGER)
        ->attribute('depth', type: Type::FLOAT)
        ->attribute('imagePath');
    }

    public static function LabelFECETarget(ClassMap $classMap): void
    {

        $classMap->table('view_labelfecetarget')
        ->attribute('idAnnotationSet', key: Key::PRIMARY)
        ->attribute('idSentence', type:Type::INTEGER)
        ->attribute('layerTypeEntry')
        ->attribute('idFrameElement', type:Type::INTEGER)
        ->attribute('idConstructionElement', type:Type::INTEGER)
        ->attribute('idGenericLabel', type:Type::INTEGER)
        ->attribute('startChar')
        ->attribute('endChar')
        ->attribute('rgbFg')
        ->attribute('rgbBg')
        ->attribute('idLanguage', type:Type::INTEGER)
        ->attribute('instantiationType')
        ->associationOne('sentence', model:  'Sentence', key: 'idSentence');
    }

    public static function Label(ClassMap $classMap): void
    {

        $classMap->table('label')
        ->attribute('idLabel', key: Key::PRIMARY)
        ->attribute('startChar')
        ->attribute('endChar')
        ->attribute('multi')
        ->attribute('idLabelType', key: Key::FOREIGN)
        ->attribute('idLayer', key: Key::FOREIGN)
        ->attribute('idInstantiationType', key: Key::FOREIGN)
        ->associationOne('genericLabel', model:  'GenericLabel', key: 'idLabelType:idEntity')
        ->associationOne('frameElement', model:  'FrameElement', key: 'idLabelType:idEntity')
        ->associationOne('constructionElement', model:  'ConstructionElement', key: 'idLabelType:idEntity')
        ->associationOne('layer', model:  'Layer', key: 'idLayer')
        ->associationOne('instantiationType', model:  'TypeInstance', key: 'idInstantiationType:idTypeInstance');
    }

    public static function Language(ClassMap $classMap): void
    {
        $classMap->table('language')
            ->attribute('idLanguage', key: Key::PRIMARY)
            ->attribute('language')
            ->attribute('description');
    }

    public static function LayerGroup(ClassMap $classMap): void
    {

        $classMap->table('layergroup')
        ->attribute('idLayerGroup', key: Key::PRIMARY)
        ->attribute('name')
        ->associationMany('layerType', model:  'LayerType', keys: 'idLayerGroup');
    }

    public static function Layer(ClassMap $classMap): void
    {

        $classMap->table('layer')
        ->attribute('idLayer', key: Key::PRIMARY)
        ->attribute('rank')
        ->attribute('idLayerType', key: Key::FOREIGN)
        ->attribute('idAnnotationSet', key: Key::FOREIGN)
        ->associationOne('layerType', model:  'LayerType', key: 'idLayerType')
        ->associationOne('annotationSet', model:  'AnnotationSet', key: 'idAnnotationSet')
        ->associationMany('labels', model:  'Label', keys: 'idLayer');
    }

    public static function LayerType(ClassMap $classMap): void
    {

        $classMap->table('layertype')
        ->attribute('idLayerType', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('allowsApositional')
        ->attribute('isAnnotation')
        ->attribute('layerOrder')
        ->attribute('idLayerGroup', key: Key::FOREIGN)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('layerGroup', model:  'LayerGroup', key: 'idLayerGroup')
        ->associationMany('relations', model:  'Relation', keys: 'idEntity:idEntity1');
    }

    public static function Lemma(ClassMap $classMap): void
    {
        $classMap->table('lemma')
        ->attribute('idLemma', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('version')
        ->attribute('idLanguage', key: Key::FOREIGN)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->associationOne('entity', model:  'Entity')
        ->associationOne('pos', model:  'POS')
        ->associationOne('udpos', model:  'UDPOS')
        ->associationOne('language', model:  'Language')
        ->associationMany('lexemes', model:  'Lexeme', associativeTable: 'lexemeentry')
        ->associationMany('lexemeentries', model:  'LexemeEntry', keys: 'idLemma')
        ->associationMany('lus', model:  'LU', keys: 'idLemma');
    }

    public static function LexemeEntry(ClassMap $classMap): void
    {
        $classMap->table('lexemeentry')
        ->attribute('idLexemeEntry', key: Key::PRIMARY)
        ->attribute('lexemeOrder', type: Type::INTEGER)
        ->attribute('breakBefore', type: Type::INTEGER)
        ->attribute('headWord', type: Type::INTEGER)
        ->attribute('idLemma', key: Key::FOREIGN)
        ->attribute('idLexeme', key: Key::FOREIGN)
        ->associationOne('lemma', model:  'Lemma', key: "idLemma")
        ->associationOne('lexeme', model:  'Lexeme', key: "idLexeme")
        ->associationOne('wordForm', model:  'WordForm', key: "idWordForm");
    }

    public static function Lexeme(ClassMap $classMap): void
    {
        $classMap->table('lexeme')
        ->attribute('idLexeme', key: Key::PRIMARY)
        ->attribute('name')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('pos', model:  'POS', key: 'idPOS')
        ->associationOne('udpos', model:  'UDPOS')
        ->associationOne('language', model:  'Language')
        ->associationMany('lemmas', model:  'Lemma', associativeTable: 'lexemeentry')
        ->associationMany('lexemeEntries', model:  'LexemeEntry', keys: 'idLexeme')
        ->associationMany('wordforms', model:  'WordForm', keys: 'idLexeme');
    }

    public static function LU(ClassMap $classMap): void
    {

        $classMap->table('lu')
            ->attribute('idLU', key: Key::PRIMARY)
            ->attribute('name')
            ->attribute('senseDescription')
            ->attribute('active', type: Type::INTEGER)
            ->attribute('importNum', type: Type::INTEGER)
            ->attribute('idFrame', key: Key::FOREIGN)
            ->attribute('incorporatedFE', key: Key::FOREIGN)
            ->attribute('idEntity', key: Key::FOREIGN)
            ->attribute('idLanguage',reference: 'lemma.idLanguage')
            ->associationOne('entity', model:  'Entity');
//        ->associationOne('lemma', model:  ''Lemma')
//        ->associationOne('frame', model:  ''Frame', key: 'idFrame')
//        ->associationOne('frameElement', model:  ''FrameElement', key: 'incorporatedFE:idFrameElement')
    }

    public static function OriginMM(ClassMap $classMap): void
    {

        $classMap->table('originmm')
        ->attribute('idOriginMM', key: Key::PRIMARY)
        ->attribute('origin');
    }

    public static function Paragraph(ClassMap $classMap): void
    {

        $classMap->table('paragraph')
        ->attribute('idParagraph', key: Key::PRIMARY)
        ->attribute('documentOrder', type:Type::INTEGER)
        ->attribute('idDocument', key:Key::FOREIGN)
        ->associationMany('sentences', model:  'Sentence', keys: 'idParagraph')
        ->associationOne('document', model:  'Document', key: 'idDocument');
    }

    public static function POS(ClassMap $classMap): void
    {
        $classMap->table('pos')
        ->attribute('idPOS', key: Key::PRIMARY)
        ->attribute('POS')
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->associationOne('entity', model:  'Entity')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');
    }

    public static function Qualia(ClassMap $classMap): void
    {

        $classMap->table('qualia')
        ->attribute('idQualia', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('info')
        ->attribute('infoInverse')
        ->attribute('idTypeInstance', key: Key::FOREIGN)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idFrame', key: Key::FOREIGN)
        ->attribute('idFrameElement1', key: Key::FOREIGN)
        ->attribute('idFrameElement2', key: Key::FOREIGN)
        ->associationOne('entity', model:  'Entity')
        ->associationOne('typeInstance', model:  'TypeInstance')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationOne('frame', model:  'Frame', key: 'idFrame:idFrame')
        ->associationMany('frameElement1', model:  'FrameElement', keys: 'idFrameElement1:idFrameElement')
        ->associationMany('frameElement2', model:  'FrameElement', keys: 'idFrameElement2:idFrameElement');
    }

    public static function RelationGroup(ClassMap $classMap): void
    {

        $classMap->table('relationgroup')
        ->attribute('idRelationGroup', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationMany('entries', model:  'Entry', keys: 'entry:entry');
    }

    public static function Relation(ClassMap $classMap): void
    {

        $classMap->table('entityrelation')
        ->attribute('idEntityRelation', key: Key::PRIMARY)
        ->attribute('idRelation', key: Key::FOREIGN)
        ->attribute('idEntity1', key: Key::FOREIGN)
        ->attribute('idEntity2', key: Key::FOREIGN)
        ->attribute('idEntity3', key: Key::FOREIGN)
        ->attribute('entry', reference: 'relationType.entry')
        ->attribute('entity1Type', reference: 'entity1.type')
        ->attribute('entity2Type', reference: 'entity2.type')
        ->attribute('entity3Type', reference: 'entity3.type')
        ->associationOne('relationType', model:  'RelationType', key: 'idRelationType')
//        ->associationMany('entries', model:  'Entry', keys: 'entry:entry')
        ->associationOne('entity1', model:  'Entity', key: 'idEntity1')
        ->associationOne('entity2', model:  'Entity', key: 'idEntity2')
        ->associationOne('entity3', model:  'Entity', key: 'idEntity3', join: Join::LEFT)
        ->associationOne('lu1', model:  'LU', key: 'idEntity1:idEntity')
        ->associationOne('lu2', model:  'LU', key: 'idEntity2:idEntity')
        ->associationOne('frame1', model:  'Frame', key: 'idEntity1:idEntity')
        ->associationOne('frame2', model:  'Frame', key: 'idEntity2:idEntity')
        ->associationOne('frame', model:  'Frame', key: 'idEntity2:idEntity')
        ->associationOne('construction1', model:  'Construction', key: 'idEntity1:idEntity')
        ->associationOne('construction2', model:  'Construction', key: 'idEntity2:idEntity')
        ->associationOne('construction', model:  'Construction', key: 'idEntity2:idEntity')
        ->associationOne('semanticType1', model:  'SemanticType', key: 'idEntity1:idEntity')
        ->associationOne('semanticType2', model:  'SemanticType', key: 'idEntity2:idEntity')
        ->associationOne('semanticType', model:  'SemanticType', key: 'idEntity2:idEntity')
        ->associationOne('constructionElement1', model:  'ConstructionElement', key: 'idEntity1:idEntity')
        ->associationOne('constructionElement2', model:  'ConstructionElement', key: 'idEntity2:idEntity')
        ->associationOne('constructionElement', model:  'ConstructionElement', key: 'idEntity2:idEntity')
        ->associationOne('frameElement1', model:  'FrameElement', key: 'idEntity1:idEntity')
        ->associationOne('frameElement2', model:  'FrameElement', key: 'idEntity2:idEntity')
        ->associationOne('frameElement', model:  'FrameElement', key: 'idEntity2:idEntity')
        ->associationOne('inverseFrame', model:  'Frame', key: 'idEntity1:idEntity')
        ->associationOne('inverseConstruction', model:  'Construction', key: 'idEntity1:idEntity')
        ->associationOne('inverseSemanticType', model:  'SemanticType', key: 'idEntity1:idEntity')
        ->associationOne('subtypeOfConcept', model:  'Concept', key: 'idEntity2:idEntity')
        ->associationOne('associatedToConcept', model:  'Concept', key: 'idEntity2:idEntity')
        ->associationOne('domain', model:  'Domain', key: 'idEntity2:idEntity')
        ->associationOne('layerType', model:  'LayerType', key: 'idEntity1:idEntity')
        ->associationOne('genericLabel', model:  'GenericLabel', key: 'idEntity2:idEntity')
        ->associationOne('qualia', model:  'Qualia', key: 'idEntity3:idEntity')
        ->associationOne('pos', model:  'POS', key: 'idEntity2:idEntity');
    }

    public static function RelationType(ClassMap $classMap): void
    {

        $classMap->table('relationtype')
        ->attribute('idRelationType', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('prefix')
        ->attribute('nameEntity1')
        ->attribute('nameEntity2')
        ->attribute('nameEntity3')
        ->attribute('idDomain', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idTypeInstance', key: Key::FOREIGN)
        ->attribute('idRelationGroup', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('typeInstance', model:  'TypeInstance')
        ->associationOne('relationGroup', model:  'RelationGroup', key: 'idRelationGroup')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');

    }

    public static function SemanticType(ClassMap $classMap): void
    {

        $classMap->table('semantictype')
        ->attribute('idSemanticType', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', type: Type::INTEGER)
        ->attribute('idDomain', type: Type::INTEGER)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity', key: 'idEntity')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('relations', model:  'Relation', keys: 'idEntity:idEntity1')
        ->associationMany('inverseRelations', model:  'Relation', keys: 'idEntity:idEntity2');

    }

    public static function SentenceMM(ClassMap $classMap): void
    {

        $classMap->table('sentence')
        ->attribute('idSentence', key: Key::PRIMARY)
        ->attribute('text')
        ->attribute('paragraphOrder', type: Type::INTEGER)
        ->attribute('idParagraph', key: Key::FOREIGN)
        ->attribute('idLanguage', key: Key::FOREIGN)
        ->attribute('idDocument', key: Key::FOREIGN)
        ->associationOne('paragraph', model:  'Paragraph', key: 'idParagraph')
        ->associationOne('document', model:  'Document', key: 'idDocument')
        ->associationOne('language', model:  'Language', key: 'idLanguage')
        ->associationMany('sentenceMM', model:  'SentenceMM', keys: 'idSentence:idSentence')
        ->associationMany('documents', model:  'Document', associativeTable:'document_sentence');
    }

    public static function StaticAnnotationMM(ClassMap $classMap): void
    {

        $classMap->table('staticannotationmm')
        ->attribute('idStaticAnnotationMM', key: Key::PRIMARY)
        ->attribute('idFrameElement', type: Type::INTEGER)
        ->attribute('idLU', type: Type::INTEGER)
        ->attribute('idLemma', type: Type::INTEGER)
        ->attribute('idFrame', type: Type::INTEGER)
        ->attribute('idStaticObjectSentenceMM', type: Type::INTEGER, key:Key::FOREIGN)
        ->associationOne('staticObjectSentenceMM', model:  'StaticObjectSentenceMM', key: 'idStaticObjectSentenceMM')
        ->associationOne('frameElement', model:  'FrameElement', key: 'idFrameElement')
        ->associationOne('lu', model:  'LU', key: 'idLU')
        ->associationOne('lemma', model:  'Lemma', key: 'idLemma')
        ->associationOne('frame', model:  'Frame', key: 'idFrame');
    }

    public static function StaticBoxMM(ClassMap $classMap): void
    {

        $classMap->table('staticbboxmm')
        ->attribute('idStaticBBoxMM', key: Key::PRIMARY)
        ->attribute('x', type: Type::INTEGER)
        ->attribute('y', type: Type::INTEGER)
        ->attribute('width', type: Type::INTEGER)
        ->attribute('height', type: Type::INTEGER)
        ->attribute('idStaticObjectMM', type: Type::INTEGER, key: Key::FOREIGN)
        ->associationOne('staticObjectMM', model:  'StaticObjectMM', key: 'idObjectMM');
    }

    public static function StaticObjectMM(ClassMap $classMap): void
    {

        $classMap->table('staticobjectmm')
        ->attribute('idStaticObjectMM', key: Key::PRIMARY)
        ->attribute('scene')
        ->attribute('nobdnbox')
        ->attribute('idFlickr30kEntitiesChain', type: Type::INTEGER)
        ->associationMany('staticBBoxMM', model:  'StaticBBoxMM', keys: 'idStaticObjectMM');
    }

    public static function StaticObjectSentenceMM(ClassMap $classMap): void
    {

        $classMap->table('staticobjectsentencemm')
        ->attribute('idStaticObjectSentenceMM', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('startWord', type: Type::INTEGER)
        ->attribute('endWord', type: Type::INTEGER)
        ->attribute('idStaticSentenceMM', type: Type::INTEGER, key:Key::FOREIGN)
        ->attribute('idStaticObjectMM', type: Type::INTEGER, key:Key::FOREIGN)
        ->associationOne('staticSentenceMM', model:  'StaticSentenceMM', key: 'idStaticSentenceMM')
        ->associationOne('staticObjectMM', model:  'StaticObjectMM', key: 'idStaticObjectMM');
    }

    public static function StaticSentenceMM(ClassMap $classMap): void
    {

        $classMap->table('staticsentencemm')
        ->attribute('idStaticSentenceMM', key: Key::PRIMARY)
        ->attribute('idFlickr30k', type: Type::INTEGER)
        ->attribute('idDocument', type: Type::INTEGER, key: Key::FOREIGN)
        ->attribute('idSentence', type: Type::INTEGER, key: Key::FOREIGN)
        ->attribute('idImageMM', type: Type::INTEGER, key: Key::FOREIGN)
        ->associationOne('sentence', model:  'Sentence', key: 'idSentence:idSentence')
        ->associationOne('imageMM', model:  'ImageMM', key: 'idImageMM:idImageMM')
        ->associationOne('document', model:  'Document', key: 'idDocument')
        ->associationMany('staticObjectSentenceMM', model:  'StaticObjectSentenceMM', keys: 'idStaticSentenceMM:idStaticSentenceMM');
    }

    public static function StatusNN(ClassMap $classMap): void
    {

        $classMap->table('statusmm')
        ->attribute('idStatusMM', key: Key::PRIMARY)
        ->attribute('file')
        ->attribute('video', type: Type::INTEGER)
        ->attribute('audio', type: Type::INTEGER)
        ->attribute('speechToText', type: Type::FLOAT)
        ->attribute('frames', type: Type::INTEGER)
        ->attribute('yolo', type: Type::INTEGER)
        ->attribute('idDocumentMM', type: Type::INTEGER, key: Key::FOREIGN);
    }

    public static function Timeline(ClassMap $classMap): void
    {

        $classMap->table('timeline')
        ->attribute('idTimeline', key: Key::PRIMARY)
        ->attribute('tlDateTime', type: Type::DATETIME)
        ->attribute('author')
        ->attribute('operation')
        ->attribute('tableName')
        ->attribute('idTable', field: 'id', type: Type::INTEGER)
        ->attribute('idUser', type: Type::INTEGER);
    }

    public static function TopFrame(ClassMap $classMap): void
    {
        $classMap->table('topframe')
        ->attribute('idTopFrame', key: Key::PRIMARY)
        ->attribute('frameBase')
        ->attribute('frameTop')
        ->attribute('frameCategory')
        ->attribute('score', type: Type::FLOAT)
        ->associationOne('frame', model:  'Frame', key: 'frameBase:entry');
    }

    public static function Transaction(ClassMap $classMap): void
    {
        $classMap->table('transaction')
        ->attribute('idTransaction', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('description')
        ->associationMany('access', model:  'Access', keys: 'idTransaction');
    }

    public static function TypeInstance(ClassMap $classMap): void
    {

        $classMap->table('typeinstance')
        ->attribute('idTypeInstance', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('info')
        ->attribute('flag')
        ->attribute('idType', key: Key::FOREIGN)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idColor', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('color', model:  'Color', key: 'idColor')
        ->associationOne('type', model:  'Type', key: 'idType')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');
    }

    public static function Type(ClassMap $classMap): void
    {

        $classMap->table('type')
        ->attribute('idType', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationMany('typeInstances', model:  'TypeInstance', keys: 'idType:idType');
    }

    public static function UDFeature(ClassMap $classMap): void
    {
        $classMap->table('udfeature')
        ->attribute('idUDFeature', key: Key::PRIMARY)
        ->attribute('udFeature')
        ->attribute('info')
        ->attribute('type', reference: 'typeinstance.typeInstance')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('typeinstance', model:  'TypeInstance');
    }

    public static function UDPOS(ClassMap $classMap): void
    {
        $classMap->table('udpos')
        ->attribute('idUDPOS', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entity.entries.name')
        ->attribute('description', reference: 'entity.entries.description')
        ->attribute('idLanguage', reference: 'entity.entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');
    }

    public static function UDRelation(ClassMap $classMap): void
    {
        $classMap->table('udrelation')
        ->attribute('idUDRelation', key: Key::PRIMARY)
        ->attribute('info')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idTypeInstance', key: Key::FOREIGN)
        ->attribute('name', reference: 'entity.entries.name')
        ->attribute('description', reference: 'entity.entries.description')
        ->attribute('idLanguage', reference: 'entity.entries.idLanguage')
        ->associationOne('entity', model:  'Entity')
        ->associationOne('typeInstance', model:  'TypeInstance');
    }

    public static function UserAnnotation(ClassMap $classMap): void
    {

        $classMap->table('userannotation')
        ->attribute('idUserAnnotation', key: Key::FOREIGN)
        ->attribute('idUser', key: Key::FOREIGN)
        ->attribute('idSentenceStart', type: Type::INTEGER)
        ->attribute('idSentenceEnd', key: Key::FOREIGN)
        ->attribute('idDocument', key: Key::FOREIGN)
        ->associationOne('document', model:  'Document', key: 'idDocument')
        ->associationOne('sentenceStart', model:  'Sentence', key: 'idSentenceStart:idSentence')
        ->associationOne('sentenceEnd', model:  'Sentence', key: 'idSentenceEnd:idSentence')
        ->associationOne('user', model:  'User', key: 'idUser:idUser');
    }

    public static function User(ClassMap $classMap): void
    {
        $classMap->table('user')
        ->attribute('idUser', key: Key::PRIMARY)
        ->attribute('login')
        ->attribute('passMD5')
        ->attribute('config')
        ->attribute('name')
        ->attribute('email')
        ->attribute('status')
        ->attribute('active')
        ->attribute('auth0IdUser')
        ->attribute('auth0CreatedAt')
        ->attribute('lastLogin', type: Type::TIMESTAMP)
        ->attribute('idLanguage', type: Type::INTEGER)
        ->associationMany('groups', model:  'Group', associativeTable: 'user_group');
    }

    public static function ViewAnnotationSet(ClassMap $classMap): void
    {

        $classMap->table('view_annotationset')
        ->attribute('idAnnotationSet', key: Key::PRIMARY)
        ->attribute('idSentence', key: Key::FOREIGN)
        ->attribute('entry')
        ->attribute('idAnnotationStatus', key: Key::FOREIGN)
        ->attribute('idLU')
        ->attribute('idEntityLU')
        ->attribute('idConstruction')
        ->attribute('idEntityCxn')
        ->associationMany('lu', model:  'LU', keys: 'idEntityLU:idEntity')
        ->associationMany('cxn', model:  'Construction', keys: 'idEntityCxn:idEntity')
        ->associationOne('entries', model:  'ViewEntryLanguage', key: 'entry')
        ->associationOne('sentence', model:  'Sentence', key: 'idSentence')
        ->associationOne('annotationStatusType', model:  'ViewAnnotationStatusType', key: 'entry')
        ->associationMany('layers', model:  'Layer', keys: 'idAnnotationSet');
    }

    public static function ViewAnnotationStatus(ClassMap $classMap): void
    {

        $classMap->table('view_annotationstatustype')
        ->attribute('idType', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('info')
        ->attribute('flag', type:Type::INTEGER)
        ->attribute('idColor',type:Type::INTEGER)
        ->attribute('idEntity',type:Type::INTEGER)
        ->associationOne('entries', model:  'ViewEntryLanguage', key: 'entry')
        ->associationOne('color', model:  'Color', key: 'idColor');
    }

    public static function ViewConstraint(ClassMap $classMap): void
    {
        $classMap->table('view_constraint')
        ->attribute('idConstraintInstance', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('prefix')
        ->attribute('idConstraint', type: Type::INTEGER)
        ->attribute('constraintType')
        ->attribute('idConstrained', type: Type::INTEGER)
        ->attribute('constrainedType')
        ->attribute('idConstrainedBy', type: Type::INTEGER)
        ->attribute('constrainedByType')
        ->associationOne('entityConstraint', model:  'Entity', key: 'idConstraint:idEntity')
        ->associationOne('entityConstrained', model:  'Entity', key: 'idConstrained:idEntity')
        ->associationOne('entityConstrainedBy', model:  'Entity', key: 'idConstrainedBy:idEntity');
    }

    public static function ViewConstructionElement(ClassMap $classMap): void
    {
        $classMap->table('view_constructionelement')
        ->attribute('idConstructionElement', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idColor', key: Key::FOREIGN)
        ->attribute('idConstruction', key: Key::FOREIGN)
        ->attribute('constructionEntry')
        ->attribute('constructionIdEntity', type: Type::INTEGER)
        ->associationOne('entries', model:  'Entry', key: 'entry')
        ->associationOne('construction', model:  'Construction', key: 'idConstruction')
        ->associationOne('color', model:  'Color', key: 'idColor');
    }

    public static function ViewConstruction(ClassMap $classMap): void
    {

        $classMap->table('view_construction')
        ->attribute('idConstruction', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idLanguage', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationOne('entity', model:  'Entity', key: 'idEentity')
        ->associationMany('ces', model:  'ViewConstructionElement', keys: 'idConstruction')
        ->associationMany('annotationSets', model:  'ViewAnnotationSet', keys: 'idConstruction');
    }

    public static function ViewDomain(ClassMap $classMap): void
    {

        $classMap->table('view_domain')
        ->attribute('idDomain', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->attribute('idEntityRel', key: Key::FOREIGN)
        ->attribute('entityType')
        ->attribute('nameRel');
    }

    public static function ViewEntryLanguage(ClassMap $classMap): void
    {

        $classMap->table('view_entrylanguage')
        ->attribute('idEntry', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('name')
        ->attribute('description')
        ->attribute('language')
        ->attribute('idLanguage', key: Key::FOREIGN)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->associationOne('language', model:  'Language');
    }

    public static function ViewFrameElement(ClassMap $classMap): void
    {
        $classMap->table('view_frameelement')
        ->attribute('idFrameElement', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('typeEntry')
        ->attribute('frameEntry')
        ->attribute('frameIdEntity', type: Type::INTEGER)
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idFrame', key: Key::FOREIGN)
        ->attribute('idColor', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity')
        ->associationOne('frame', model:  'Frame', key: 'idFrame')
        ->associationOne('color', model:  'Color', key: 'idColor')
        ->associationMany('labels', model:  'Label', keys: 'idFrameElement:idLabelType');
    }

    public static function ViewFrame(ClassMap $classMap): void
    {
        $classMap->table('view_frame')
        ->attribute('idFrame', key: Key::PRIMARY)
        ->attribute('entry')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('name', reference: 'entries.name')
        ->attribute('description', reference: 'entries.description')
        ->attribute('idLanguage', reference: 'entries.idLanguage')
        ->associationMany('entries', model:  'Entry', keys:'idEntity:idEntity')
        ->associationMany('lus', model:  'LU', keys: 'idFrame')
        //->associationMany('fes', model:  'FrameElement', keys: 'idFrame')
        ->associationMany('entries', model:  'Entry', keys: 'idEntity:idEntity');
        //->associationMany('relations', model:  'ViewRelation', keys: 'idEntity:idEntity1')
        //->associationMany('inverseRelations', model:  ''ViewRelation', keys: 'idEntity:idEntity2')
        ;
    }

    public static function ViewLU(ClassMap $classMap): void
    {

        $classMap->table('view_lu')
        ->attribute('idLU', key: Key::PRIMARY)
        ->attribute('name')
        ->attribute('senseDescription')
        ->attribute('active', type: Type::INTEGER)
        ->attribute('importNum', type: Type::INTEGER)
        ->attribute('incorporatedFE', type: Type::INTEGER)
        ->attribute('idEntity', key: Key::FOREIGN)
        ->attribute('idLemma', key: Key::FOREIGN)
        ->attribute('idFrame', key: Key::FOREIGN)
        ->attribute('frameEntry')
        ->attribute('lemmaName')
        ->attribute('idLanguage', key: Key::FOREIGN)
        ->attribute('idPOS', key: Key::FOREIGN)
        ->associationOne('lemma', model:  'Lemma')
        ->associationOne('frame', model:  'Frame', key: 'idFrame')
        ->associationOne('pos', model:  'POS', key: 'idPOS')
        ->associationOne('language', model:  'Language', key: 'idLanguage')
        ->associationMany('annotationSets', model:  'ViewAnnotationSet', keys: 'idLU');
    }


    public static function ViewRelation(ClassMap $classMap): void
    {

        $classMap->table('view_relation')
        ->attribute('idEntityRelation', key: Key::PRIMARY)
        ->attribute('domain')
        ->attribute('relationGroup')
        ->attribute('idRelationType', type: Type::INTEGER)
        ->attribute('relationType')
        ->attribute('prefix')
        ->attribute('idEntity1', type: Type::INTEGER)
        ->attribute('idEntity2', type: Type::INTEGER)
        ->attribute('idEntity3', type: Type::INTEGER)
        ->attribute('entity1Type')
        ->attribute('entity2Type')
        ->attribute('entity3Type')
        ->associationOne('relationType', model:  'RelationType', key: 'idRelationType')
        ->associationMany('entries', model:  'Entry', keys: 'entry:entry')
        ->associationOne('entity1', model:  'Entity', key: 'idEntity1')
        ->associationOne('entity2', model:  'Entity', key: 'idEntity2')
        ->associationOne('entity3', model:  'Entity', key: 'idEntity3', join: Join::LEFT);
    }

    public static function ViewWfLexemeLemma(ClassMap $classMap): void
    {
        $classMap->table('view_wflexemelemma')
        ->attribute('idWordForm', key: Key::PRIMARY)
        ->attribute('form')
        ->attribute('idLexeme',key:Key::FOREIGN)
        ->attribute('lexeme')
        ->attribute('idPOSLexeme',key:Key::FOREIGN)
        ->attribute('POSLexeme')
        ->attribute('idLanguage',key:Key::FOREIGN)
        ->attribute('idLexemeEntry',key:Key::FOREIGN)
        ->attribute('lexemeOrder')
        ->attribute('headWord')
        ->attribute('idLemma',key:Key::FOREIGN)
        ->attribute('idPOSLemma',key:Key::FOREIGN)
        ->attribute('POSLemma');
    }

    public static function WordForm(ClassMap $classMap): void
    {
        $classMap->table('wordform')
        ->attribute('idWordForm', key: Key::PRIMARY)
        ->attribute('form')
        ->attribute('md5')
        ->associationOne('entity', model:  'Entity', key: 'idEntity')
        ->associationOne('lexeme', model:  'Lexeme', key: 'idLexeme');
    }



}
