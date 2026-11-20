---
topic: WP-CLI Workflows and WordPress MCP Adapter Setup
source_url: https://github.com/wordpress/mcp-adapter
last_synced: 2026-11-20
---

# WP-CLI Workflows & WordPress MCP Adapter Configuration

## 1. Essential WP-CLI Operational Commands

WP-CLI provides deterministic command-line control over WordPress core, themes, plugins, and Elementor.

### Theme & Plugin Lifecycle
```bash
# List and activate themes
wp theme list
wp theme activate agency-theme

# Manage companion and builder plugins
wp plugin list
wp plugin install elementor --activate
wp plugin install woocommerce advanced-custom-fields --activate
wp plugin is-active elementor && echo "Elementor ready"
```

### Elementor CLI Maintenance
```bash
# Regenerate compiled widget CSS files after layout/style migrations
wp elementor flush-css

# Safe URL search-replace updating Elementor JSON stringified postmeta
wp elementor replace-urls https://old-domain.local https://new-domain.local
```

### Database & Auth Management
```bash
# Database export & safe search-replace
wp db export backup.sql
wp search-replace "http://staging.local" "https://production.com" --all-tables

# Create Application Password for MCP authentication
wp user application-password create admin "AI-Agent-Session"
```

---

## 2. Official WordPress MCP Adapter (`WordPress/mcp-adapter`)

The official WordPress MCP Adapter exposes WordPress **Abilities** as standardized Model Context Protocol (MCP) tools, resources, and prompts.

### Activating MCP Access for Core Abilities

By default, abilities require explicit opt-in for the default MCP server via the `meta.mcp.public` flag. In a companion plugin or mu-plugin:

```php
declare(strict_types=1);

add_filter( 'wp_register_ability_args', static function( array $args, string $ability_name ): array {
    $core_abilities = [
        'core/get-site-info',
        'core/get-user-info',
        'core/get-environment-info',
    ];

    if ( in_array( $ability_name, $core_abilities, true ) ) {
        $args['meta']['mcp']['public'] = true;
    }

    return $args;
}, 10, 2 );
```

---

## 3. MCP Client Configuration

### Transport A: Local STDIO via WP-CLI (Recommended for Local Dev)

STDIO executes WP-CLI directly inside the local environment without network latency or HTTP token overhead.

```json
{
  "mcpServers": {
    "wordpress": {
      "command": "wp",
      "args": [
        "--path=/var/www/html",
        "mcp-adapter",
        "serve",
        "--server=mcp-adapter-default-server",
        "--user=admin"
      ]
    }
  }
}
```

### Transport B: HTTP Remote Proxy (Staging & Production)

Connects to the WordPress REST API endpoint via `@automattic/mcp-wordpress-remote` using Application Passwords:

```json
{
  "mcpServers": {
    "wordpress-remote": {
      "command": "npx",
      "args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
      "env": {
        "WP_API_URL": "https://example.com/wp-json/mcp/mcp-adapter-default-server",
        "WP_API_USERNAME": "admin",
        "WP_API_PASSWORD": "xxxx xxxx xxxx xxxx"
      }
    }
  }
}
```

---

## 4. Security & Permissions Checklist

1. **Granular Capabilities:** Abilities inherit WordPress capabilities (`edit_posts`, `manage_options`). Never assign administrator-only abilities to unauthenticated clients.
2. **Dedicated User Account:** Create an isolated WordPress user (e.g. `ai_agent`) possessing only necessary capabilities.
3. **Environment Security:** Never commit `.mcp.json` containing credentials or Application Passwords into version control.
