<?php

namespace App\Repositories;

use App\Services\AppService;
use Orkester\Persistence\Repository;

class LU extends Repository
{
    public function getIdFrame()
    {
        return $this->idFrame;
    }

    public function setIdFrame($value)
    {
        $this->idFrame = (int)$value;
    }

//    public function getById($id): void
//    {
//        parent::getById($id);
//        $criteria = $this->getCriteria()->select('frame.idFrame');
//        //Base::relation($criteria, 'LU', 'Frame', 'rel_evokes');
//        $criteria->where("idLU = {$id}");
//        $result = $criteria->asQuery()->getResult();
//        $this->setIdFrame($result[0]['idFrame']);
//    }

    public function getByIdEntity($idEntity)
    {
        $criteria = $this->getCriteria();
        $criteria->where("idEntity = {$idEntity}");
        $this->retrieveFromCriteria($criteria);
    }

    public function getData()
    {
        $data = parent::getData();
        $data->idFrame = $this->idFrame;
        $criteria = Base::relationCriteria('LU', 'SemanticType', 'rel_hastype', 'SemanticType.idEntity');
        $criteria->where("LU.idEntity", "=", $this->getIdEntity());
        $idEntitySemanticType = $criteria->asQuery()->getResult()[0]['idEntity'];
        if ($idEntitySemanticType) {
            $st = new SemanticType();
            $stData = $st->getByIdEntity($idEntitySemanticType);
            $data->idSemanticType = $stData->idSemanticType;
        }
        return $data;
    }

//    public function setData($data, $role = 'default')
//    {
//        parent::setData($data);
//        $this->idFrame = $data->idFrame;
//    }

    public function getFrame()
    {
        return Frame::create($this->getIdFrame());
    }

    public function getDescription()
    {
        return $this->getIdLU();
    }

    public function getFullName()
    {
        $this->retrieveAssociation("frame", AppService::getCurrentIdLanguage());
        return $this->frame->name . '.' . $this->name;
    }

