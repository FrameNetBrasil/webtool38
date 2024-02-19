<x-layout.content>
    <x-form
        id="luConstraintFormNew"
        title="New LU Constraint"
        center="true"
    >
        <x-slot:toolbar>
            <x-link-button
                id="luConstraintFormNewMetonymLU"
                label="Metonym-LU"
                hx-get="/lu/{{$data->idLU}}/constraints/formNew/metonymLU"
                hx-target="#luConstraintFormNewFields"
            ></x-link-button>
            <x-link-button
                id="luConstraintFormNewEquivalentLU"
                label="Equivalent-LU"
                hx-get="/lu/{{$data->idLU}}/constraints/formNew/equivalenceLU"
                hx-target="#luConstraintFormNewFields"
            ></x-link-button>
        </x-slot:toolbar>
        <x-slot:fields>
            <div id="luConstraintFormNewFields">
                @if ($data->fragment)
                    @fragment('metonymLU')
                        <x-layout.content>
                            <x-hidden-field
                                id="constraint"
                                value="rel_lustandsforlu"
                            ></x-hidden-field>
                            <x-combobox.lu
                                id="idLUMetonymConstraint"
                                label="Related LU"
                            ></x-combobox.lu>
                        </x-layout.content>
                    @endfragment
                    @fragment('equivalenceLU')
                        <x-layout.content>
                            <x-hidden-field
                                id="constraint"
                                value="rel_luequivalence"
                            ></x-hidden-field>
                            <x-combobox.lu
                                id="idLUEquivalenceConstraint"
                                label="Equivalent LU"
                            ></x-combobox.lu>
                        </x-layout.content>
                    @endfragment
                @endif
            </div>
        </x-slot:fields>
        <x-slot:buttons>
            <x-submit label="Add Constraint" hx-post="/lu/{{$data->idLU}}/constraints"></x-submit>
        </x-slot:buttons>
    </x-form>
</x-layout.content>
