<?php

namespace Puock\Theme\setting\options;

/**
 * @type = 'string' | 'number' | 'select' | 'switch' | 'date' | 'img' | 'textarea' | 'color' | 'upload' | 'radio' | 'info' | 'slider' | 'dynamic-list'
 * @ruleType = 'string' | 'number' | 'boolean' | 'method' | 'regexp' | 'integer' | 'float' | 'array' | 'object' | 'enum' | 'date' | 'url' | 'hex' | 'email' | 'pattern' | 'any'
 * @textType = 'text' | 'textarea' | string
 * @radioType = 'button' | 'radio'
 * @infoType = 'info' | 'warning' | 'error' | 'success'
 */
abstract class BaseOptionItem
{

    protected static $_category = null;
    protected static $_link_category = null;
    protected static $_pages = null;

    protected static function get_category()
    {
        if (!self::$_category) {
            self::$_category = get_all_category_id_row('category');
        }
        return self::$_category;
    }

    protected static function get_link_category(): ?array
    {
        if (!self::$_link_category) {
            self::$_link_category = get_all_category_id_row('link_category');
            array_unshift(self::$_link_category, ['label' => '無', 'value' => '']);
        }
        return self::$_link_category;
    }

    /**
     * 取得友情連結排序順序。
     *
     * 昇冪 (ASC)、降冪 (DESC)，預設為昇冪 (ASC)。
     * https://developer.wordpress.org/reference/functions/get_bookmarks/#parameters
     *
     */
    protected static function get_link_order()
    {
        return [
            ["label" => "昇冪 (ASC)", "value" => "ASC"],
            ["label" => "降冪 (DESC)", "value" => "DESC"]
        ];
    }

    /**
     * 取得友情連結排序欄位。
     *
     * 下面僅為部分欄位，所支援的全部欄位請查看官方文檔`orderby`部分
     * https://developer.wordpress.org/reference/functions/get_bookmarks/#parameters
     *
     */
    protected static function get_link_order_by(): array
    {
        return [
            ["label" => "ID 排序", "value" => "link_id"],
            ["label" => "連結排序", "value" => "url"],
            ["label" => "名字排序", "value" => "name"],
            ["label" => "評級排序", "value" => "rating"],
            ["label" => "長度排序", "value" => "length"],
            ["label" => "隨機排序", "value" => "rand"]
        ];
    }

    protected static function get_pages()
    {
        if (!self::$_pages) {
            $pages = array();
            $pages[] = ['label' => '無', 'value' => ''];
            $pageObjects = get_pages('sort_column=post_parent,menu_order');
            foreach ($pageObjects as $page) {
                $pages[] = ['label' => $page->post_title, 'value' => $page->ID];
            }
            self::$_pages = $pages;
        }
        return self::$_pages;
    }

    abstract function get_fields(): array;
}
