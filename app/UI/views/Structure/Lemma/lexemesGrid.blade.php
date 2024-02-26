    <x-datagrid
        id="gridLexeme"
        title="Lexemes"
        type="child"
        hx-trigger="reload-gridLexeme from:body"
        hx-target="this"
        hx-swap="outerHTML"
        hx-get="/lemma/{{$idLemma}}/lexemes/grid"
    >
        @foreach($lexemes as $lx)
            <tr>
                <td class="wt-datagrid-action">
                    <div
                        class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"
                        title="delete Lexeme"
                        hx-delete="/lemma/{{$idLemma}}/lexemes/delete/{{$lx['idLexemeEntry']}}"
                    ></div>
                </td>
                <td
                    style="min-width:120px"
                >
                    {{$lx['name']}}
                </td>
            </tr>
        @endforeach
    </x-datagrid>
