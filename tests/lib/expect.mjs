// Assertions on what a page shows, with failure messages short enough to read.
import assert from 'node:assert/strict';

const PHP_NOISE = /Fatal error|Parse error|Warning<\/b>|Notice<\/b>|Deprecated<\/b>|Uncaught |SQLSTATE\[/;

function position(text, needle) {
    return needle instanceof RegExp ? text.search(needle) : text.indexOf(needle);
}

function excerpt(text, at) {
    const start = Math.max(0, at - 150);
    return text.slice(start, start + 400);
}

export function shows(page, expected, what = 'trang') {
    const text = page.text;
    assert.ok(
        position(text, expected) !== -1,
        `${what} (${page.url}, HTTP ${page.status}) phải hiện ${expected}; đang hiện: ${excerpt(text, 0)}…`
    );
}

export function doesNotShow(page, unexpected, what = 'trang') {
    const text = page.text;
    const at = position(text, unexpected);
    assert.ok(at === -1, `${what} (${page.url}) không được hiện ${unexpected}: …${excerpt(text, at)}…`);
}

/** The page loaded and carries no PHP or database error output. */
export function loadsCleanly(page, what = 'trang') {
    assert.equal(page.status, 200, `${what} (${page.url}) trả HTTP ${page.status}`);
    const at = page.body.search(PHP_NOISE);
    assert.ok(at === -1, `${what} (${page.url}) có thông báo lỗi PHP: …${excerpt(page.body, at)}…`);
}
