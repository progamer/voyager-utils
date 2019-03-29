@switch($type)
    @case('string')
    @case('numeric')
    @case('tel')
    @case('email')
    @case('password')

    <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
        <label for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>
        <input
                id="{{$name}}"

                @if(isset($order)) tabindex="{{$order}}"  @endif

                name="{{$name}}" type="{{$type}}" class="form-control"
                @if(isset($validation['required']) && $validation['required'])required="required" @endif

                @if(isset($value))value="{{$value}}" @endif
                @if(isset($placeholder)) placeholder="{{$placeholder[session('locale', 'ar')]}}"  @endif
        >
    </div>
    @break

    @case('file')

    <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">

        <div class=" input-group col-md-12">
            <label class="custom-file-label" for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>

            @if(isset($value))
                <table class="table table-hover">
                    @foreach($value as $index => $file)
                        <tr class="list-unstyled">
                            <td>
                                <div class="">
                                    <div class="col-md-10">
                                        <a target="_blank" href="{{Voyager::image($file['download_link'])}}">{{$file['original_name']}}</a><br>
                                    </div>
                                    <div class="col-md-2">
                                        <a class="btn btn-small btn-danger" href="{{route("applications.files.destroy", ['application' => $dataTypeContent , 'field' => $name, 'index' => $index])}}">{{__("Delete")}}</a>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </table>
            @endif
            <input
                    id="{{$name}}"
                    class="custom-file-input"

                    @if(isset($attrs) && isset($attrs['type']))  accept="{{$attrs['type']}}" @endif
                    @if(isset($attrs) && isset($attrs['multiple']))  multiple @endif
                    @if(isset($order)) tabindex="{{$order}}"  @endif

                    name="{{$name}}[]" type="{{$type}}" class="form-control"
                    {{--@if(isset($validation['required']) && $validation['required'])required="required" @endif--}}
                    @if(isset($placeholder)) placeholder="{{$placeholder[session('locale', 'ar')]}}"  @endif
            >

        </div>

    </div>

    @break

    @case('select')
    <div class="form-group @if(isset($display['width']))col-md-{{$display['width']}}@endif">
        <label for="{{$name}}">{{$title[session('locale', 'ar')]}}</label>
        <select
                @if(isset($order)) tabindex="{{$order}}"  @endif
        class="form-control" id="{{$name}}" name="{{$name}}[]"
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
            <input
                    @if(isset($order)) tabindex="{{$order}}"  @endif
            type="checkbox" class="custom-control-input" name="{{$name}}" id="{{$name}}" value="1"
                    @if(isset($validation['required']) && $validation['required'])required="required"@endif
                    @if(isset($value) and $value == 1) checked="checked"  @endif
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

