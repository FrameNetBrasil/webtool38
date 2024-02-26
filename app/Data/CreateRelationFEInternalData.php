<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class CreateRelationFEInternalData extends Data
{
    #[Computed]
    public ?int $idRelationType;
    public function __construct(
        public object $idFrameElementRelated,
        public string $relationType,
    )
    {
        $this->idRelationType = (int)substr($this->relationType, 1);
    }

    public static function messages(): array
    {
        return [
            'relationType.required' => 'Field [Relation] is required',
        ];
    }

}
