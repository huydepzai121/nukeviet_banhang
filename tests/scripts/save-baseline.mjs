// Saves the current database as the known state every test run starts from.
// Take it on a freshly installed NukeViet, before the shops module is set up:
// the tests install the module and create their own data over HTTP.
import { mkdirSync, writeFileSync } from 'node:fs';
import path from 'node:path';
import { projectRoot } from '../lib/env.mjs';
import { dumpDatabase } from '../lib/docker.mjs';

const dir = path.join(projectRoot, 'tests', '.baseline');
mkdirSync(dir, { recursive: true });
const file = path.join(dir, 'site.sql');
writeFileSync(file, dumpDatabase());
console.log(`Saved ${path.relative(projectRoot, file)}`);
