(function ($) {
    'use strict';
    var baseUrl = $('meta[name="base-url"]')[0].content

    $(document).on('click', '[href="#"]', function (e) {
        e.preventDefault();
    })

    function format(n) {
        return n.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,")
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if (document.documentURI.match('#login_account')) {
        $('#login_account').toggleClass('is-visiable');
    }

    /*-----------------------------------------------
    1.0 Search Bar
    -----------------------------------------------*/
    $('.search-btn-open').on("click", function () {
        $('body').toggleClass('search-box-show');
        return false;
    })

    $('.search-btn-close').on("click", function () {
        $('body').toggleClass('search-box-show');
        return false;
    })

    /*-----------------------------------------------
    2.0 Cart Box
    -----------------------------------------------*/
    $('.cart-btn-open').on("click", function () {
        $('.shopping-cart').addClass('shopping-cart-on');
        $('.body-overlay').addClass('is-visible');
    })

    $('.cart-btn-close').on("click", function () {
        $('.shopping-cart').removeClass('shopping-cart-on');
        $('.body-overlay').removeClass('is-visible');
    })

    $('.body-overlay').on("click", function () {
        $(this).removeClass('is-visible');
        $('.shopping-cart').removeClass('shopping-cart-on');
    })

    /*-----------------------------------------------
    3.0 Scroll Up && Sroll Header
    -----------------------------------------------*/
    $.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });

    var win = $(window);
    var sticky_id = $("#header-area");
    win.on('scroll', function () {
        var scroll = win.scrollTop();
        if (scroll < 245) {
            sticky_id.removeClass("scroll-header");
        } else {
            sticky_id.addClass("scroll-header");
        }
    });

    /*-----------------------------------------------
    4.0 Mean Menu
    -----------------------------------------------*/
    $('.mobile-icon').on("click", function () {
        $('.mobile-icon i').toggleClass('fa-bars fa-times');
        $('#mobile-menu .nav').toggleClass('d-block');
    })

    /*-----------------------------------------------
    5.0 Limit Line Code
    -----------------------------------------------*/
    if ($('.limit-line').length) {
        $('.limit-line').each(function (i, e) {
            let lineHeight = parseFloat($(e).css('lineHeight').split('px')[0]);
            $(e).css('height', lineHeight * 4)
        })
    }

    /*-----------------------------------------------
    6.0 Login Account Code
    -----------------------------------------------*/
    $('.btn-open-login').on("click", function () {
        $('#login_account').toggleClass('is-visiable');
        return false;
    })

    $('#login_account .btn-close-login').on("click", function () {
        $('#login_account').toggleClass('is-visiable');
        return false;
    })

    $('#login_account form').submit(function (e) {
        e.preventDefault();
        let that = $(this);
        let urlPost = that.data('action');
        $.ajax({
            type: 'post',
            url: urlPost,
            dataType: 'json',
            data: that.serialize(),
            success: function (data) {
                that.find('.alert-danger').removeClass('alert-danger');
                that.find('.error').remove();
                window.location.replace(document.documentURI.split('#login_account')[0])
            },
            error: function (respon) {
                if (respon.status === 422) {
                    let errors = respon.responseJSON.errors;
                    that.find('[name]').each(function (ind, elem) {
                        if (errors[elem.name]) {
                            $(elem).parents('.form-group').find('.error').remove();
                            $(elem).addClass('alert-danger').parents('.form-group').append('<div class="error">' + errors[elem.name] + '</div>');
                        } else {
                            $(elem).parents('.form-group').find('.error').remove();
                            $(elem).removeClass('alert-danger')
                        }
                    });
                }
            }
        })
    })

    /*-----------------------------------------------
    7.0 Product Add Related, Compare Function Code
    -----------------------------------------------*/
    function addLocalStorage(btnSelector, dataName, parentSelect, imgSelector, localName, countLimit, viewFunc) {
        $(document).on('click', btnSelector, function (e) {
            let that = $(this);
            let inforProduct = that.data(dataName);
            if (that.is($('.product-name'))) {
                inforProduct.name = that.text();
            } else {
                inforProduct.name = that.parents(parentSelect).find('.product-name').text();
            }
            inforProduct.image_path = that.parents(parentSelect).find(imgSelector).attr('src');
            inforProduct.url = that.attr('href');

            let old_related = localStorage.getItem(localName) === null ? [] : JSON.parse(localStorage.getItem(localName));

            let idMatches = $.grep(old_related, function (obj) {
                return obj.id === inforProduct.id;
            });

            if (old_related.length <= countLimit) {
                if (!idMatches.length) {
                    old_related.push(inforProduct);
                    localStorage.setItem(localName, JSON.stringify(old_related));
                }
            }
            viewFunc()
        })
    }

    addLocalStorage('.prd-item-action .btn-add-compare', 'info', '.product-item', '.prd-item-thumb img', 'asbab_compare', 4, viewCompare)


    viewCompare()

    function viewCompare() {
        if ($('.compare-list').length) {
            let compares = $(JSON.parse(localStorage.getItem('asbab_compare')));
            let itemprels = '';
            let lastel = `<li><a href="#" class="remove-compare-all">Clear all</a><a href="${ compares[0].baseUrl + '/compare/' }">Compare</a></li>`;
            if (compares.length == 0) {
                $('.compare-list').children().remove();
                $('.compare-list').prepend('<li>No have product to compare !</li>');
            } else {
                compares.each(function (i, com) {
                    itemprels += `<li class="compare-group-local"><a href="#">${ com.name }</a><a data-id="${com.id}" href="#" class="remove-compare-item"><i class="fa fa-trash"></i></a></li>`;
                });
                $('.compare-list').children().remove();
                $('.compare-list').prepend(itemprels).append(lastel);
            }
        }
    }

    $(document).on('click', '.remove-compare-item', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let old_compare = localStorage.getItem('asbab_compare') === null ? [] : JSON.parse(localStorage.getItem('asbab_compare'));
        let new_compare = $.grep(old_compare, function (el) {
            if (el.id !== id) {
                return el;
            }
        })

        localStorage.setItem('asbab_compare', JSON.stringify(new_compare));

        $(this).parents('.compare-group-local').remove()
    });

    $(document).on('click', '.widget-compare .remove-compare-all', function (e) {
        e.preventDefault();
        $(this).parents('ul.compare-list').children().remove();
        $('.widget-compare ul.compare-list').append('<li>No have product to compare !</li>');
        localStorage.removeItem('asbab_compare');
    });

    $(document).on('click', '.btn-add-wishlist', function (e) {
        e.preventDefault();
        let that = $(this);
        $.ajax({
            type: "get",
            url: baseUrl + '/wishlist/add',
            data: that.data('info'),
            dataType: "json",
            success: function (data) {
                Swal.fire(
                    'Added',
                    'Product has been added on Wishlist.',
                    'success'
                )
            }
        });
    });

    /*-----------------------------------------------
    8.0 Add/Remove Cart Ajax Function Code
    -----------------------------------------------*/
    $(document).on('click', '.btn-add-cart', function (e) {
        e.preventDefault();
        let url = $(this).data('url');
        let quantity;
        let quantityEl = $(this).parents('form').find('[name="prd_qtt"]');
        if (quantityEl.length) {
            quantity = parseInt(quantityEl.val());
        } else {
            quantity = 1;
        }
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data: {
                'quantity': quantity
            },
            success: function (data) {
                if (data.code === 200) {
                    $('#header-area .top-head-right .badge').text(data.carts.length);
                    $('#header-area .shopping-cart .shopping-cart-wrap').children().remove();
                    let cartItems = '';
                    let cartTotalPrice = 0;
                    $.grep(data.carts, function (c) {
                        cartItems += `<div class="shp-single-prd cart-product-item">
                                            <div class="shp-prd-infor d-flex">
                                                <div class="shp-prd-thumb">
                                                    <a href="#"><img src="${ c.image_path }" alt="" /></a>
                                                </div>
                                                <div class="shp-prd-details">
                                                    <a href="#">${ c.name }</a>
                                                    <span class="quantity">QTY: ${ c.quantity }</span>
                                                    <span class="shp-price">$${ format(c.price) }</span>
                                                </div>
                                            </div>
                                            <div class="prd-btn-remove">
                                                <a data-href="${ data.baseUrl + '/cart/removecart/' + c.id }" class="btn-remove-cart"><i
                                                        class="fa fa-times"></i></a>
                                            </div>
                                        </div>`;
                        cartTotalPrice += c.quantity * c.price;
                    })
                    $('#header-area .shopping-cart .shopping-cart-wrap').append(cartItems);
                    $('#header-area .shopping-cart .total-price').text('$' + format(cartTotalPrice))
                    Swal.fire(
                        'Added!',
                        'Your product has been added.',
                        'success'
                    )
                }
            }
        });
    });

    $(document).on('click', '.btn-remove-cart', function (e) {
        e.preventDefault();
        let url = $(this).data('href');
        let that = $(this);
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            success: function (data) {
                if (data.code === 200) {
                    $('#header-area .top-head-right .badge').text(data.carts.length);
                    that.parents('.cart-product-item').remove();
                    if(data.carts.length === 0) {
                        $('#header-area .shopping-cart-wrap').append('<div class="shp-single-prd cart-product-item text-center">No have product on cart !</div>')
                    }
                    Swal.fire(
                        'Removed!',
                        'Your product has been removed.',
                        'success'
                    ).then(() => {
                        location.reload()
                    })
                }
            }
        });
    });

})(jQuery);
