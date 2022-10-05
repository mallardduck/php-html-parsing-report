<?php

use Masterminds\HTML5;
use function Spatie\Snapshots\assertMatchesHtmlSnapshot;
use function Spatie\Snapshots\assertMatchesTextSnapshot;

dataset('HTML-inner-text', [
    "PHP🐘 is a very 😎 cool language to code in.",
    "This is some fancy-💃 Markdown/WYSIWYG text with surrounding tags disabled. 🎉",
    "This is some fancy-💃 Markdown/WYSIWYG text with surrounding <p> tags disabled. 🎉", // Improper escaping for text "<p>"
    "This is some fancy-💃 Markdown/WYSIWYG text with surrounding &lt;p&gt; tags disabled. 🎉", // Proper escaping for text "<p>"
]);

// This will encode all unicode within the doc.
it('can parse HTML fragment with DOMDocument', function (string $html) {
    $doc = new \DOMDocument();
    $doc->loadHtml($html);
    assertMatchesTextSnapshot($doc->saveHTML());
})->with('HTML-inner-text');

// This fails to load.
it('cannot parse HTML fragment with DOMDocument::loadXml', function (string $html) {
    $doc = new \DOMDocument();
    $this->expectExceptionMessage("DOMDocument::loadXML(): Start tag expected, '<' not found in Entity");
    $doc->loadXML($html);
})->with('HTML-inner-text');

it('cannot parse HTML fragment with HTML5 parser', function (string $html) {
    $html5 = new Masterminds\HTML5(['disable_html_ns' => true]);
    $dom = $html5->loadHtml($html);
    expect($html5->getErrors())->toBeArray()->toHaveKey(0); // We know every input causes at least one error, some more.
})->with('HTML-inner-text');

// This will work but saves with the additional wrapped HTML too
it('can parse HTML fragment text (after wrapping) with HTML5 parser', function (string $html) {
    $html5 = new Masterminds\HTML5(['disable_html_ns' => true]);
    $dom = $html5->loadHtml(wrapFragmentWithDummyPage($html));
    assertMatchesTextSnapshot($html5->saveHTML($dom));
    var_dump($html, $html5->saveHTML($dom));
})->with('HTML-inner-text');

it('can parse HTML fragment with HTML5 fragment parser', function (string $html) {
    $html5 = new Masterminds\HTML5(['disable_html_ns' => true]);
    $dom = $html5->loadHTMLFragment($html);
    assertMatchesTextSnapshot($html5->saveHTML($dom));
    var_dump($html, $html5->saveHTML($dom));
})->with('HTML-inner-text');

// This will work but saves with the additional wrapped HTML too
it('can parse HTML fragment text (after wrapping) with HTML5 fragment parser', function (string $html) {
    $html5 = new Masterminds\HTML5(['disable_html_ns' => true]);
    $dom = $html5->loadHTMLFragment(wrapFragmentWithDummyPage($html));
    assertMatchesTextSnapshot($html5->saveHTML($dom));
    var_dump($html, $html5->saveHTML($dom));
})->with('HTML-inner-text');
