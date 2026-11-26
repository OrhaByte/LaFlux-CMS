<?php

namespace ExtensionsValley\Cms\Helpers;

class SeoHelper
{
    /**
     * Evaluate SEO quality for a Page or Post row/model.
     *
     * Status colors:
     * - grey:   No SEO meta fields configured
     * - red:    Score < 45
     * - orange: Score 45..74
     * - green:  Score >= 75
     */
    public static function evaluate(object|array $item): array
    {
        $get = fn (string $k): string => trim((string) (is_array($item) ? ($item[$k] ?? '') : ($item->{$k} ?? '')));

        $title = $get('title');
        $metaTitle = $get('meta_title');
        $metaKeywords = $get('meta_keywords');
        $metaDesc = $get('meta_description');
        $content = strip_tags($get('content'));
        $featuredImage = $get('featured_image');
        $slug = $get('slug');

        if ($metaTitle === '' && $metaKeywords === '' && $metaDesc === '') {
            return [
                'score' => 0,
                'status' => 'grey',
                'color' => '#94a3b8',
                'label' => 'Not Set',
            ];
        }

        $score = 0;

        // 1. Meta Title (25 pts)
        $effectiveTitle = $metaTitle !== '' ? $metaTitle : $title;
        $titleLen = mb_strlen($effectiveTitle);
        if ($metaTitle !== '') {
            $score += ($titleLen >= 30 && $titleLen <= 65) ? 25 : 15;
        }

        // 2. Meta Description (25 pts)
        $descLen = mb_strlen($metaDesc);
        if ($metaDesc !== '') {
            $score += ($descLen >= 110 && $descLen <= 165) ? 25 : 15;
        }

        // 3. Meta Keywords + Keyword presence in title/description/content (25 pts)
        if ($metaKeywords !== '') {
            $score += 10;
            $keywords = array_values(array_filter(array_map('trim', explode(',', mb_strtolower($metaKeywords)))));
            $primaryKw = $keywords[0] ?? '';
            if ($primaryKw !== '') {
                if (str_contains(mb_strtolower($effectiveTitle), $primaryKw)) {
                    $score += 8;
                }
                if (str_contains(mb_strtolower($metaDesc . ' ' . $content), $primaryKw)) {
                    $score += 7;
                }
            }
        }

        // 4. Content depth (15 pts)
        $wordCount = str_word_count($content);
        if ($wordCount >= 100) {
            $score += 15;
        } elseif ($wordCount >= 25) {
            $score += 8;
        }

        // 5. Featured Image & Clean Slug (10 pts)
        if ($featuredImage !== '') {
            $score += 5;
        }
        if ($slug !== '') {
            $score += 5;
        }

        $score = min(100, $score);

        if ($score >= 75) {
            return ['score' => $score, 'status' => 'green', 'color' => '#22c55e', 'label' => 'Good'];
        }
        if ($score >= 45) {
            return ['score' => $score, 'status' => 'orange', 'color' => '#f59e0b', 'label' => 'Needs Improvement'];
        }

        return ['score' => $score, 'status' => 'red', 'color' => '#ef4444', 'label' => 'Poor'];
    }

    public static function dotHtml(object|array $item): string
    {
        $res = self::evaluate($item);
        $tooltip = e('SEO: ' . $res['label'] . ' (' . $res['score'] . '/100)');

        return '<span title="' . $tooltip . '" style="display:inline-block;width:11px;height:11px;border-radius:50%;background:' . $res['color'] . ';vertical-align:middle;margin-left:6px;box-shadow:0 0 0 2px rgba(0,0,0,0.06);"></span>';
    }
}

