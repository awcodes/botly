---
title: Usage
description: Manage crawler rules, sitemaps and AI crawler blocks from the Robots Manager page.
---

# Usage

Everything Botly serves is managed from the **Robots Manager** page in your panel. The page has three sections — rules, sitemaps and AI crawler blocking — and saves them all together.

![The Robots Manager page, with a persistent rule, six editable rules, two sitemap URLs and four blocked AI crawlers](assets/robots-manager-light.png#gh-light-mode-only)
![The Robots Manager page, with a persistent rule, six editable rules, two sitemap URLs and four blocked AI crawlers](assets/robots-manager-dark.png#gh-dark-mode-only)

## Rules

A rule is a single directive aimed at a single user agent. Each rule has three fields:

| Field | Values |
|---|---|
| User-Agent | Any string, for example `*` or `Googlebot` |
| Directive | `Allow`, `Disallow`, `Crawl-delay` or `Clean-param` |
| Path | The path the directive applies to, for example `/admin` |

![The rules repeater, with Disallow, Clean-param, Allow and Crawl-delay rules for the *, Googlebot and Bingbot user agents](assets/rules-light.png#gh-light-mode-only)
![The rules repeater, with Disallow, Clean-param, Allow and Crawl-delay rules for the *, Googlebot and Bingbot user agents](assets/rules-dark.png#gh-dark-mode-only)

New rules default to a user agent of `*` and a directive of `Disallow`. All three fields are required.

Rules are grouped by user agent in the output, so several rules aimed at the same agent are emitted under a single `User-agent` line:

```text
User-agent: *
Disallow: /admin
Disallow: /private

User-agent: Googlebot
Allow: /
```

Rules defined in code as persistent rules also appear in this list, but cannot be edited or removed here. See [Configuration](configuration.md) for how to define them.

## Sitemaps

The sitemaps section takes a list of full sitemap URLs. Each one is emitted as its own `Sitemap:` line at the end of the output:

```text
Sitemap: https://example.com/sitemap.xml
```

![The sitemaps repeater, with two sitemap URLs on example.com](assets/sitemaps-light.png#gh-light-mode-only)
![The sitemaps repeater, with two sitemap URLs on example.com](assets/sitemaps-dark.png#gh-dark-mode-only)

## Blocking AI crawlers

The **Block AI Crawlers** section is a checkbox list of known AI bots. Checking one adds a `Disallow: /` entry for it:

```text
User-agent: GPTBot
Disallow: /
```

![The Block AI Crawlers checkbox list, with GPTBot, ClaudeBot, CCBot and Bytespider checked](assets/ai-crawlers-light.png#gh-light-mode-only)
![The Block AI Crawlers checkbox list, with GPTBot, ClaudeBot, CCBot and Bytespider checked](assets/ai-crawlers-dark.png#gh-dark-mode-only)

Botly ships with a curated list that includes GPTBot, ChatGPT-User, OAI-SearchBot, ClaudeBot, anthropic-ai, claude-web, PerplexityBot, Perplexity-User, Google-Extended, Applebot-Extended, Bytespider, CCBot and others.

> [!NOTE]
> Blocking a crawler in `robots.txt` is a request, not an enforcement mechanism. Well-behaved crawlers honour it; nothing stops one that does not.

## Exporting a static file

The **Export Robots.txt** action writes the current output to `public/robots.txt` as a static file.

This is useful if you want to stop serving the file dynamically, or want a snapshot committed to your repository. Be aware of the trade-off: once that file exists, your web server serves it directly and further changes made on the admin page will have no effect until you delete it or export again.
