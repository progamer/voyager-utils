<div class="row">
    <div class="col-md-12">
        <!-- form start -->
        <form role="form"
              class="form-edit-add"
              action="@if(!is_null($dataTypeContent->getKey())){{ route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) }}@else{{ route('voyager.'.$dataType->slug.'.store') }}@endif"
              method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->
        @if(!is_null($dataTypeContent->getKey()))
            {{ method_field("PUT") }}
        @endif

        <!-- CSRF TOKEN -->
            {{ csrf_field() }}


            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        <!-- Adding / Editing -->
            @php
                $dataTypeRows = $dataType->{(!is_null($dataTypeContent->getKey()) ? 'editRows' : 'addRows' )};
            @endphp

            @foreach($dataTypeRows as $row)


            <!-- GET THE DISPLAY OPTIONS -->
                @php
                    $display_options = isset($row->details->display) ? $row->details->display : NULL;
                @endphp
                @if (isset($row->details->legend) && isset($row->details->legend->text))
                    <legend class="text-{{isset($row->details->legend->align) ? $row->details->legend->align : 'center'}}" style="background-color: {{isset($row->details->legend->bgcolor) ? $row->details->legend->bgcolor : '#f0f0f0'}};padding: 5px;">{{$row->details->legend->text}}</legend>
                @endif
                @if (isset($row->details->formfields_custom))
                    @include('voyager::formfields.custom.' . $row->details->formfields_custom)
                @else
                    <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ isset($display_options->width) ? $display_options->width : 12 }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                        {{ $row->slugify }}
                        <label for="name">{{ $row->display_name }}</label>
                        @include('voyager::multilingual.input-hidden-bread-edit-add')
                        @if($row->type == 'relationship')
                            @include('voyager::formfields.relationship', ['options' => $row->details])
                        @else
                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                        @endif

                        @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                        @endforeach
                    </div>
                @endif
            @endforeach



        </form>
    </div>
</div>