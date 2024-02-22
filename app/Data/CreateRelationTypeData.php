<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class CreateRelationTypeData extends Data
{
    public function __construct(
        public string|int $idRelationGroup,
        public string|int $idDomain,
        public ?string    $nameEn = '',
        public ?string    $prefix = '',
        public string     $_token = '',
    )
    {
        $this->_token = csrf_token();
    }

    public static function messages(): array
    {
        return [
            'idRelationGroup.required' => 'Field [RelationGroup] is required',
            'nameEn.required' => 'Field [Name] is required',
        ];
    }

}
