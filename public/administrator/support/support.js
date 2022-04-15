(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if ($('#supports-table').length) {
        let urlAjax = $('#supports-table').data('url');
        let columns;
        if (urlAjax.split('data/')[1] == 0) {
            columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'user',
                name: 'user'
            },
            {
                data: 'subject',
                name: 'subject',
                class: 'text-center'
            },
            {
                data: 'require',
                name: 'require'
            },
            {
                data: 'status',
                name: 'status',
                class: 'text-center'
            }];
        } else {
            columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'user',
                name: 'user'
            },
            {
                data: 'subject',
                name: 'subject',
                class: 'text-center'
            },
            {
                data: 'require',
                name: 'require'
            },
            {
                data: 'status',
                name: 'status',
                class: 'text-center'
            },
            {
                data: 'access',
                name: 'access',
                orderable: false,
                searchable: false
            }];
        }
        $('#supports-table').DataTable({
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
            columns: columns
        });
        $(document).on('click', '.btn-support-delete', actionDelete);
    }

    $(document).on('click', '.btn-support-reply', function (e) {
        e.preventDefault();
        let that = $(this);

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
                $.ajax({
                    type: 'GET',
                    url: that.data('href'),
                    dataType: 'json',
                    data: result.value,
                    success: function (data) {
                        Swal.fire(
                            'Đã gửi!',
                            'Phản hồi đã được gửi.',
                            'success'
                        )
                        .then(() => {
                            $('#supports-table').DataTable().ajax.reload();
                        })
                    }
                });
            }
        })
    });

    function actionDelete(e) {
        e.preventDefault();
        let hrefData = $(this).data('href');
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Bạn sẽ không thể lấy lại được dữ liệu!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Chắc chắn, xóa!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: hrefData,
                    dataType: 'json',
                    success: function (data) {
                        Swal.fire(
                            'Đã xóa!',
                            'Yêu cầu đã được xóa.',
                            'success'
                        )
                        $('#supports-table').DataTable().ajax.reload();
                    }
                });
            }
        })
    }
})(jQuery)
