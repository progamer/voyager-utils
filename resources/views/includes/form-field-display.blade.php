@switch($type)
    @case('string')
    @case('numeric')
    @case('tel')
    @case('email')

        <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
            <label for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>
            <div>
                {{$value}}
            </div>
        </div>
    @break

    @case('select')
    <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
        <label for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>

        <div>
            <ul>
            @foreach( $options as $option )
                @if(isset($value) and in_array($option['value'], $value ))
                    <li>{{$option['title'][session('locale', 'ar')]}}</li>
                @endif
            @endforeach
            </ul>
        </div>
    </div>
    @break

    @default
@endswitch

