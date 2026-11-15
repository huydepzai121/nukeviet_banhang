// Puts the site into a known state before tests. Assertions never go through here.
// Docker Compose is used when it is available. Otherwise the same MariaDB and
// cache directory on this machine are used, so the suite can run without Docker.
import { spawnSync } from 'node:child_process';
import { readdirSync, rmSync } from 'node:fs';
import path from 'node:path';
import { env, projectRoot } from './env.mjs';

function dockerComposeAvailable() {
    const res = spawnSync('docker', ['compose', 'version'], { encoding: 'utf8' });
    return !res.error && res.status === 0;
}

const useDocker = dockerComposeAvailable();

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

function localMysql(bin, extraArgs, input) {
    const res = spawnSync(bin, [
        '-h127.0.0.1',
        `-u${env.DB_USER}`,
        `-p${env.DB_PASS}`,
        ...extraArgs,
        env.DB_NAME
    ], {
        cwd: projectRoot,
        input,
        encoding: 'utf8',
        maxBuffer: 256 * 1024 * 1024
    });
    if (res.error) throw res.error;
    if (res.status !== 0) {
        throw new Error(`${bin} failed (${res.status}): ${res.stderr.trim()}`);
    }
    return res.stdout;
}

const dbArgs = () => [`-u${env.DB_USER}`, `-p${env.DB_PASS}`, env.DB_NAME];

export function sql(statements) {
    if (useDocker) return compose('db', ['mariadb', ...dbArgs(), '-N'], statements);
    return localMysql('mysql', ['-N', '--batch'], statements);
}

export function dumpDatabase() {
    if (useDocker) return compose('db', ['mariadb-dump', ...dbArgs()]);
    return localMysql('mysqldump', ['--single-transaction', '--skip-comments'], undefined);
}

export function clearSiteCache() {
    if (useDocker) {
        compose('php', ['sh', '-c', 'find /var/www/html/data/cache -type f ! -name index.html ! -name .htaccess -delete']);
        return;
    }
    const cacheDir = path.join(projectRoot, 'site', 'data', 'cache');
    for (const name of readdirSync(cacheDir)) {
        if (name === 'index.html' || name === '.htaccess') continue;
        rmSync(path.join(cacheDir, name), { recursive: true, force: true });
    }
}
