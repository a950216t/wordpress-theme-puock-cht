<?php

namespace Puock\Theme\setting\options;

class OptionExtend extends BaseOptionItem
{

    function get_fields(): array
    {
        return [
            'key' => 'extend',
            'label' => __('擴充套件及相容', PUOCK),
            'icon' => 'dashicons-admin-plugins',
            'fields' => [
                [
                    'id' => 'office_mp_support',
                    'label' => __('Puock 官方小程式支援', PUOCK),
                    'type' => 'switch',
                    'value' => defined('PUOCK_MP_VERSION'),
                    'tips' => __('Puock 官方小程式支援，此選項安裝小程式外掛後會自動開啟，如需關閉請在小程式外掛中關閉', PUOCK) . " （<a target='_blank' href='https://licoy.cn/puock-mp.html'>" . __('瞭解小程式？', PUOCK) . "</a>）",
                    'disabled' => true,
                    'badge' => ['value' => '🔥 ' . __('熱門 & 推薦', PUOCK)]
                ],
                [
                    'id' => 'user_center',
                    'label' => __('使用者中心', PUOCK),
                    'type' => 'switch',
                    'sdt' => false,
                    'badge' => ['value' => 'New'],
                    'tips' => __('使用前請先配置 wordpress 偽靜態規則：<code>try_files $uri $uri/ /index.php?$args</code>', PUOCK)
                ],
                [
                    'id' => 'user_center_entry',
                    'label' => __('使用者中心入口', PUOCK),
                    'type' => 'select',
                    'sdt' => '',
                    'options' => [
                        ['value' => 'theme', 'label' => __('主題使用者中心', PUOCK)],
                        ['value' => 'wp', 'label' => __('WordPress 個人資料', PUOCK)],
                        ['value' => 'erphp', 'label' => __('ErphpDown 使用者中心', PUOCK)],
                    ],
                    'tips' => __('用於選擇使用者中心跳轉入口（未設定時沿用「使用者中心」開關邏輯）', PUOCK)
                ],
                [
                    'id' => 'erphpdown_user_center_url',
                    'label' => __('ErphpDown 使用者中心 URL', PUOCK),
                    'type' => 'text',
                    'sdt' => '/erphpdown/user',
                    'showRefId' => 'func:(function(args){return args.data.user_center_entry==="erphp"})(args)',
                    'tips' => __('ErphpDown 使用者中心地址，可填寫完整 URL 或站內路徑', PUOCK)
                ],
                [
                    'id' => 'strawberry_icon',
                    'label' => __('Strawberry 圖示庫', PUOCK),
                    'type' => 'switch',
                    'sdt' => false,
                    'tips' => __('開啟之後會在前臺載入 Strawberry 圖示庫支援', PUOCK)
                ],
                [
                    'id' => 'dplayer',
                    'label' => 'DPlayer' . ' ' . __('支援', PUOCK),
                    'type' => 'switch',
                    'sdt' => false,
                    'tips' => __('開啟之後會在前臺載入 DPlayer 支援', PUOCK)
                ],
            ],
        ];
    }
}
