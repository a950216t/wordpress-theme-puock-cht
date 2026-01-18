<?php

namespace Puock\Theme\setting;

use Puock\Theme\setting\options\OptionAbout;
use Puock\Theme\setting\options\OptionAd;
use Puock\Theme\setting\options\OptionAi;
use Puock\Theme\setting\options\OptionBasic;
use Puock\Theme\setting\options\OptionCache;
use Puock\Theme\setting\options\OptionCarousel;
use Puock\Theme\setting\options\OptionCms;
use Puock\Theme\setting\options\OptionCompany;
use Puock\Theme\setting\options\OptionDebug;
use Puock\Theme\setting\options\OptionEmail;
use Puock\Theme\setting\options\OptionExtend;
use Puock\Theme\setting\options\OptionGlobal;
use Puock\Theme\setting\options\OptionAuth;
use Puock\Theme\setting\options\OptionResource;
use Puock\Theme\setting\options\OptionScript;
use Puock\Theme\setting\options\OptionSeo;
use Puock\Theme\setting\options\OptionValidate;

class PuockSetting
{
    public function init()
    {
        add_action("admin_menu", array($this, '__wp_reg_menu'));
        add_action('admin_init', array($this, '__wp_admin_init'));
    }

    public function option_menus_register()
    {
        $classes = [];
        $classes[] = ['class' => OptionGlobal::class, 'sort' => 1];
        $classes[] = ['class' => OptionBasic::class, 'sort' => 2];
        $classes[] = ['class' => OptionCarousel::class, 'sort' => 3];
        $classes[] = ['class' => OptionCms::class, 'sort' => 4];
        $classes[] = ['class' => OptionCompany::class, 'sort' => 5];
        $classes[] = ['class' => OptionAuth::class, 'sort' => 6];
        $classes[] = ['class' => OptionAi::class, 'sort' => 7];
        $classes[] = ['class' => OptionValidate::class, 'sort' => 7];
        $classes[] = ['class' => OptionAd::class, 'sort' => 8];
        $classes[] = ['class' => OptionEmail::class, 'sort' => 9];
        $classes[] = ['class' => OptionSeo::class, 'sort' => 10];
        $classes[] = ['class' => OptionExtend::class, 'sort' => 10];
        $classes[] = ['class' => OptionScript::class, 'sort' => 11];
        $classes[] = ['class' => OptionCache::class, 'sort' => 12];
        $classes[] = ['class' => OptionDebug::class, 'sort' => 13];
        $classes[] = ['class' => OptionResource::class, 'sort' => 14];
        $classes[] = ['class' => OptionAbout::class, 'sort' => 99];
        $classes = apply_filters('pk_theme_option_menus_register', $classes, 10, 1);
        array_multisort(array_column($classes, 'sort'), SORT_ASC, $classes);
        return $classes;
    }

    public function __wp_admin_init()
    {
    }

    public function __wp_reg_menu()
    {
        add_menu_page(
            __('Puock 主題組態', PUOCK),
            __('Puock 主題組態', PUOCK),
            "manage_options",
            "puock-options",
            array($this, 'setting_page'),
            PUOCK_ABS_URI . '/assets/img/logo/puock-20.png',
        );
    }

    function setting_page()
    {
        $menus = $this->option_menus_register();
        if (!current_user_can('edit_theme_options')) {
            wp_send_json_error(__('許可權不足', PUOCK));
        }
        $fields = [];
        foreach ($menus as $menu) {
            $f = (new $menu['class']())->get_fields();
            $fields[] = apply_filters('pk_load_theme_option_fields_'.$f['key'], $f);
        }
        do_action('pk_get_theme_option_fields', $fields);
        require_once dirname(__FILE__) . '/template.php';
    }

    /**
     * 從欄位定義中提取預設值
     *
     * @return array
     */
    public function get_default_options(): array
    {
        $menus = $this->option_menus_register();
        $defaults = [];
        foreach ($menus as $menu) {
            $f = (new $menu['class']())->get_fields();
            $f = apply_filters('pk_load_theme_option_fields_' . $f['key'], $f);
            if (isset($f['fields']) && is_array($f['fields'])) {
                $this->extract_defaults_from_fields($f['fields'], $defaults);
            }
        }
        return $defaults;
    }

    /**
     * 遞迴提取欄位預設值
     *
     * @param array $fields 欄位陣列
     * @param array $defaults 預設值陣列（引用傳遞）
     * @return void
     */
    private function extract_defaults_from_fields(array $fields, array &$defaults): void
    {
        foreach ($fields as $field) {
            // 如果欄位有 id 和 sdt（預設值），則提取
            if (isset($field['id']) && array_key_exists('sdt', $field)) {
                $defaults[$field['id']] = $field['sdt'];
            }
            // 遞迴處理子欄位（如 panel 類型）
            if (isset($field['children']) && is_array($field['children'])) {
                $this->extract_defaults_from_fields($field['children'], $defaults);
            }
            // 處理嵌套的 fields
            if (isset($field['fields']) && is_array($field['fields'])) {
                $this->extract_defaults_from_fields($field['fields'], $defaults);
            }
        }
    }
}
