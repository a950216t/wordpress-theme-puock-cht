<?php

if (pk_is_checked('post_poster_open')) {
    pk_ajax_register('pk_poster', 'pk_poster_page_callback', true);
}
function pk_poster_page_callback()
{
    $id = $_REQUEST['id'];
    if (empty($id)) {
        wp_die(sprintf(__('無效的文章 ID: %s', PUOCK), $id));
    }
    $post = get_post($id);
    if (empty($post)) {
        wp_die(sprintf(__('無效的文章 ID: %s', PUOCK), $id));
    }
    setup_postdata($post);
    $title = get_the_title($post);
    $qrcode_url = PUOCK_ABS_URI . pk_post_qrcode(get_permalink($post));
    $el_id = 'post-poster-main-' . $post->ID;
    ?>

    <div class="post-poster">
        <div class="post-poster-main" id="<?php echo $el_id; ?>">
            <div class="cover">
                <img crossOrigin="anonymous" src="<?php echo pk_get_img_thumbnail_src(get_post_images($post),640,320) ?>" alt="poster">
            </div>
            <div class="content">
                <p class="title mt20 fs16"><?php echo $title ?></p>
                <p class="excerpt text-3line fs14 mt20 c-sub"><?php echo get_the_excerpt() ?></p>
                <div class="info mt20">
                    <img class="qrcode" src="<?php echo $qrcode_url ?>" alt="<?php echo $title ?>">
                    <?php if (!pk_is_checked('on_txt_logo') || empty(pk_get_option('light_logo'))): ?>
                        <img class="logo" src="<?php echo pk_get_option('light_logo') ?>" alt="logo">
                    <?php else: ?>
                        <p class="tip c-sub fs14">@<?php echo pk_get_web_title() ?></p>
                    <?php endif; ?>
                </div>
                <p class="tip c-sub fs12 mt20 p-flex-center"><i class="fa-solid fa-qrcode"></i>&nbsp;<?php _e('長按識別 QRCode 檢視文章內容', PUOCK) ?></p>
            </div>
        </div>
    </div>
    <!--    <div class="mt20 d-flex justify-content-center">-->
    <!--        <div class="btn btn-primary btn-sm"><i class="fa fa-download"></i> 下載海報</div>-->
    <!--    </div>-->
    <script>
        $(function () {
            const loadingId = window.Puock.startLoading();
            const rootSelector = "#<?php echo $el_id; ?>";
            const rootEl = document.querySelector(rootSelector);

            const waitForImages = (node) => {
                if (!node) return Promise.resolve();
                const imgs = Array.from(node.querySelectorAll('img'));
                // 預先設定 crossOrigin，避免已載入的圖片污染 canvas
                imgs.forEach(img => {
                    if (!img.crossOrigin) img.crossOrigin = 'anonymous';
                });
                const tasks = imgs.map(img => img.complete ? Promise.resolve() : new Promise(resolve => {
                    img.addEventListener('load', resolve, {once: true});
                    img.addEventListener('error', resolve, {once: true});
                }));
                return Promise.all(tasks);
            };

            const waitForFonts = async () => {
                if (document.fonts && document.fonts.ready) {
                    await document.fonts.ready;
                }
                // 字型 ready 後再給一點緩衝，避免字型 fallback -> 目標字型切換時的佈局抖動
                await new Promise(resolve => setTimeout(resolve, 150));
            };

            const settleLayout = async () => {
                // 兩幀保證最新佈局
                await new Promise(resolve => requestAnimationFrame(() => requestAnimationFrame(resolve)));
                // 再小延時，等字型重排完成
                await new Promise(resolve => setTimeout(resolve, 80));
            };

            (async () => {
                try {
                    if (!rootEl) {
                        throw new Error('未找到海報容器');
                    }

                    await Promise.all([waitForImages(rootEl), waitForFonts()]);

                    // 確保佈局穩定後再截圖（圖片、字型、佈局都穩定）
                    await settleLayout();

                    const canvas = await html2canvas(rootEl, {
                        allowTaint: true,
                        useCORS: true,
                        backgroundColor: '#ffffff',
                        scale: window.devicePixelRatio || 2,
                        letterRendering: true,
                        logging: false,
                        scrollX: 0,
                        scrollY: 0
                    });

                    const $root = $(rootSelector);
                    $root.show();
                    $root.html("<img class='result' src='" + canvas.toDataURL("image/png") + "' alt='<?php echo $title ?>'>");
                } catch (err) {
                    console.error(err);
                    window.Puock.toast("產生海報失敗，請到 Console 檢視錯誤資訊", TYPE_DANGER);
                } finally {
                    window.Puock.stopLoading(loadingId);
                }
            })();
        })
    </script>
    <?php

    wp_die();
}
