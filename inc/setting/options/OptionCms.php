<?php

namespace Puock\Theme\setting\options;

class OptionCms extends BaseOptionItem{

    function get_fields(): array
    {
        return [
            'key' => 'cms',
            'label' => __('CMS 佈局', PUOCK),
            'icon'=>'czs-layers',
            'fields' => [
                [
                    'id' => 'cms_show_pagination',
                    'label' => __('顯示分頁', PUOCK),
                    'type' => 'switch',
                    'sdt' => false,
                ],
                [
                    'id' => 'cms_show_load_more',
                    'label' => __('顯示載入更多', PUOCK),
                    'type' => 'switch',
                    'sdt' => false,
                ],
                [
                    'id' => 'cms_show_new',
                    'label' => __('顯示最新文章', PUOCK),
                    'type' => 'switch',
                    'sdt' => true,
                ],
                [
                    'id' => 'cms_new_sort',
                    'label' => __('最新文章排序規則', PUOCK),
                    'type' => 'radio',
                    'options'=>[
                        ['label'=>__('發佈時間', PUOCK),'value'=>'published'],
                        ['label'=>__('更新時間', PUOCK),'value'=>'updated'],
                    ],
                    'sdt' => 'published',
                ],
                [
                    'id' => 'cms_show_new_num',
                    'label' => __('最新文章數量', PUOCK),
                    'type' => 'number',
                    'sdt' => 6,
                    'showRefId' => 'cms_show_new',
                ],
                [
                    'id' => 'cms_card_columns',
                    'label' => __('CMS 卡片列數', PUOCK),
                    'type' => 'select',
                    'sdt' => 2,
                    'options' => [
                        ['label' => '2', 'value' => 2],
                        ['label' => '3', 'value' => 3],
                        ['label' => '4', 'value' => 4],
                    ],
                    'tips' => __('適用於文章清單的卡片風格（首頁/分類/標籤/作者/搜尋等）；當頁面顯示側邊欄時最大僅 2 列；預設 2 列', PUOCK),
                ],
                [
                    'id' => 'cms_show_2box',
                    'label' => __('顯示 CMS 兩欄佈局', PUOCK),
                    'type' => 'switch',
                    'sdt' => true,
                ],
                [
                    'id' => 'cms_show_2box_id',
                    'label' => __('CMS 兩欄佈局分類 ID', PUOCK),
                    'type' => 'select',
                    'sdt' => '',
                    'multiple' => true,
                    'showRefId' => 'cms_show_2box',
                    'options' => self::get_category(),
                ],
                [
                    'id' => 'cms_show_2box_num',
                    'label' => __('CMS 兩欄佈局每欄數量', PUOCK),
                    'type' => 'number',
                    'sdt' => 6,
                    'showRefId' => 'cms_show_2box',
                ],
            ],
        ];
    }
}
