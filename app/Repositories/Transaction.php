<?php

namespace App\Repositories;

use Orkester\Persistence\Repository;

class Transaction extends Repository
{

    public function listByFilter($filter)
    {
        $criteria = $this->getCriteria()->select('*')->orderBy('idTransaction');
        if ($filter->idTransaction) {
            $criteria->where("idTransaction LIKE '{$filter->idTransaction}%'");
        }
        return $criteria;
    }

    public function listGroups()
    {
        $criteria = $this->getCriteria()->select("access.idAccess,access.group.idGroup,access.group.name,access.rights")->orderBy("access.group.name");
        if ($this->idTransaction) {
            $criteria->where("idTransaction = {$this->idTransaction}");
        }
        return $criteria;
    }

    public function deleteAcesso($delete)
    {
        try {
            $transaction = $this->beginTransaction();
            if (is_array($delete)) {
                foreach ($delete as $id) {
                    Access::create($id)->delete();
                }
            } else {
                Access::create($delete)->delete();
            }
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            throw new EModelException('Error');
        }
    }
}
