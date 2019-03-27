@php

    $multiple = isset($multiple) ? $multiple : false;

    $value = old($name, null);



    $oldInput = session()->getOldInput();
    if(!empty($oldInput))
    {
        if($type == 'select'){
            $value = old("{$name}_");
        }
        else{
            $value = old($name);
        }
    }
    else{
        if(isset($elq)){

            if(!$multiple ){
                $value = $elq->{$name};
            }
            else{
                $value  = $elq->{$name}->pluck('id')->toArray();
            }
        }
    }


@endphp

@if( $type == 'textarea' )
    <div class="form-group">
        <label for="description">{{ $title }}</label>
        <textarea id="{{$name}}" name="{{$name}}" type="textarea" class="form-control" cols="{{ $col ?? 10 }}" rows="{{ $rows ?? 5 }}" >{{$value}}</textarea>
        @if ($errors->has($name))
            <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
        @endif
    </div>

@elseif( $type == 'select' )



    <div class="form-group">
        <label class="" >{{$title}}</label>
        <select class="form-control" name="{{$name}}@if($multiple) [] @endif" @if($multiple) multiple @endif>

            @if($multiple)
                @foreach($options as $key => $optionTitle)
                    <option value="{{$key}}" @if(in_array($key,$value)) selected @endif>{{$optionTitle}}</option>
                @endforeach
            @else
                @foreach($options as $key => $optionTitle)
                    <option value="{{$key}}" @if($key == $value) selected @endif>{{$optionTitle}}</option>
                @endforeach
            @endif

        </select>
        @if ($errors->has($name))
            <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
        @endif
    </div>

@elseif( $type == 'radiogroup' )

    <div class="input-group">
        <label class="mr-5" >{{$title}}</label>
        @foreach($options as $key => $optionTitle)
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="{{$key}}" value="{{$key}}" name="{{$name}}" class="custom-control-input" @if($key == $value) checked @endif>
                <label class="custom-control-label" for="{{$key}}">{{$optionTitle}}</label>
            </div>
        @endforeach

        @if ($errors->has($name))
            <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
        @endif
    </div>
@else
    <div class="form-group">
        <label for="{{ $name }}">{{ $title }}</label>
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type ?? 'text' }}" class="form-control" value="{{$value}}">

        @if ($errors->has($name))
            <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
        @endif
    </div>

@endif











