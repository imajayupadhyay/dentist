<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class RichTextSanitizer
{
    private const ALLOWED_TAGS = [
        'a',
        'br',
        'em',
        'i',
        'li',
        'mark',
        'ol',
        'p',
        'strong',
        'ul',
    ];

    private const REMOVE_WITH_CONTENTS = [
        'embed',
        'iframe',
        'math',
        'object',
        'script',
        'style',
        'svg',
    ];

    public function sanitize(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        if (! preg_match('/<[^>]+>/', $value)) {
            return $this->paragraphize($value);
        }

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="rich-text-root">'.$value.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('rich-text-root');

        if (! $root) {
            return '';
        }

        $this->cleanChildren($root);

        $html = trim($this->innerHtml($root));

        if (trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')) === '') {
            return '';
        }

        return $html;
    }

    private function cleanChildren(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);

                continue;
            }

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::REMOVE_WITH_CONTENTS, true)) {
                $node->removeChild($child);

                continue;
            }

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                $this->cleanChildren($child);
                $this->unwrap($child);

                continue;
            }

            $this->cleanAttributes($child);
            $this->cleanChildren($child);
        }
    }

    private function cleanAttributes(DOMElement $element): void
    {
        $tag = strtolower($element->tagName);
        $href = $tag === 'a' ? $this->cleanHref($element->getAttribute('href')) : null;

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $element->removeAttribute($attribute->name);
        }

        if ($tag === 'a' && $href) {
            $element->setAttribute('href', $href);
            $element->setAttribute('rel', 'noopener noreferrer');

            if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
                $element->setAttribute('target', '_blank');
            }
        }
    }

    private function cleanHref(string $href): ?string
    {
        $href = trim($href);

        if ($href === '') {
            return null;
        }

        $lower = strtolower($href);

        if (
            str_starts_with($lower, '#') ||
            (str_starts_with($lower, '/') && ! str_starts_with($lower, '//')) ||
            str_starts_with($lower, 'http://') ||
            str_starts_with($lower, 'https://') ||
            str_starts_with($lower, 'mailto:') ||
            str_starts_with($lower, 'tel:')
        ) {
            return $href;
        }

        return null;
    }

    private function unwrap(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if (! $parent) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private function innerHtml(DOMElement $element): string
    {
        $html = '';

        foreach ($element->childNodes as $child) {
            $html .= $element->ownerDocument->saveHTML($child);
        }

        return $html;
    }

    private function paragraphize(string $value): string
    {
        $paragraphs = preg_split('/\R{2,}/', str_replace(["\r\n", "\r"], "\n", $value)) ?: [];

        return collect($paragraphs)
            ->map(fn (string $paragraph): string => trim($paragraph))
            ->filter()
            ->map(function (string $paragraph): string {
                $lines = collect(explode("\n", $paragraph))
                    ->map(fn (string $line): string => e(trim($line)))
                    ->all();

                return '<p>'.implode('<br>', $lines).'</p>';
            })
            ->implode('');
    }
}
