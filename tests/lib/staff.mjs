// What a member of staff does in the administration area.
import { Browser } from './browser.mjs';
import { env } from './env.mjs';

export const adminUrl = (query) => `/admin/index.php?language=vi&${query}`;

/** A browser signed in to the administration area. */
export async function signInAsStaff() {
    const browser = new Browser();
    const login = await browser.get('/admin/index.php');
    const page = await browser.submit(login.form(), {
        nv_login: env.NV_ADMIN_USER,
        nv_password: env.NV_ADMIN_PASS
    });
    if (/name="nv_password"/.test(page.body)) {
        throw new Error('Signing in to the administration area failed');
    }
    return browser;
}
