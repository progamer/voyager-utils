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

    @case('file')
        <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
        <label for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>
        <div>
            <table class="table table-hover">
                @foreach($value as $index => $file)
                    <tr class="list-unstyled">
                        <td>
                            <div class="">
                                <div class="col-md-12">
                                    <a target="_blank" href="{{Voyager::image($file['download_link'])}}">{{$file['original_name']}}</a><br>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
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

