(function ($) {
    /*------- Prevent Default Link Anchor Code --------*/
    if($('a[href="#"]').length) {
        $('a[href="#"]').click(function (e) {
            e.preventDefault();
        });
    }

    /*------- Sidebar Show Code --------*/
    $('.sidebar-toggle-box').on("click", function () {
        $('#container').toggleClass('sidebar-closed');
        return false;
    })
    
    $('#sidebar').on('click', '.sidebar-menu li', function () {
        $(this).addClass('active').parents('.sidebar-menu').find('li').not(this).removeClass('active');
        $(this).find('.sidebar-icon-adjq').toggleClass('minus plus')
            .parents('.sidebar-menu').find('.sidebar-icon-adjq').not($(this).find('.sidebar-icon-adjq')).removeClass('minus').addClass('plus');
        $(this).find('.sidebar-sub').toggleClass('d-none')
            .parents('#sidebar').find('.sidebar-sub').not($(this).find('.sidebar-sub')).addClass('d-none');
    })

    /*------- Scroll Up Code --------*/
    $.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });

    /*------- Confirm Delete Code --------*/
    $('#confirmDelete').on('show.bs.modal', function (e) {
        let dataURL = $(e.relatedTarget).data('href');
        $(e.target).find('.btn.delete').attr('href', dataURL)
    });

})(jQuery)