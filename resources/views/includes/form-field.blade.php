@switch($type)
    @case('string')
    @case('numeric')
    @case('tel')
    @case('email')

        <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
            <label for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>
            <input
                    id="{{$name}}" tabindex="{{$order}}" name="{{$name}}" type="{{$type}}" class="form-control"
                    @if(isset($validation['required']) && $validation['required'])required="required" @endif

                    @if(isset($value))value="{{$value}}" @endif

                    placeholder="{{$placeholder[session('locale', 'ar')]}}">
        </div>
    @break

    @case('select')
    <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
        <label for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>
        <select tabindex="{{$order}}" class="form-control" id="{{$name}}" name="{{$name}}[]"
                @if(isset($validation['required']) && $validation['required'])required="required" @endif>
            @foreach( $options as $option )
                <option value="{{$option['value']}}" @if(isset($value) and in_array($option['value'], $value )) selected=selected @endif>
                    {{$option['title'][session('locale', 'ar')]}}
                </option>
            @endforeach
        </select>
    </div>
    @break

    @case('boolean')
        <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
            <div class="custom-control custom-checkbox mt-3 mb-4">
                <input tabindex="{{$order}}" type="checkbox" class="custom-control-input" name="{{$name}}" id="{{$name}}" value="1"
                       @if(isset($validation['required']) && $validation['required'])required="required"@endif
                >
                <label class="custom-control-label f-14" for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>
            </div>
        </div>
    @break

    @case('header')
        <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
            <p class="pt-2">{{$title[session('locale', 'ar')]}}</p>
        </div>
    @break
    @default
@endswitch

