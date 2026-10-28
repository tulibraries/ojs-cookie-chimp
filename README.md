# ojs-cookie-chimp

Minimal site-wide [CookieChimp](https://cookiechimp.com/) consent widget plugin for **OJS 3.4.x**.

## Behavior

When **CookieChimp** is enabled in OJS's **site-wide** plugin management area, it adds this tag to the frontend HTML `<head>` of the site homepage and every journal:

```html
<script src="https://cookiechimp.com/widget/a2ZtgNB.js"></script>
```

No per-journal configuration is needed. It uses the OJS `TemplateManager::display` hook and PKP's `addHeader` API, restricted to frontend pages. It does **not** modify OJS core or other journal headers. The script URL is hardcoded to the specified CookieChimp widget.

## Install

### Filesystem / container image (preferred)

Copy the `cookieChimp` directory into your OJS installation:

```bash
cp -R cookieChimp /var/www/html/ojs/plugins/generic/cookieChimp
```

Or, in your Dockerfile, relative to your image's OJS working directory:

```dockerfile
COPY cookieChimp/ /var/www/html/ojs/plugins/generic/cookieChimp/
```

Adjust the root path to match your container. Ensure the web server can read the files. Restart/redeploy the application and clear OJS template caches if needed. In the **site-wide administration context**, open **Settings > Website > Plugins > Installed Plugins > Generic Plugins** (navigation varies by permissions), find **CookieChimp**, and enable it once. You may need to install/register the plugin in your OJS plugin manager first. Do not enable it individually per journal.

### Plugin installer

The standalone `cookieChimp.tar.gz` archive has `cookieChimp/` as the top-level directory and can be tried through the OJS plugin upload installer. The **ZIP** archive is mainly for source/repository distribution; use the `.tar.gz` for OJS's plugin upload dialog.

## Verify

1. Load the site homepage and a page in at least two different journals.
2. Inspect **View Page Source** (not only the Elements tab) and search for `cookiechimp.com/widget/a2ZtgNB.js`; it should appear once in `<head>` on each page.
3. Confirm your CSP allows `https://cookiechimp.com` (and any other script/connect destinations documented by CookieChimp).
4. Verify that non-essential trackers are prevented from firing before consent. Including the widget script **does not itself prove that consent enforcement is correctly configured**.
5. Confirm the site is registered in your CookieChimp account for all relevant domains/paths.

## Notes

- OJS 3.4.x target; not validated inside a live OJS environment.
- Loads on publicly rendered frontend templates, including login/submission pages that use the frontend template context; excludes backend dashboard pages.
- A reverse proxy could also inject the script, but using OJS keeps this behavior in a versioned plugin.
- If you have previously installed the same CookieChimp snippet through Custom Header, remove it there to avoid duplication.

Licensed under GPL-3.0-or-later.
