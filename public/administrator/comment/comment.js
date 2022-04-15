(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if ($('#product-comments-table').length) {
        var urlAjax = $('#product-comments-table').data('url');
        $('#product-comments-table').DataTable({
            processing: true,
            responsive: true,
            dom: '<"flex-between"lf>t<"flex-between"ip>',
            language: {
                processing: "<div id='loader'>Đang tải dữ liệu !</div>",
                paginate: {
                    previous: '← Trước',
                    next: 'Sau →'
                },
                infoEmpty: '',
                zeroRecords: 'Không có dữ liệu!',
                search: 'Tìm',
                lengthMenu: '_MENU_ kết quả một trang',
                info: 'Hiển thị _START_ đến _END_ của _TOTAL_ kết quả'
            },
            serverSide: true,
            order: [0, 'desc'],
            ajax: urlAjax,
            columns: [{
                    data: 'stt',
                    name: 'stt'
                },
                {
                    data: 'product',
                    name: 'product'
                },
                {
                    data: 'comments',
                    name: 'comments',
                    class: 'text-center'
                },
                {
                    data: 'rating',
                    name: 'rating',
                    class: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }

    if ($('#news-comments-table').length) {
        var urlAjax = $('#news-comments-table').data('url');
        $('#news-comments-table').DataTable({
            processing: true,
            responsive: true,
            dom: '<"flex-between"lf>t<"flex-between"ip>',
            language: {
                processing: "<div id='loader'>Đang tải dữ liệu !</div>",
                paginate: {
                    previous: '← Trước',
                    next: 'Sau →'
                },
                infoEmpty: '',
                zeroRecords: 'Không có dữ liệu!',
                search: 'Tìm',
                lengthMenu: '_MENU_ kết quả một trang',
                info: 'Hiển thị _START_ đến _END_ của _TOTAL_ kết quả'
            },
            serverSide: true,
            order: [0, 'desc'],
            ajax: urlAjax,
            columns: [{
                    data: 'stt',
                    name: 'stt'
                },
                {
                    data: 'post',
                    name: 'post'
                },
                {
                    data: 'comments',
                    name: 'comments',
                    class: 'text-center'
                },
                {
                    data: 'view',
                    name: 'view',
                    class: 'text-center'
                },
                {
                    data: 'like',
                    name: 'like',
                    class: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }

    $(document).on('click', '.btn-comment-reply', function (e) {
        e.preventDefault();
        let that = $(this);
        let commID = that.parents('tr').data('id');
        let slug = that.parents('tbody').data('item');

        Swal.fire({
            html: '<textarea id="reply" name="reply" rows="5" class="form-control"></textarea> ',
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Hủy',
            confirmButtonText: 'Gửi',
            preConfirm: () => {
                const reply = Swal.getPopup().querySelector('#reply').value;
                if(!reply) {
                    Swal.showValidationMessage(`Hãy nhập nội dung phản hồi.`);
                }
                return { reply: reply}
            }
        }).then((result) => {
            if (result.value) {
                result.value.parent_id = commID;
                $.ajax({
                    type: 'GET',
                    url: document.documentURI.split('admin')[0] + 'admin/comment/' + that.parents('tbody').data('type') + '/' + slug + '/reply',
                    dataType: 'json',
                    data: result.value,
                    success: function (data) {
                        Swal.fire(
                            'Đã phản hồi!',
                            'Bình luận của bạn đã được thêm.',
                            'success'
                        )
                        .then(() => {
                            location.reload()
                        })
                    }
                });
            }
        })
    });

    $(document).on('click', '.btn-comment-delete', actionDelete);

    function actionDelete(e) {
        e.preventDefault();
        let hrefData = $(this).parents('tbody').data('href');
        let id = $(this).parents('tr').data('id');
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Bạn sẽ không lấy lại được dữ liệu!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Hủy',
            confirmButtonText: 'Chắc chắn, xóa!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: hrefData,
                    data: {
                        'id': parseInt(id)
                    },
                    dataType: 'json',
                    success: function (data) {
                        Swal.fire(
                            'Đã xóa!',
                            'Bình luận đã được xóa.',
                            'success'
                        )
                        .then(() => {
                            location.reload();
                        })
                    }
                });
            }
        })
    }
})(jQuery)
