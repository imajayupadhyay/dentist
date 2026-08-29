<?php

namespace Tests\Unit;

use App\Support\RichTextSanitizer;
use PHPUnit\Framework\TestCase;

class RichTextSanitizerTest extends TestCase
{
    public function test_plain_text_is_converted_to_paragraph_html(): void
    {
        $html = (new RichTextSanitizer)->sanitize("First line\nSecond line\n\nNext paragraph");

        $this->assertSame('<p>First line<br>Second line</p><p>Next paragraph</p>', $html);
    }

    public function test_only_safe_rich_text_markup_is_kept(): void
    {
        $html = (new RichTextSanitizer)->sanitize(
            '<p onclick="alert(1)"><strong>Bold</strong><script>alert(1)</script><mark>Marked</mark><a href="javascript:alert(1)">bad</a><a href="/safe" style="color:red">safe</a><span><em>wrapped</em></span></p>',
        );

        $this->assertStringContainsString('<strong>Bold</strong>', $html);
        $this->assertStringContainsString('<mark>Marked</mark>', $html);
        $this->assertStringContainsString('<a>bad</a>', $html);
        $this->assertStringContainsString('<a href="/safe" rel="noopener noreferrer">safe</a>', $html);
        $this->assertStringContainsString('<em>wrapped</em>', $html);
        $this->assertStringNotContainsString('script', $html);
        $this->assertStringNotContainsString('onclick', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringNotContainsString('style=', $html);
    }
}
