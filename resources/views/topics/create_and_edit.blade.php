@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div class="card ">

                <div class="card-body">
                    <h2 class="">
                        <i class="far fa-edit"></i>
                        @if ($topic->id)
                            编辑话题
                        @else
                            新建话题
                        @endif
                    </h2>

                    <hr>

                    @if ($topic->id)
                        <form action="{{ route('topics.update', $topic->id) }}" method="POST" accept-charset="UTF-8">
                            <input type="hidden" name="_method" value="PUT">
                            @else
                                <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">
                                    @endif

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    @include('shared._error')

                                    <div class="mb-3">
                                        <label for="title"></label>
                                        <input class="form-control" type="text" id="title" name="title"
                                               value="{{ old('title', $topic->title) }}"
                                               placeholder="请填写标题" required/>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id"></label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            <option value="" hidden disabled selected>请选择分类</option>
                                            @foreach ($categories as $value)
                                                <option value="{{ $value->id }}" {{ $topic->category_id == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="editor"></label>
                                        <textarea name="body" class="form-control" id="editor" rows="6"
                                                  placeholder="请填入至少三个字符的内容。"
                                                  required>{{ old('body', $topic->body) }}</textarea>
                                    </div>

                                    <div class="well well-sm">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="far fa-save mr-2" aria-hidden="true"></i> 保存
                                        </button>
                                    </div>
                                </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>

    <script>
        $(document).ready(function () {
            let editor = new Simditor({
                textarea: $('#editor'),
                upload: {
                    url: '{{ route('topics.upload_image') }}', // 文件上传的接口地址
                    params: { // params 表单提交的参数列表
                        _token: '{{ csrf_token() }}' // Laravel 的 CSRF-TOKEN
                    },
                    fileKey: 'upload_file', // 文件上传的参数名，这个参数会是后端接受文件的参数
                    connectionCount: 3, // 最多只能同时上传 3 个文件
                    leaveConfirm: '文件上传中，关闭此页面将取消上传。' // 上传过程中，用户关闭页面的提示
                },
                pasteImage: true, // 设定是否支持图片粘贴上传
            });
        });
    </script>
@stop
