@extends('admin.layout.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.css') }}"/>
@endsection

@section('js')
    <script src="{{ asset('administrator/assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('administrator/plugins.js') }}"></script>
    <script src="{{ asset('administrator/common.js') }}"></script>
    <script src="{{ asset('administrator/comment/comment.js') }}"></script>
@endsection

@section('content')
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="breadcrumb">
                        <li class="breadcumb-item"><a href="{{ route('admin') }}"><i class="fa fa-home"></i> Trang
                                chủ</a></li>
                        <li>Bình luận</li>
                        <li>Sản phẩm</li>
                        <li class="active">{{ $product->name }}</li>
                    </ul>
                </div>
            </div>

            <section class="panel">
                <div class="panel-body">
                    <div class="adv-table">
                        <table class="table table-bordered table-middle table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Người dùng</th>
                                <th>Nội dung</th>
                                @if(auth()->user()->Can('trả lời bình luận') || auth()->user()->Can('xóa bình luận'))
                                    <th class="text-center">Hành động</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody data-item="{{ $product->slug }}" data-type="product"
                                   data-href="{{ route('admin.comment.product.delete', ['slug' => $product->slug]) }}">
                            @if ($comments->count() > 0)
                                @foreach ($comments->where('parent_id', 0) as $test => $comm)
                                    <tr data-id="{{ $comm->id }}">
                                        <td class="tab-icon"><i class="fa fa-plus-square-o"></i></td>
                                        <td>
                                            @if ($comm->user_id !== 0)
                                                {{ $comm->users->name }}<br>
                                                {{ $comm->users->email }}
                                            @else
                                                {!! '<span class="text-success">Asbab Furniture</span>' !!}
                                            @endif
                                        </td>
                                        <td class="text-justify">{!! $comm->comment !!}</td>
                                        @if(auth()->user()->Can('reply comment') || auth()->user()->Can('delete comment'))
                                            <td class="text-center">
                                                @can('reply comment')
                                                    <a href="#" class="btn btn-small btn-primary btn-comment-reply">Trả lời</a>
                                                @endcan
                                                @can('delete comment')
                                                    <a href="#" class="btn btn-small btn-danger btn-comment-delete">Xóa</a>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                    @foreach ($comments->where('parent_id', '>', 0) as $key => $child)
                                        @if (id_parent($child->parent_id, $comments, 'parent_id', 'id') == $comm->id)
                                            <tr data-id="{{ $child->id }}" class="sub-border-dashed @if ($comments->where('parent_id', '>', 0)->count() == $key +
                                                    1) {{ 'sub-end' }} @endif">
                                                <td class="tab-icon">
                                                    <div class="line-box-folder">
                                                        <span class="line-vertical"></span>
                                                        <span class="line-horizontal"></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($child->user_id !== 0)
                                                        {{ $child->users->name }}<br>
                                                        {{ $child->users->email }}
                                                    @else
                                                        {!! '<span class="text-success">Asbab Furniture</span>' !!}
                                                    @endif
                                                </td>
                                                <td class="text-justify"><span
                                                        class="text-danger weight-700 mr-10">{{ '@' . $child->reply_for_user_name }}</span>{!! $child->comment !!}
                                                </td>
                                                @if(auth()->user()->Can('trả lời bình luận') || auth()->user()->Can('xóa bình luận'))
                                                    <td class="text-center">
                                                        @can('trả lời bình luận')
                                                            <a href="#" class="text-primary btn-comment-reply">Trả lời</a>
                                                        @endcan|
                                                        @can('xóa bình luận')
                                                            <a href="#"
                                                               class="text-danger btn-comment-delete">Xóa</a>
                                                        @endcan
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    @if ($comments->count() > 0)
                        <div class="row items-center">
                            <div class="col-lg-6">
                                <div class="data-info">Hiển thị {{ $comments->firstItem() }} đến
                                    {{ $comments->lastItem() }}
                                    của {{ $comments->total() }} kết quả
                                </div>
                            </div>
                            <div class="col-lg-6 flex-end">
                                {{ $comments->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </section>
    </section>
@endsection
