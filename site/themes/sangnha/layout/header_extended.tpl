    <noscript>
        <div class="alert alert-danger">{LANG.nojs}</div>
    </noscript>
    <header class="sn-top">
        <div class="sn-util">
            <div class="sn-wrap">
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=stores">Hệ thống cửa hàng</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=warranty">Tra cứu bảo hành</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=news">Tin tức</a>
                <span class="sn-util-phone">[Số điện thoại]</span>
                <button type="button" class="sn-login-open" data-open="sn-login">Đăng nhập</button>
            </div>
        </div>
        <div class="sn-bar">
            <div class="sn-wrap sn-bar-row">
                <button type="button" class="sn-burger" aria-label="Mở menu">☰</button>
                <a class="sn-logo" href="{THEME_SITE_HREF}" title="{SITE_NAME}">
                    <img src="{NV_BASE_SITEURL}themes/sangnha/images/logo-sang-nha.svg" alt="{SITE_NAME}">
                    <!-- BEGIN: site_name_h1 --><h1 class="sr-only">{SITE_NAME}</h1><!-- END: site_name_h1 -->
                    <!-- BEGIN: site_name_span --><span class="sr-only">{SITE_NAME}</span><!-- END: site_name_span -->
                </a>
                <form class="sn-search" action="{NV_BASE_SITEURL}index.php" method="get">
                    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                    <input type="hidden" name="{NV_NAME_VARIABLE}" value="shops">
                    <input type="hidden" name="{NV_OP_VARIABLE}" value="search">
                    <input name="q" placeholder="Tìm tủ lạnh, điện thoại, máy lạnh…" autocomplete="off">
                    <button type="submit">Tìm</button>
                </form>
                <a class="sn-icon-link" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=wishlist">Yêu thích</a>
                <a class="sn-cart-link" href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=cart">Giỏ hàng <b>{SN_CART}</b></a>
            </div>
        </div>
        <div class="sn-nav">
            <div class="sn-wrap sn-nav-row">
                <div class="sn-mega">
                    <button type="button">Ngành hàng</button>
                    <div class="sn-mega-panel">{SN_MENU}</div>
                </div>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=flashsale">Flash sale</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=installment">Trả góp</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=tradein">Thu cũ đổi mới</a>
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=stores">Cửa hàng</a>
            </div>
        </div>
        <div class="sn-drawer" hidden>
            <div class="sn-mega-panel">{SN_MENU}</div>
            <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=shops&amp;{NV_OP_VARIABLE}=warranty">Tra cứu đơn / bảo hành</a>
        </div>
    </header>
    <div class="sn-legacy" hidden>
        [CONTACT_DEFAULT][SOCIAL_ICONS][PERSONALAREA][HEAD_RIGHT][MENU_SITE]
    </div>
    <div class="sn-login" id="sn-login" hidden>
        <form method="post" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}=users&amp;{NV_OP_VARIABLE}=login">
            <h2>Đăng nhập</h2>
            <label>Tên hoặc số điện thoại đã đăng ký<input name="nv_login" autocomplete="username"></label>
            <label>Mật khẩu<input type="password" name="nv_password" autocomplete="current-password"></label>
            <button type="submit">Vào tài khoản</button>
            <p>Google: cần khoá ứng dụng do chủ cửa hàng cấp trong quản trị thành viên. Nút này không gửi dữ liệu khi chưa có khoá.</p>
            <button type="button" class="sn-ghost">Tiếp tục với Google</button>
            <button type="button" class="sn-close">Đóng</button>
        </form>
    </div>
    [THEME_ERROR_INFO]
    <div class="section-body sn-body">
        <div class="wraper">
            <section>
                <div class="container" id="body">
                    <nav class="third-nav">
                        <div class="row">
                            <div class="bg">
                            <div class="clearfix">
                                <div class="col-xs-24 col-sm-18 col-md-18">
                                    <!-- BEGIN: breadcrumbs -->
                                    <div class="breadcrumbs-wrap">
                                        <ul class="temp-breadcrumbs hidden" itemscope itemtype="https://schema.org/BreadcrumbList">
                                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="{THEME_SITE_HREF}" itemprop="item" title="{LANG.Home}"><span itemprop="name">{LANG.Home}</span></a><i class="hidden" itemprop="position" content="1"></i></li>
                                            <!-- BEGIN: loop --><li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="{BREADCRUMBS.link}" itemprop="item" title="{BREADCRUMBS.title}"><span class="txt" itemprop="name">{BREADCRUMBS.title}</span></a><i class="hidden" itemprop="position" content="{BREADCRUMBS.position}"></i></li><!-- END: loop -->
                                        </ul>
                                    </div>
                                    <!-- END: breadcrumbs -->
                                    <!-- BEGIN: currenttime --><span class="current-time">{NV_CURRENTTIME}</span><!-- END: currenttime -->
                                </div>
                            </div>
                            </div>
                        </div>
                    </nav>
