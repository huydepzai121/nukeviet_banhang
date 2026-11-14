// Puts the site back into the known state: every table is dropped, the
// baseline snapshot is loaded and the NukeViet file cache is emptied.
import { existsSync, readFileSync } from 'node:fs';
import path from 'node:path';
import { projectRoot } from '../lib/env.mjs';
import { clearSiteCache, sql } from '../lib/docker.mjs';

const file = path.join(projectRoot, 'tests', '.baseline', 'site.sql');
if (!existsSync(file)) {
    console.error('No baseline snapshot. Run "npm run baseline" on a clean NukeViet install first.');
    process.exit(1);
}

const tables = sql('SHOW TABLES').split(/\r?\n/).filter(Boolean);
const drop = tables.length ? `DROP TABLE IF EXISTS ${tables.map((t) => `\`${t}\``).join(', ')};\n` : '';
sql(`SET FOREIGN_KEY_CHECKS=0;\n${drop}${readFileSync(file, 'utf8')}`);
clearSiteCache();
console.log(`Site reset: ${tables.length} tables dropped, baseline restored, cache cleared.`);
