<?php

namespace App\Services;

use App\Repositories\Qualia;
use App\Repositories\SemanticType;
use Orkester\Manager;


class SemanticTypeService
{
    public static function listForComboGrid(string $root = '')
    {
        $st = new SemanticType();
        $st->retrieveFromName($root);
        $list = $st->listChildren($st->idSemanticType, (object)[])->getResult();
        $result = [];
        foreach ($list as $row) {
            $node = $row;
            $node['state'] = 'open';
            $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-semantictype';
            $children = SemanticTypeService::listForComboGrid($row['name']);
            $node['children'] = !empty($children) ? $children : null;
            $result[] = $node;
        }
        return $result;
    }

}