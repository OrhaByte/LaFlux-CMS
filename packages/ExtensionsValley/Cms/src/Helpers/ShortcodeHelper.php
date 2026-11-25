<?php

namespace ExtensionsValley\Cms\Helpers;

use ExtensionsValley\Cms\Models\Banner;
use ExtensionsValley\Cms\Models\CmsForm;

class ShortcodeHelper
{
    public static function render(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // Normalize TinyMCE-encoded quotes inside shortcodes like [banner slug=&quot;home&quot;]
        $pattern = '/\[(banner|form)\s+([^\]]+)\]/i';

        return (string) preg_replace_callback($pattern, function ($matches) {
            $tag = strtolower($matches[1]);
            $rawAttrs = html_entity_decode($matches[2], ENT_QUOTES, 'UTF-8');
            $attrs = self::parseAttributes($rawAttrs);

            if ($tag === 'banner') {
                return self::renderBanner($attrs);
            }

            if ($tag === 'form') {
                return self::renderForm($attrs);
            }

            return $matches[0];
        }, $content);
    }

    protected static function parseAttributes(string $text): array
    {
        $attrs = [];
        if (preg_match_all('/(\w+)\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s]+))/', $text, $m, PREG_SET_ORDER)) {
            foreach ($m as $match) {
                $key = strtolower($match[1]);
                $val = $match[2] !== '' ? $match[2] : ($match[3] !== '' ? $match[3] : ($match[4] ?? ''));
                $attrs[$key] = $val;
            }
        }

        return $attrs;
    }

    protected static function renderBanner(array $attrs): string
    {
        $query = Banner::where('status', 1)->with('activeSlides');

        if (!empty($attrs['slug'])) {
            $banner = $query->where('slug', $attrs['slug'])->first();
        } elseif (!empty($attrs['id'])) {
            $banner = $query->where('id', (int) $attrs['id'])->first();
        } else {
            return '';
        }

        if (!$banner || $banner->activeSlides->isEmpty()) {
            return '';
        }

        return view('Cms::frontend.shortcodes.banner', compact('banner'))->render();
    }

    protected static function renderForm(array $attrs): string
    {
        $query = CmsForm::where('status', 1)->with('fields');

        if (!empty($attrs['slug'])) {
            $form = $query->where('slug', $attrs['slug'])->first();
        } elseif (!empty($attrs['id'])) {
            $form = $query->where('id', (int) $attrs['id'])->first();
        } else {
            return '';
        }

        if (!$form || $form->fields->isEmpty()) {
            return '';
        }

        return view('Cms::frontend.shortcodes.form', compact('form'))->render();
    }
}

