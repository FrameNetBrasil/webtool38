<?php
/**
 * 
 *
 * @category   Maestro
 * @package    UFJF
 *  @subpackage fnbr
 * @copyright  Copyright (c) 2003-2012 UFJF (http://www.ufjf.br)
 * @license    http://siga.ufjf.br/license
 * @version    
 * @since      
 */

namespace App\Repositories;

use App\Models\ParagraphModel;
use Maestro\Persistence\Repository;

class Paragraph extends Repository {

    public ?int $idParagraph;
    public ?int $documentOrder;
    public ?int $idDocument;
    public ?array $sentences;
    public ?object $document;

    public function __construct(int $id = null)
    {
        parent::__construct(ParagraphModel::class, $id);
    }
    public function getDescription(){
        return $this->getIdParagraph();
    }

    public function listByFilter($filter){
        $criteria = $this->getCriteria()->select('*')->orderBy('idParagraph');
        if ($filter->idParagraph){
            $criteria->where("idParagraph LIKE '{$filter->idParagraph}%'");
        }
        return $criteria;
    }
}

?>