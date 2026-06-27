'use strict';

/**
 * @file
 * Custom step definitions for the Varbase Core test suite.
 *
 * Most of the suite reuses the step definitions that ship with webship-js
 * (navigation, web-first assertions, accessibility). Only a few module-specific
 * helpers live here: logging in as a named user from cucumber.js
 * worldParameters.users, dropping back to an anonymous session, and creating a
 * Basic page through the node-add form.
 */

const { Given, When } = require('@cucumber/cucumber');
const {
  friendly,
} = require('webship-js/tests/step-definitions/webship');

/**
 * Navigate with domcontentloaded only — heavy front-end themes (Bootstrap/AOS)
 * may never reach network idle, so do not block on it.
 */
async function settle(page) {
  await page.waitForLoadState('domcontentloaded').catch(() => {});
  await page.waitForTimeout(1500);
}

async function gotoUrl(page, url) {
  await page.goto(url, { waitUntil: 'domcontentloaded', timeout: 60000 });
  await page.waitForSelector('body', { state: 'attached', timeout: 30000 }).catch(() => {});
}

/**
 * Run a step body and rethrow any failure as a tester-friendly error.
 *
 * @param {Function} body
 *   Async function performing the step.
 * @param {string} message
 *   Human-readable description for failures.
 */
async function attempt(body, message) {
  try {
    await body();
  }
  catch (err) {
    throw friendly(message, err);
  }
}

/**
 * Log in as a named test user defined in cucumber.js worldParameters.users.
 *
 * Example: Given I am a logged in user with the "Webmaster" user
 */
Given(/^I am a logged in user with( the)*( username)* "([^"]*)?"( user)?$/, async function (theCase, usernameCase, key, userCase) {
  const users = this.parameters.users || {};
  if (!(key in users)) {
    throw new Error(`No user named "${key}" in cucumber.js worldParameters.users`);
  }
  const { username, password } = users[key];
  if (!username || !password) {
    throw new Error(`User "${key}" is missing username or password in worldParameters.users`);
  }
  await attempt(async () => {
    // Heavy Bootstrap front-end theme: navigate with domcontentloaded only and do
    // not load the (heavy) /user page to verify; the admin-page steps reveal a
    // failed login by hitting an access-denied page.
    await this.context.clearCookies();
    await gotoUrl(this.page, `${this.parameters.launchUrl}/user/login`);
    await this.page.locator('#edit-name').fill(username);
    await this.page.locator('#edit-pass').fill(password);
    await Promise.all([
      this.page.waitForURL((url) => !/\/user\/login/.test(String(url)), { timeout: 30000 }).catch(() => {}),
      this.page.locator('#edit-submit').click(),
    ]);
    await settle(this.page);
  }, `Could not log in as "${key}"`);
});

/**
 * Drop back to an anonymous session by clearing every cookie.
 *
 * Example: Given I am an anonymous visitor
 */
Given(/^(?:I |we )?am an anonymous visitor$/, async function () {
  await attempt(async () => {
    await this.context.clearCookies();
  }, 'Could not clear the session to become anonymous');
});

/**
 * Create a Basic page through the node-add form (Varbase Page content type).
 *
 * Example: When I create a basic page titled "Varbase Core test page"
 */
When(/^(?:I |we )?create a basic page titled "([^"]*)"$/, async function (title) {
  await attempt(async () => {
    await gotoUrl(this.page, `${this.parameters.launchUrl}/node/add/page`);
    await this.page.locator('#edit-title-0-value').fill(title);
    await this.page.locator('#edit-submit').click();
    await settle(this.page);
  }, `Could not create a basic page titled "${title}"`);
});

/**
 * Open an administration page and assert it is reachable.
 *
 * Uses the webship-js smart-wait helpers (gotoUrl + waitForPageLoad) so heavy
 * Varbase admin pages are fully settled before the assertion, and reports any
 * access-denied / not-found / fatal-error page with a tester-friendly message.
 *
 * Example: When I open the administration page "/admin/config"
 */
When(/^I open the administration page "([^"]*)"$/, async function (path) {
  await attempt(async () => {
    await gotoUrl(this.page, `${this.parameters.launchUrl}${path}`);
    await settle(this.page);
    const bad = await this.page.locator(
      'h1:has-text("Access denied"), h1:has-text("Page not found"), h1:has-text("The website encountered an unexpected error")'
    ).count();
    if (bad > 0) {
      throw new Error(`The page "${path}" returned an access-denied, not-found or error response`);
    }
  }, `Could not open the administration page "${path}"`);
});

const path = require('path');

/**
 * Attach a file from the suite's own tests/assets directory to a file input.
 *
 * webship-js resolves "attach the file" against its bundled assets folder, so
 * this step resolves against the project's tests/assets so committed fixtures
 * (e.g. flag-earth.jpg) can be uploaded.
 *
 * Example: When I attach the media file "flag-earth.jpg" to "#edit-field-media-image-0-upload"
 */
When(/^(?:I |we )?attach the media file "([^"]*)" to "([^"]*)"$/, async function (fileName, selector) {
  await attempt(async () => {
    const file = path.resolve(process.cwd(), 'tests', 'assets', fileName);
    await this.page.locator(selector).setInputFiles(file);
    await settle(this.page);
  }, `Could not attach the media file "${fileName}" to "${selector}"`);
});

/**
 * Fill a form field located by a raw CSS selector with a value.
 *
 * The webship-js "fill in ... for ..." step resolves fields by label; this step
 * fills by selector so fields with array-style names (e.g. redirect_source) can
 * be set reliably.
 *
 * Example: When I fill the field "#edit-redirect-source-0-path" with "old-seo-page"
 */
When(/^(?:I |we )?fill the field "([^"]*)" with "([^"]*)"$/, async function (selector, value) {
  await attempt(async () => {
    await this.page.locator(selector).fill(value);
  }, `Could not fill the field "${selector}" with "${value}"`);
});
