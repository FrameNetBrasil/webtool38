<?php

namespace App\Services;

use App\Repositories\Qualia;
use Orkester\Manager;


class QualiaService
{
    public static function listRelationsForSelect()
    {
        $qualia = new Qualia();
        return $qualia->listForSelect();
    }

    public static function listTypesForSelect()
    {
        $qualia = new Qualia();
        return $qualia->listTypesForSelect();
    }

    public static function listForGrid()
    {
        $data = Manager::getData();
        $result = [];
        $filter = (object)[
            'idQualiaType' => $data->idQualiaType ?? null,
            'frame' => $data->frame ?? ''
        ];
        $qualia = new Qualia();
        $list = $qualia->listForGrid($filter);
        // select q.idQualia, eq.name info, t.entry qualiaEntry, et.name qualiaType, t.idTypeInstance idQualiaType, ef.name frame, efe1.name fe1, fe1.coreType fe1Type,efe2.name fe2, fe2.coreType fe2Type
        $icon = 'material-icons-outlined wt-tree-icon ';
        $iconFE = config('webtool.fe.icon.tree');
        foreach ($list as $row) {
            $node = $row;
            $node['id'] = $row['idQualia'];
            $node['state'] = 'closed';
            $node['color'] = "color_{$row['qualiaEntry']}--text";
            $node['icon'] = $icon . match ($row['qualiaEntry']) {
                    'qla_agentive' => 'wt-icon-qualia-agentive',
                    'qla_constitutive' => 'wt-icon-qualia-constitutive',
                    'qla_context' => 'wt-icon-qualia-context',
                    'qla_formal' => 'wt-icon-qualia-formal',
                    'qla_telic' => 'wt-icon-qualia-telic',
                };
            $node['iconFE1'] = $iconFE[$row['fe1Type']];
            $node['iconFE2'] = $iconFE[$row['fe2Type']];
            $node['children'] = [];
            $result[] = $node;
        }
        return $result;
    }

}