    public function listByFilter($filter)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $criteria = $this->getCriteria()->select('*,frame.entries.name as frameName');
        $criteria->where("frame.entries.idLanguage", "=", $idLanguage);
        if ($filter->idLU) {
            if (is_array($filter->idLU)) {
                $criteria->where("idLU", "IN", $filter->idLU);
            } else {
                $criteria->where("idLU = {$filter->idLU}");
            }
        }
        if ($filter->name) {
            $criteria->where("name", "LIKE", "'{$filter->name}%'")->orderBy('name');
        }
        if ($filter->idLanguage) {
            $criteria->where("lemma.idLanguage", "=", $filter->idLanguage);
        }
        return $criteria;
    }

    public function listForSelect(string $name = '', string $pos = '')
    {
        $idLanguage = AppService::getCurrentIdLanguage();
        $name = (strlen($name) > 2) ? $name : '-none';
        $criteria = $this->getCriteria()
            ->select(['idLU',"concat(frame.name,'.',name) as name"])
            ->orderBy('frame.name,name');
        $criteria->where("lemma.idLanguage","=",$idLanguage);
        $criteria->where("frame.idLanguage","=",$idLanguage);
        if ($pos != '') {
            $criteria->where("lemma.pos.POS","=",$pos);
        }
        $criteria->whereRaw("upper(lu.name) LIKE upper('{$name}%')");
        return $criteria;
    }
    public function listForEvent(string $name = '')
    {
        $result = [];
        $idLanguage = AppService::getCurrentIdLanguage();
        $name = (strlen($name) > 2) ? $name : '-none';
        $criteria = $this->getCriteria()
            ->select(['idLU',"concat(frame.name,'.',name) as name"])
            ->orderBy('frame.name,name');
//        $criteria->where("lemma.idLanguage","=",$idLanguage);
        $criteria->where("frame.idLanguage","=",$idLanguage);
        $criteria->where("lemma.pos.POS","=","V");
        $criteria->whereRaw("upper(lu.name) LIKE upper('{$name}%')");
        $partial1 = $criteria->get()->keyBy('idLU')->all();
        foreach($partial1 as $lu) {
            $result[] = $lu;
        }
        $topFrame = new TopFrame();
        $criteria = $topFrame->getCriteria()
            ->select(['frame.lus.idLU',"concat(frame.name,'.',frame.lus.name) as name"])
            ->distinct()
            ->where('frameTop','NOT IN', ['frm_entity','frm_attributes'])
            ->where("frame.idLanguage","=",$idLanguage)
            ->where("frame.lus.name","startswith", $name)
            ->orderBy('frame.name,name');
        $partial2 = $criteria->get()->keyBy('idLU')->all();
        foreach($partial2 as $lu) {
            if(!isset($partial1[$lu['idLU']])) {
                $result[] = $lu;
            }
        }
        return $result;
    }
    public function listForLookup($filter = null)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $criteria = $this->getCriteria()
            ->select("idLU, concat(frame.entries.name,'.',name) as fullname")
            ->orderBy('frame.entries.name,name');
        $criteria->where("lemma.idLanguage = {$idLanguage}");
        $criteria->where("frame.entries.idLanguage = {$idLanguage}");
        $fullname = $filter ? $filter->fullname : '';
        $fullname = (strlen($fullname) > 2) ? $fullname : '-none-';
        $criteria->where("upper(name) LIKE upper('{$fullname}%')");
        return $criteria;
    }

    public function listForLookupEquivalent($filter = null)
    {
        $criteria = $this->getCriteria()->select("idLU, concat(frame.entries.name,'.',name,' [', lemma.language.language, ']' ) as fullname")->orderBy('frame.entries.name,name');
        Base::relation($criteria, 'LU', 'Frame frame', 'rel_evokes');
        $criteria->where("lemma.idLanguage = entry.idLanguage");
        $fullname = $filter ? $filter->fullname : '';
        $fullname = (strlen($fullname) > 2) ? $fullname : '-none-';
        $criteria->where("upper(name) LIKE upper('{$fullname}%')");
        return $criteria;
    }

    public function listForConstraint($array)
    {
        $idLanguage = \Manager::getSession()->idLanguage;
        $criteria = $this->getCriteria()->select("idLU as del, idLU, concat(frame.entries.name,'.',name) as fullname")->orderBy('frame.entries.name,name');
        $criteria->where("idLU", "IN", $array);
        Base::relation($criteria, 'LU', 'Frame frame', 'rel_evokes');
        Base::entryLanguage($criteria, 'frame');
        $criteria->where("lemma.idLanguage = {$idLanguage}");
        return $criteria;
    }

    public function listConstraints()
    {
        $constraint = new ViewConstraint();
        $constraints = $constraint->listLUSTConstraints($this->getIdEntity());
        //$qualiaConstraints = $constraint->listLUQualiaConstraints($this->getIdEntity());
        //foreach ($qualiaConstraints as $qualia) {
        //    $constraints[] = $qualia;
        //}
        $domainConstraints = $constraint->listLUDomainConstraints($this->getIdEntity());
        foreach ($domainConstraints as $domain) {
            $constraints[] = $domain;
        }
        $equivalenceConstraints = $constraint->listLUEquivalenceConstraints($this->getIdEntity());
        foreach ($equivalenceConstraints as $equivalence) {
            $constraints[] = $equivalence;
        }
        $metonymyConstraints = $constraint->listLUMetonymyConstraints($this->getIdEntity());
        foreach ($metonymyConstraints as $metonymy) {
            $constraints[] = $metonymy;
        }
        return $constraints;
    }


    public function getPOS()
    {
        $lemma = $this->getLemma();
        $pos = $lemma->getPOS();
        return $pos->getPOS();
    }

    public function create($data)
    {
        $this->beginTransaction();
        try {
            $lemma = new Lemma($data->idLemma);
            $baseEntry = 'lu_' . $lemma->name . '_' . $data->idFrame;
            $entity = new Entity();
            $idEntity = $entity->create('LU', $baseEntry);
            $id = $this->saveData([
                'idFrame' => $data->idFrame,
                'idLemma' => $data->idLemma,
                'name' => $lemma->name,
                'senseDescription' => $data->senseDescription,
                'active' => 1,
                'incorporatedFE' => ($data->incorporatedFE == 0) ? null : $data->incorporatedFE,
                'idEntity' => $idEntity
            ]);
            Timeline::addTimeline("lu", $id, "S");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            ddump($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    /*
    public function saveData($data): ?int
    {
        $transaction = $this->beginTransaction();
        try {
            $this->setData($data);
            if (!$this->isPersistent()) {
                $entity = new Entity();
                $alias = 'lu_' . $data->name . '_' . $data->idFrame . '_' . $data->idLemma;
                $entity->getByAlias($alias);
                if ($entity->getIdEntity()) {
                    throw new \Exception("This LU already exists!.");
                } else {
                    $entity->setAlias($alias);
                    $entity->setType('LU');
                    $entity->save();
                    $this->setIdEntity($entity->getId());
                }
            }
            Base::deleteEntity1Relation($this->getIdEntity(), 'rel_hastype');
            if ($data->idSemanticType) {
                $st = new SemanticType();
                $st->getById($data->idSemanticType);
                Base::createEntityRelation($this->getIdEntity(), 'rel_hastype', $st->getIdEntity());
            }
            //Base::entityTimelineSave($this->getIdEntity());
            $this->setActive(true);
            $idLU = parent::save();
            Timeline::addTimeline("lu", $this->getId(), "S");
            $transaction->commit();
            return $idLU;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw new \Exception($e->getMessage());
        }
    }
    */

    public function delete()
    {
        $this->beginTransaction();
        try {
            Base::deleteAllEntityRelation($this->idEntity);
            Timeline::addTimeline("lu", $this->idLU, "D");
            parent::delete();
            $entity = new Entity($this->idEntity);
            $entity->delete();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            ddump($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function update($data)
    {
        $this->beginTransaction();
        try {
            $this->saveData($data);
            Timeline::addTimeline("lu", $this->getId(), "U");
            $this->commit();
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Upload LU from simple text file
     * Line: wordform|lexeme|lemma|frame(english)
     * Parâmetro data informa: idLanguage
     * @param type $data
     * @param type $file
     */
    public function uploadLUOffline($data)
    {
        $idLanguage = $data->idLanguage;
        $pos = new POS();
        $POS = $pos->listAll()->asQuery()->chunkResult('POS', 'idPOS');
        $lexeme = new Lexeme();
        $lemma = new Lemma();
        $frame = new Frame();
        $wf = new WordForm();
        $transaction = $this->beginTransaction();
        $c1 = $c2 = 0;
        try {
            $lineNum = 0;
            $rows = $data->rows;
            foreach ($rows as $row) {
                $lineNum++;
                $row = trim($row);
                if (($row == '') || (substr($row, 0, 2) == "//")) {
                    continue;
                }
                print_r(' ================= ' . "\n");
                print_r(' row = ' . $row . "\n");
                list($wordform, $lexemePOS, $lemmaFull, $frameName) = explode('|', $row);
                $frameEntry = 'frm_' . strtolower($frameName);
                $frame->getByEntry($frameEntry);
                $idFrame = $frame->getId();
                if ($idFrame != '') {
                    list($lemmaName, $lemmaPOS) = explode('.', $lemmaFull);
                    $lemmaFullLower = $lemmaName . '.' . strtolower($lemmaPOS);
                    print_r(' lemma = ' . $lemmaFullLower . "\n");
                    list($lexemeName, $POSName) = explode('.', $lexemePOS);
                    $POSNameUpper = strtoupper($POSName);
                    $line = $wordform . ' ' . $POSNameUpper . ' ' . $lexemeName;
                    print_r('line = ' . $line . "\n");

                    $idLexeme = $lexeme->createLexemeWordform($line, $wf, $POS, $idLanguage);
                    //verifica se o Lemma já existe
                    $lemma = new Lemma();
                    $lemma->getByNameIdLanguage($lemmaFullLower, $idLanguage);
                    if ($lemma->getId() == '') {
                        $lemmaData = (object)[];
                        $lemmaIdPOS = $POS[$POSNameUpper];
                        $lemmaData->lemma = (object)[
                            'name' => $lemmaFullLower,
                            'idPOS' => $lemmaIdPOS,
                            'idLanguage' => $idLanguage
                        ];
                        $lemmaData->lexeme = [
                            $lexemeName => [
                                'id' => $idLexeme,
                                'headWord' => true,
                                'breakBefore' => false
                            ]
                        ];
                        //print_r($lemmaData);
                        $lemma->save($lemmaData);
                        $c1++;
                    }
                    //verifica se a LU já existe
                    $entity = new Entity();
                    $alias = 'lu_' . $lemma->getName() . '_' . $idFrame . '_' . $lemma->getIdLemma();
                    $entity->getByAlias($alias);
                    print_r('alias = ' . $alias . "  identity = " . $entity->getIdEntity() . "\n");
                    if ($entity->getIdEntity() == '') {
                        $luData = (object)[
                            'idFrame' => $idFrame
                        ];
                        print_r("creating LU " . $lemma->getName() . '  Frame: ' . $idFrame . "\n");
                        $lemma->saveForLU($luData);
                        $c2++;
                    }
                }
            }
            print_r("***********\n");
            print_r('** created Lemma  = ' . $c1 . "\n");
            print_r('** created LU  = ' . $c2 . "\n");
            print_r("***********\n");
            $transaction->commit();
        } catch (\Exception $e) {
            // rollback da transação em caso de algum erro
            $transaction->rollback();
            print_r($e->getMessage() . ' LineNum: ' . $lineNum . "\n");
            throw new \Exception($e->getMessage() . ' LineNum: ' . $lineNum);
        }
    }

}
