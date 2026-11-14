// Reads the project's .env (never committed). Values are used, never printed.
import { readFileSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import path from 'node:path';

export const projectRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..', '..');

function parse(text) {
    const out = {};
    for (const line of text.split(/\r?\n/)) {
        const m = /^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)\s*$/.exec(line);
        if (!m) continue;
        let value = m[2];
        if (/^(['"]).*\1$/.test(value)) value = value.slice(1, -1);
        out[m[1]] = value;
    }
    return out;
}

export const env = parse(readFileSync(path.join(projectRoot, '.env'), 'utf8'));
export const baseUrl = process.env.SITE_URL || `http://localhost:${env.HTTP_PORT}`;
