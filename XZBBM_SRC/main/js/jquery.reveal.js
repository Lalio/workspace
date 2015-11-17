
(function ($) {

    /*---------------------------
    Defaults for Reveal
    ----------------------------*/

    /*---------------------------
    Listener for data-reveal-id attributes
    ----------------------------*/

    /*添加一级*/
    $('a[data-reveal-id_one]').live('click', function (e) {
        e.preventDefault();
        var modalLocation = $(this).attr('data-reveal-id_one');
        $('#' + modalLocation).reveal($(this).data());
    });

    /*修改一、二级 添加二级*/
    $('a[data-reveal-id_tow]').live('click', function (e) {
        e.preventDefault();

        $("#" + $(this).attr('data-reveal-id_tow')).children("a").attr("id", $(this).attr("flag_id")); /*获取二级对应的一级代码*/
        $("#" + $(this).attr('data-reveal-id_tow')).children(".pop_text").attr("value", $(this).attr("input_content")); //回填编辑内容

        var modalLocation = $(this).attr('data-reveal-id_tow');
        $('#' + modalLocation).reveal($(this).data());

        $(".pop_text").select(); //选中文本框
    });
    /*修改支出快记模板*/
    $('a[data-reveal-id_template]').live('click', function (e) {
        e.preventDefault();

        $("#" + $(this).attr('data-reveal-id_template')).children(".close-reveal-modal").attr("id", $(this).attr("flag_id"));
        var arry = $(this).attr('update_info').split("!");
        if ($(this).attr('data-reveal-id_template') == "zc_template") {
            $("#txt_zc_tempName_update").val(arry[0]);
            $("#txt_zc_typeName_update").val(arry[1]);
            $("#txt_zc_accountName_update").val(arry[2]);
            $("#txt_zc_je_update").val(arry[3]);
            $("#txt_zc_proName_update").val(arry[4]);
            $("#txt_zc_shangjiaName_update").val(arry[5]);
            $("#txt_zc_chengyuanName_update").val(arry[6]);

        }
        else {
            $("#txt_sr_tempName_update").val(arry[0]);
            $("#txt_sr_typeName_update").val(arry[1]);
            $("#txt_sr_accountName_update").val(arry[2]);
            $("#txt_sr_je_update").val(arry[3]);
            $("#txt_sr_proName_update").val(arry[4]);

            $("#txt_sr_chengyuanName_update").val(arry[6]);

        }
        var modalLocation = $(this).attr('data-reveal-id_template');
        $('#' + modalLocation).reveal($(this).data());

    });

    /*删除一级 二级*/
    $('a[data-reveal-id_three]').live('click', function (e) {
        e.preventDefault();

        if ($(this).attr("delete_flag") == "True") {

            //            alert('error');
        }
        else {
            $("#" + $(this).attr('data-reveal-id_three')).children("a").attr("id", $(this).attr("flag_id")); /*获取二级对应的一级代码*/
            var modalLocation = $(this).attr('data-reveal-id_three');
            $('#' + modalLocation).reveal($(this).data());

        }
    });


    /*页面只有一个上传（普通上传）*/
    $('a[data-reveal-id_upload]').live('click', function (e) {
        e.preventDefault();
        $("#" + $(this).attr('data-reveal-id_upload')).children("a").attr("id", $(this).attr("image_id")); /*获取IMG元素ID*/


        var modalLocation = $(this).attr('data-reveal-id_upload');
        $('#' + modalLocation).reveal($(this).data());


    });
    /*切换选项卡时的上传*/
    $('a[data-reveal-tab_upload]').live('click', function (e) {
        e.preventDefault();
        $("#" + $(this).attr('data-reveal-tab_upload')).children("a").attr("id", $(this).attr("upload_flag")); /*根据 upload_flag 区别上传附件元素*/
        var modalLocation = $(this).attr('data-reveal-tab_upload');
        $('#' + modalLocation).reveal($(this).data());

    });
    /*修改用户密码*/
    $('sap[data-reveal-id_upload]').live('click', function (e) {
        e.preventDefault();

        var modalLocation = $(this).attr('data-reveal-id_upload');
        $('#' + modalLocation).reveal($(this).data());

    });

    /*固定资产 删除*/
    $('input[data-reveal-id_delete]').live('click', function (e) {
        e.preventDefault();
        $("#" + $(this).attr('data-reveal-id_delete')).children("a").attr("id", $(this).attr("flag_id")); /*获取二级对应的一级代码*/
        var modalLocation = $(this).attr('data-reveal-id_delete');
        $('#' + modalLocation).reveal($(this).data());

    });

    /*复制预算金额*/
    $('a[data-reveal-copy]').live('click', function (e) {
        e.preventDefault();
        $("#" + $(this).attr('data-reveal-copy')).children("a").attr("id", $(this).attr("copy_flag"));
        var modalLocation = $(this).attr('data-reveal-copy');
        $('#' + modalLocation).reveal($(this).data());

    });

    /*帐户弹出窗口*/
    $('a[data-reveal-account_pop]').live('click', function (e) {
        e.preventDefault();

        var d = new Date()
        var vYear = d.getFullYear()
        var vMon = d.getMonth() + 1
        var vDay = d.getDate()

        var id = $(this).attr("flag_id").split(";")[0];
        var name = $(this).attr("flag_id").split(";")[1];
        var ye = $(this).attr("flag_id").split(";")[2];
        var zhsyzt = $(this).attr("flag_id").split(";")[3];

        if (zhsyzt == "0") {

            $("#" + $(this).attr('data-reveal-account_pop')).children("#account_name").append($.trim(name));
            $("#" + $(this).attr('data-reveal-account_pop')).children("#account_ye").append($.trim(ye));
            $("#" + $(this).attr('data-reveal-account_pop')).children().find("#input_zxje").val($.trim(ye));
            $("#" + $(this).attr('data-reveal-account_pop')).children("#account_date").append(vYear + "-" + (vMon < 10 ? "0" + vMon : vMon) + "-" + (vDay < 10 ? "0" + vDay : vDay));



            $("#" + $(this).attr('data-reveal-account_pop')).children("a").attr("id", id);
            var modalLocation = $(this).attr('data-reveal-account_pop');
            $('#' + modalLocation).reveal($(this).data());
        }


    });




    /*---------------------------
    Extend and Execute
    ----------------------------*/

    $.fn.reveal = function (options) {


        var defaults = {
            animation: 'fadeAndPop', //fade, fadeAndPop, none
            animationspeed: 300, //how fast animtions are
            closeonbackgroundclick: true, //if you click background will modal close?
            dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
        };

        //Extend dem' options
        var options = $.extend({}, defaults, options);

        return this.each(function () {

            /*---------------------------
            Global Variables
            ----------------------------*/
            var modal = $(this),
        		topMeasure = parseInt(modal.css('top')),
				topOffset = modal.height() + topMeasure,
          		locked = false,
				modalBG = $('.reveal-modal-bg');

            /*---------------------------
            Create Modal BG
            ----------------------------*/
            if (modalBG.length == 0) {
                modalBG = $('<div class="reveal-modal-bg" />').insertAfter(modal);
            }

            /*---------------------------
            Open & Close Animations
            ----------------------------*/
            //Entrance Animations
            modal.bind('reveal:open', function () {
                modalBG.unbind('click.modalEvent');
                $('.' + options.dismissmodalclass).unbind('click.modalEvent');
                if (!locked) {
                    lockModal();
                    if (options.animation == "fadeAndPop") {
                        //                        modal.css({ 'top': $(document).scrollTop() - topOffset, 'opacity': 0, 'visibility': 'visible' });
                        //                        modalBG.fadeIn(options.animationspeed / 2);
                        //                        modal.delay(options.animationspeed / 2).animate({
                        //                            "top": $(document).scrollTop() + topMeasure + 'px',
                        //                            "opacity": 1
                        //                        }, options.animationspeed, unlockModal());


                        modal.css({ 'opacity': 0, 'visibility': 'visible', 'top': $(document).scrollTop() + topMeasure });
                        modalBG.fadeIn(options.animationspeed / 2);
                        modal.delay(options.animationspeed / 2).animate({
                            "opacity": 1
                        }, options.animationspeed, unlockModal());
                    }
                    if (options.animation == "fade") {
                        modal.css({ 'opacity': 0, 'visibility': 'visible', 'top': $(document).scrollTop() + topMeasure });
                        modalBG.fadeIn(options.animationspeed / 2);
                        modal.delay(options.animationspeed / 2).animate({
                            "opacity": 1
                        }, options.animationspeed, unlockModal());
                    }
                    if (options.animation == "none") {
                        //                        modal.css({ 'visibility': 'visible', 'top': $(document).scrollTop() + topMeasure });
                        //                        modalBG.css({ "display": "block" });
                        //                        unlockModal()


                        modal.css({ 'opacity': 0, 'visibility': 'visible', 'top': $(document).scrollTop() + topMeasure });
                        modalBG.fadeIn(options.animationspeed / 2);
                        modal.delay(options.animationspeed / 2).animate({
                            "opacity": 1
                        }, options.animationspeed, unlockModal());
                    }
                }
                modal.unbind('reveal:open');
            });

            //Closing Animation
            modal.bind('reveal:close', function () {
                if (!locked) {
                    lockModal();
                    if (options.animation == "fadeAndPop") {
                        //                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        //                        modal.animate({
                        //                            "top": $(document).scrollTop() - topOffset + 'px',
                        //                            "opacity": 0
                        //                        }, options.animationspeed / 2, function () {
                        //                            modal.css({ 'top': topMeasure, 'opacity': 1, 'visibility': 'hidden' });
                        //                            unlockModal();
                        //                        });

                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        modal.animate({
                            "opacity": 0
                        }, options.animationspeed, function () {
                            modal.css({ 'opacity': 1, 'visibility': 'hidden', 'top': topMeasure });
                            unlockModal();
                        });
                    }
                    if (options.animation == "fade") {
                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        modal.animate({
                            "opacity": 0
                        }, options.animationspeed, function () {
                            modal.css({ 'opacity': 1, 'visibility': 'hidden', 'top': topMeasure });
                            unlockModal();
                        });
                    }
                    if (options.animation == "none") {
                        //                        modal.css({ 'visibility': 'hidden', 'top': topMeasure });
                        //                        modalBG.css({ 'display': 'none' });

                        modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
                        modal.animate({
                            "opacity": 0
                        }, options.animationspeed, function () {
                            modal.css({ 'opacity': 1, 'visibility': 'hidden', 'top': topMeasure });
                            unlockModal();
                        });
                    }
                }
                modal.unbind('reveal:close');
            });

            /*---------------------------
            Open and add Closing Listeners
            ----------------------------*/
            //Open Modal Immediately
            modal.trigger('reveal:open')

            //Close Modal Listeners
            var closeButton = $('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
                modal.trigger('reveal:close')
            });

            if (options.closeonbackgroundclick) {
                modalBG.css({ "cursor": "pointer" })
                modalBG.bind('click.modalEvent', function () {
                    modal.trigger('reveal:close')
                });
            }
            $('body').keyup(function (e) {
                if (e.which === 27) { modal.trigger('reveal:close'); } // 27 is the keycode for the Escape key
            });


            /*---------------------------
            Animations Locks
            ----------------------------*/
            function unlockModal() {
                locked = false;
            }
            function lockModal() {
                locked = true;
            }

        }); //each call
    } //orbit plugin call
})(jQuery);
        
