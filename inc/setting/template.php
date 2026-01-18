<?php if (!file_exists(dirname(__FILE__) . '/template-script-dev.php')): ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/dist/setting/index.css?ver=<?php echo PUOCK_CUR_VER_STR ?>">
<?php endif; ?>
<style id="pk-options-style"></style>
<style>
    .pk-reset-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .pk-reset-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .pk-reset-modal {
        background: #fff;
        border-radius: 8px;
        padding: 24px;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        transform: scale(0.9);
        transition: transform 0.2s ease;
    }
    .pk-reset-modal-overlay.active .pk-reset-modal {
        transform: scale(1);
    }
    .pk-reset-modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pk-reset-modal-title .dashicons {
        color: #f0a020;
    }
    .pk-reset-modal-content {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    .pk-reset-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .pk-reset-modal-btn {
        padding: 8px 20px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .pk-reset-modal-btn-cancel {
        background: #f5f5f5;
        color: #666;
    }
    .pk-reset-modal-btn-cancel:hover {
        background: #e8e8e8;
    }
    .pk-reset-modal-btn-confirm {
        background: #d03050;
        color: #fff;
    }
    .pk-reset-modal-btn-confirm:hover {
        background: #bf2040;
    }
    .pk-reset-modal-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
<div id="app">
    <div style="padding: 40px;font-size: 30px">loading...</div>
</div>

<!-- 重設確認彈窗 -->
<div id="pk-reset-modal-overlay" class="pk-reset-modal-overlay">
    <div class="pk-reset-modal">
        <div class="pk-reset-modal-title">
            <span class="dashicons dashicons-warning"></span>
            <?php _e('確認重設設定', PUOCK); ?>
        </div>
        <div class="pk-reset-modal-content">
            <?php _e('此操作將把所有主題設定恢復為預設值，您當前的設定將會丟失。', PUOCK); ?>
            <br><br>
            <strong><?php _e('此操作不可撤銷，確定要繼續嗎？', PUOCK); ?></strong>
        </div>
        <div class="pk-reset-modal-actions">
            <button type="button" class="pk-reset-modal-btn pk-reset-modal-btn-cancel" id="pk-reset-cancel">
                <?php _e('取消', PUOCK); ?>
            </button>
            <button type="button" class="pk-reset-modal-btn pk-reset-modal-btn-confirm" id="pk-reset-confirm">
                <?php _e('確認重設', PUOCK); ?>
            </button>
        </div>
    </div>
</div>

<script>
    jQuery(function () {
        var wpAdminBarEl = jQuery("#wpadminbar")
        var wpFooterEl = jQuery("#wpfooter")
        var pkOptionsStyleEl = jQuery("#pk-options-style")

        function loadWpContentHeight() {
            var h = window.innerHeight - wpAdminBarEl.height() - wpFooterEl.height() - 50
            pkOptionsStyleEl.html("#wpbody-content{height:" + h + "px;padding:0;}#pk-options-box{height:" + (window.innerHeight - wpAdminBarEl.height()) + "px}")
        }

        window.addEventListener("resize", loadWpContentHeight)

        loadWpContentHeight()
    })

    window.puockSettingId = "puock-theme-options-global";
    window.puockSettingMetaInfo = {
        version: "V<?php echo PUOCK_CUR_VER_STR ?>",
        colors: {
            primaryColor: '#ae4af7',
            primaryColorHover: '#903eca',
            primaryColorPressed: '#8912e6',
            primaryColorSuppl: '#a537fb',
        },
        language:"<?php echo get_user_locale() ?>",
        description:"<?php _e('簡單/方便/高顏值', PUOCK) ?>",
        tag: {text: "<?php _e('主題', PUOCK) ?>", color: 'rgb(155,39,238)'},
        github: "https://github.com/Licoy/wordpress-theme-puock",
        qq: "https://licoy.cn/go/puock-update.php?r=qq_qun",
        license: "GPL V3",
        donate: "https://licoy.cn/puock-theme-sponsor.html",
        update_url: '<?php echo admin_url('admin-ajax.php') ?>?action=update_theme_options',
        reset_url: '<?php echo admin_url('admin-ajax.php') ?>?action=reset_theme_options',
        fields:<?php echo json_encode($fields); ?>,
        data:<?php echo json_encode(get_option(PUOCK_OPT)); ?>,
    }

    // 重設功能
    ;(function() {
        var resetModalOverlay = document.getElementById('pk-reset-modal-overlay');
        var resetConfirmBtn = document.getElementById('pk-reset-confirm');
        var resetCancelBtn = document.getElementById('pk-reset-cancel');
        var isResetting = false;

        function showResetModal() {
            resetModalOverlay.classList.add('active');
        }

        function hideResetModal() {
            resetModalOverlay.classList.remove('active');
        }

        function doReset() {
            if (isResetting) return;
            isResetting = true;
            resetConfirmBtn.disabled = true;
            resetConfirmBtn.textContent = '<?php _e('重設中...', PUOCK); ?>';

            fetch(window.puockSettingMetaInfo.reset_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({})
            })
            .then(function(response) { return response.json(); })
            .then(function(result) {
                if (result.success) {
                    alert('<?php _e('設定已重設為預設值，頁面即將重新整理', PUOCK); ?>');
                    window.location.reload();
                } else {
                    alert('<?php _e('重設失敗：', PUOCK); ?>' + (result.data || '<?php _e('未知錯誤', PUOCK); ?>'));
                    isResetting = false;
                    resetConfirmBtn.disabled = false;
                    resetConfirmBtn.textContent = '<?php _e('確認重設', PUOCK); ?>';
                }
            })
            .catch(function(error) {
                alert('<?php _e('重設失敗：', PUOCK); ?>' + error.message);
                isResetting = false;
                resetConfirmBtn.disabled = false;
                resetConfirmBtn.textContent = '<?php _e('確認重設', PUOCK); ?>';
            });
        }

        // 點選遮罩層關閉
        resetModalOverlay.addEventListener('click', function(e) {
            if (e.target === resetModalOverlay && !isResetting) {
                hideResetModal();
            }
        });

        resetCancelBtn.addEventListener('click', function() {
            if (!isResetting) hideResetModal();
        });

        resetConfirmBtn.addEventListener('click', doReset);

        // ESC 鍵關閉
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && resetModalOverlay.classList.contains('active') && !isResetting) {
                hideResetModal();
            }
        });

        // 暴露給全域使用
        window.puockShowResetModal = showResetModal;

        // 在 Vue 應用載入完成後注入重新開機按鈕
        function injectResetButton() {
            var headerRight = document.querySelector('.pk-setting-header-right');
            if (!headerRight) {
                // 嘗試其他可能的選擇器
                headerRight = document.querySelector('[class*="header"] [class*="right"]');
            }
            if (!headerRight) {
                // 如果找不到 header-right，嘗試在儲存按鈕附近新增
                var saveBtn = document.querySelector('button[class*="primary"]');
                if (saveBtn && saveBtn.parentElement) {
                    headerRight = saveBtn.parentElement;
                }
            }

            if (headerRight) {
                // 檢查是否已經新增過
                if (document.getElementById('pk-reset-btn')) return;

                var resetBtn = document.createElement('button');
                resetBtn.id = 'pk-reset-btn';
                resetBtn.type = 'button';
                resetBtn.className = 'n-button n-button--default-type n-button--medium-type';
                resetBtn.style.cssText = 'margin-right: 12px; background: #f5f5f5; border: 1px solid #e0e0e0; color: #666; padding: 0 16px; height: 34px; border-radius: 4px; cursor: pointer; font-size: 14px; display: inline-flex; align-items: center; gap: 6px;';
                resetBtn.innerHTML = '<span class="dashicons dashicons-image-rotate" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px;"></span><?php _e('重設設定', PUOCK); ?>';
                resetBtn.addEventListener('click', showResetModal);
                resetBtn.addEventListener('mouseenter', function() {
                    this.style.background = '#e8e8e8';
                });
                resetBtn.addEventListener('mouseleave', function() {
                    this.style.background = '#f5f5f5';
                });

                // 插入到第一個子元素之前
                if (headerRight.firstChild) {
                    headerRight.insertBefore(resetBtn, headerRight.firstChild);
                } else {
                    headerRight.appendChild(resetBtn);
                }
            }
        }

        // 使用 MutationObserver 監聽 DOM 變化，等 Vue 載入完成
        var observer = new MutationObserver(function(mutations) {
            injectResetButton();
        });

        observer.observe(document.getElementById('app'), {
            childList: true,
            subtree: true
        });

        // 備用：延遲嘗試
        setTimeout(injectResetButton, 1000);
        setTimeout(injectResetButton, 2000);
        setTimeout(injectResetButton, 3000);
    })();
</script>
<script type="text/javascript" crossorigin src="<?php echo get_template_directory_uri() ?>/assets/dist/setting/language/<?php echo get_user_locale() ?>.js?ver=<?php echo PUOCK_CUR_VER_STR ?>"></script>
<?php
if (file_exists(dirname(__FILE__) . '/template-script-dev.php')) {
    include_once dirname(__FILE__) . '/template-script-dev.php';
} else { ?>

    <script type="module" crossorigin
            src="<?php echo get_template_directory_uri() ?>/assets/dist/setting/index.js?ver=<?php echo PUOCK_CUR_VER_STR ?>"></script>

    <?php
}
?>
