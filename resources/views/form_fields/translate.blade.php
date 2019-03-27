<input
        id="trans-{{ $dataTypeContent->id }}"
        type="hidden"
        name="{{ $row->field }}"
        value={{json_encode($dataTypeContent->{$row->field}, JSON_UNESCAPED_UNICODE )}}

/>

<div>
       @foreach( config('voyager.multilingual.locales') as $locale )
              <div class="input-group">
                     <span class="input-group-addon" id="{{$locale}}">{{strtoupper($locale)}}</span>
                     <input type="text" data-holder="trans-{{ $dataTypeContent->id }}"  class="form-control translation-input" data-lang="{{$locale}}" id="{{$locale}}" value="@if(isset( $dataTypeContent->{$row->field}[$locale] )){{$dataTypeContent->{$row->field}[$locale]}}@endif">
              </div>
       @endforeach
</div>
