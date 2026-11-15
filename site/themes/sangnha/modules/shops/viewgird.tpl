<!-- BEGIN: main -->
<div class="sn-cards shops-viewgrid shops-viewgrid-{MODULE_NAME}">
    <!-- BEGIN: loop -->
    <article class="sn-card">
        <a class="sn-card-media" href="{ROW.link_pro}" title="{ROW.title}">
            <img src="{ROW.homeimgthumb}" alt="{ROW.title}">
            <!-- BEGIN: discounts -->
            <span class="sn-badge">-{PRICE.discount_percent}{PRICE.discount_unit}</span>
            <!-- END: discounts -->
            <!-- BEGIN: new -->
            <span class="sn-badge sn-badge-new">{LANG.newday}</span>
            <!-- END: new -->
        </a>
        <div class="sn-card-body">
            <h3><a href="{ROW.link_pro}">{ROW.title}</a></h3>
            <!-- BEGIN: product_code --><p class="sn-sku">{PRODUCT_CODE}</p><!-- END: product_code -->
            <!-- BEGIN: adminlink --><p class="sn-admin">{ADMINLINK}</p><!-- END: adminlink -->
            <!-- BEGIN: price -->
            <p class="sn-price">
                <!-- BEGIN: discounts -->
                <span class="money">{PRICE.sale_format} {PRICE.unit}</span>
                <span class="discounts_money">{PRICE.price_format} {PRICE.unit}</span>
                <!-- BEGIN: standard --><!-- END: standard -->
                <!-- END: discounts -->
                <!-- BEGIN: no_discounts -->
                <span class="money">{PRICE.price_format} {PRICE.unit}</span>
                <!-- END: no_discounts -->
            </p>
            <!-- END: price -->
            <!-- BEGIN: contact -->
            <p class="sn-price"><span class="money">{LANG.price_contact}</span></p>
            <!-- END: contact -->
            <div class="sn-card-actions">
                <!-- BEGIN: order -->
                <a href="javascript:void(0)" id="{ROW.id}" title="{ROW.title}" onclick="cartorder(this, {GROUP_REQUIE}, '{ROW.link_pro}'); return !1;"><button type="button" class="btn btn-primary btn-xs">{LANG.add_product}</button></a>
                <!-- END: order -->
                <!-- BEGIN: product_empty -->
                <button class="btn btn-danger disabled btn-xs" type="button">{LANG.product_empty}</button>
                <!-- END: product_empty -->
                <!-- BEGIN: wishlist -->
                <button type="button" onclick="wishlist({ROW.id}, this)" class="sn-heart <!-- BEGIN: disabled -->is-on<!-- END: disabled -->" aria-label="{LANG.wishlist}">♡</button>
                <!-- END: wishlist -->
            </div>
            <!-- BEGIN: point --><span class="sn-point" title="{point_note}">+{point}</span><!-- END: point -->
            <!-- BEGIN: gift --><span class="sn-point">{LANG.gift}</span><!-- END: gift -->
            <!-- BEGIN: compare -->
            <label class="sn-compare"><input type="checkbox" value="{ROW.id}" {ch} onclick="nv_compare({ROW.id});" id="compare_{ROW.id}" />{LANG.compare}</label>
            <!-- END: compare -->
            <!-- BEGIN: tooltip_js --><!-- END: tooltip_js -->
        </div>
    </article>
    <!-- END: loop -->
</div>
<!-- BEGIN: page -->
<div class="sn-pager">{PAGE}</div>
<!-- END: page -->
<!-- END: main -->
