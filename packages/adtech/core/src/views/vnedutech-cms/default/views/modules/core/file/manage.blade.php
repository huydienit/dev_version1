@extends('layouts.default')

{{-- Page title --}}
@section('title'){{ $title = trans('adtech-core::titles.file.manage') }}@stop

{{-- page level styles --}}
@section('header_styles')

@stop


{{-- Page content --}}
@section('content')
    <section class="content-header">
        <h1>{{ $title }}</h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('backend.homepage') }}"> <i class="livicon" data-name="home" data-size="16"
                                                             data-color="#000"></i>
                    {{ trans('adtech-core::labels.home') }}
                </a>
            </li>
            <li class="active"><a href="#">{{ $title }}</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content paddingleft_right15">
        {{--<div class="container">--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<h2>CKEditor</h2>--}}
                    {{--<textarea name="ce" class="form-control"></textarea>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <iframe src="{{ route('adtech.core.file.manager') }}" width="100%" height="800px" frameborder="0"></iframe>
    </section>
@stop

@section('footer_scripts')
    {{--<script>--}}
        {{--var route_prefix = "{{ url(config('lfm.url_prefix', config('lfm.prefix'))) }}";--}}
    {{--</script>--}}

    <!-- CKEditor init -->
    {{--<script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/ckeditor/js/ckeditor.js') }}"></script>--}}
    {{--<script type="text/javascript" src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/vendors/ckeditor/js/adapters.jquery.js') }}"></script>--}}
    {{--<script>--}}
        {{--$('textarea[name=ce]').ckeditor({--}}
            {{--height: 100,--}}
            {{--filebrowserImageBrowseUrl: route_prefix + '?type=Files',--}}
            {{--filebrowserImageUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}',--}}
            {{--filebrowserBrowseUrl: route_prefix + '?type=Files',--}}
            {{--filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'--}}
        {{--});--}}
    {{--</script>--}}
@stop