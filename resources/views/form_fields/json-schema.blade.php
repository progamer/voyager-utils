@php
    $schema = collect($dataTypeContent->form->fields);
    $values = collect($dataTypeContent->fields_values);
@endphp



<div class="row">
    @foreach($schema->sortBy('order')->whereNotIn('name', ['header', 'agree'])->whereNotIn('type', ['header', 'agree']) as $field)
        @php
            $field['value'] = $values->get($field['name'])
        @endphp
        @if(isset($view) and $view=='read')
            @formFieldDisplay($field)
        @else
            @formField($field)
        @endif
    @endforeach
</div>
