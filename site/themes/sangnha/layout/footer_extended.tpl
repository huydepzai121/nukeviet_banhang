                </div>
            </section>
        </div>
    </div>
    <footer class="section-footer-top" id="footer">
        <div class="wraper">
            <div class="container">
                <div class="row">
                    <div class="col-xs-24 col-sm-24 col-md-6">
                        [MENU_FOOTER]
                    </div>
                    <div class="col-xs-24 col-sm-24 col-md-10">
                        [FEATURED_PRODUCT]
                    </div>
                    <div class="col-xs-24 col-sm-24 col-md-8">
                        [COMPANY_INFO]
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24 col-sm-24 col-md-8">
                        <div class="qr-code-wraper">
                            [QR_CODE]
                        </div>
                        <div id="ftip" data-content="">
                            <div class="bg"></div>
                        </div>
                    </div>
                    <div class="col-xs-24 col-sm-24 col-md-16">
                        [FOOTER_SITE]
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <footer class="sn-foot">
        <div class="sn-wrap sn-foot-grid">
            <div>
                <img src="{NV_BASE_SITEURL}themes/sangnha/images/logo-sang-nha-dark.svg" alt="Sáng Nhà">
                <p>[Địa chỉ]</p>
                <p>[Số điện thoại]</p>
                <p>[Email]</p>
            </div>
            <div>
                <h3>Hỗ trợ</h3>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=warranty">Bảo hành</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=stores">Cửa hàng</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=contact">Liên hệ</a>
            </div>
            <div>
                <h3>Mua hàng</h3>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=flashsale">Flash sale</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=installment">Trả góp</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=tradein">Thu cũ đổi mới</a>
            </div>
        </div>
        <p class="sn-copy">© Sáng Nhà. Giá và ảnh là dữ liệu mẫu. [Cam kết 1] [Cam kết 2]</p>
    </footer>
    <script>
    document.querySelectorAll('.sn-burger').forEach(function (b) {
        b.addEventListener('click', function () {
            var d = document.querySelector('.sn-drawer');
            if (d) d.hidden = !d.hidden;
        });
    });
    document.querySelectorAll('[data-open]').forEach(function (b) {
        b.addEventListener('click', function () {
            var box = document.getElementById(b.getAttribute('data-open'));
            if (box) box.hidden = false;
        });
    });
    document.querySelectorAll('.sn-close').forEach(function (b) {
        b.addEventListener('click', function () { b.closest('.sn-login').hidden = true; });
    });
    function snTick() {
        document.querySelectorAll('.sn-count').forEach(function (el) {
            var left = Math.max(0, (el.getAttribute('data-end') | 0) - Math.floor(Date.now() / 1000));
            var h = String(Math.floor(left / 3600)).padStart(2, '0');
            var m = String(Math.floor((left % 3600) / 60)).padStart(2, '0');
            var s = String(left % 60).padStart(2, '0');
            var t = el.querySelector('.sn-count-text');
            if (t) t.textContent = h + ':' + m + ':' + s;
        });
    }
    snTick();
    setInterval(snTick, 1000);
    </script>
    <nav class="sn-tabbar">
        <a href="{THEME_SITE_HREF}">Trang chủ</a>
        <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops">Ngành hàng</a>
        <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=flashsale">Flash sale</a>
        <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=cart">Giỏ</a>
        <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=users">Tài khoản</a>
    </nav>
    <nav class="section-footer-bottom footerNav2">
        <div class="wraper">
            <div class="container">
                <!-- BEGIN: theme_type -->
                <div class="theme-change">
                <!-- BEGIN: loop -->
                    <!-- BEGIN: other -->
                    <a href="{STHEME_TYPE}" rel="nofollow" title="{STHEME_INFO}"><i class="fa fa-{STHEME_ICON}"></i></a>
                    <!-- END: other -->
                    <!-- BEGIN: current -->
                    <span title="{LANG.theme_type_select}: {STHEME_TITLE}"><i class="fa fa-{STHEME_ICON}"></i></span>
                    <!-- END: current -->
                <!-- END: loop -->
                </div>
                <!-- END: theme_type -->
                <div class="bttop">
                    <a class="pointer"><i class="fa fa-eject fa-lg"></i></a>
                </div>
            </div>
        </div>
    </nav>
    {ADMINTOOLBAR}
    <!-- SiteModal Required!!! -->
    <div id="sitemodal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
                <button type="button" class="close" data-dismiss="modal"><span class="fa fa-times"></span></button>
            </div>
        </div>
    </div>
    <div class="fix_banner_left">
        [FIX_BANNER_LEFT]
    </div>
    <div class="fix_banner_right">
        [FIX_BANNER_RIGHT]
    </div>
