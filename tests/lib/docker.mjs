// Runs commands inside the compose services. Used only to put the site into a
// known state before tests; assertions never go through here.
import { spawnSync } from 'node:child_process';
import { env, projectRoot } from './env.mjs';

function compose(service, args, input) {
    const res = spawnSync('docker', ['compose', '--env-file', '.env', 'exec', '-T', service, ...args], {
        cwd: projectRoot,
        input,
        encoding: 'utf8',
        maxBuffer: 256 * 1024 * 1024
    });
    if (res.error) throw res.error;
    if (res.status !== 0) {
        throw new Error(`docker compose exec ${service} failed (${res.status}): ${res.stderr.trim()}`);
    }
    return res.stdout;
}

const dbArgs = () => [`-u${env.DB_USER}`, `-p${env.DB_PASS}`, env.DB_NAME];

export function sql(statements) {
    return compose('db', ['mariadb', ...dbArgs(), '-N'], statements);
}

export function dumpDatabase() {
    return compose('db', ['mariadb-dump', ...dbArgs()]);
}

export function clearSiteCache() {
    compose('php', ['sh', '-c', 'find /var/www/html/data/cache -type f ! -name index.html ! -name .htaccess -delete']);
}
