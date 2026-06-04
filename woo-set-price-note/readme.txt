=== Set Price Note (Units, Offers, Editions) for WooCommerce ===
Contributors: shivashankerbhatta, afthemes
Donate link: https://github.com/shshanker/woo-set-price-note
Tags: suffix, label, unit, woocommerce, wholesale
Requires at least: 5.0
Tested up to: 7.0.0
Stable tag: 3.0.2
Requires PHP: 7.4
WC requires at least: 8.5
WC tested up to: 10.8.1
Requires Plugins: woocommerce
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Eliminate pricing ambiguity by adding custom units, measurement metrics, and marketing notes directly beside your product prices.

== Description ==

Is a missing piece of pricing context costing your store valuable conversions? 

When digital shoppers browse a storefront and encounter a flat price string like **$120.00** without a clear indicator explaining whether it signifies *per box*, *per square meter*, *per dozen*, or *minimum 3 items required*, cognitive friction occurs immediately. In electronic commerce, user hesitation represents the primary root cause of abandoned cart funnels. If your buyers do not know exactly what they are paying for, they will not finalize checkout.

**Set Price Note for WooCommerce** provides a precise, robust, and performance-optimized solution engineered specifically to eliminate layout informational gaps. Maintained and engineered by Shiva Shanker Bhatta and the specialized engineering group at **AF Themes**, this utility empowers digital merchants to define entirely custom text templates, descriptive wholesale suffixes, or promotional marketing contexts directly adjacent to default core currency displays.

Unlike unoptimized code injection snippets that cause design regressions or directly alter database core pricing arrays, this plugin hooks cleanly into the native WooCommerce `woocommerce_get_price_html` visualization filter loop. This foundational architectural strategy guarantees your pricing context renders flawlessly across your site's archive query grids, individual single product viewports, related product slider modules, transactional shopping cart rows, checkout evaluation workflows, and automated customer outgoing email invoices. It delivers this display transparency safely without causing validation errors in your database schema or introducing validation conflicts with active theme templates.

### How It Drives Store Growth
* **Drastically Reduces Customer Support Backlogs:** Cut off repetitive support ticket queries regarding product counts, package metrics, minimum volumes, or subscription bounds before they happen.
* **Streamlines Complex B2B & Wholesale Configurations:** Explicitly communicate multi-pack definitions, wholesale weight parameters, minimum threshold commitments, or specific tax inclusions inline, removing barriers for commercial corporate procurement.
* **Maintains Core Checkout Visibility:** Build complete transactional transparency by automatically synchronizing your custom pricing notes from initial point-of-sale displays directly into mini-carts, sliding drawers, main cart loops, and email alerts.
* **Optimized for Maximum Infrastructure Performance:** Developed following stringent WordPress core coding standards with an explicitly zero-bloat methodology. Your site's loading velocity, DOM depth metrics, and Core Web Vitals rankings remain completely unaffected.

---

### Take Total Control with Set Price Note Pro
While the free repository core handles single product-level configurations beautifully, scaling storefronts managing medium to large catalogs require complex visual design controls, global management frameworks, and dynamic automation filters. Upgrading to **Set Price Note Pro** transforms your pricing layout strategy into a highly automated, conversion-optimized store engine:

* **Category & Global Mass Assignment Rules:** Stop spending hours manually editing inventory items one by one. Build dynamic bulk-assignment execution logic to append identical price suffix formulas across hundreds of specific items, chosen tags, or global product categories instantly.
* **B2B User Role Context Filtering:** Deliver the right contextual pricing message to the right target audience. Display standard storefront consumers a retail-appropriate text note (e.g., *"Inc. Tax / Free Shipping"*), while dynamically flashing custom pricing terms to authenticated commercial profiles (e.g., *"/ Case of 24 Ex. VAT"*).
* **Dynamic Variable Suffix Switching via AJAX:** Do you manage single product layouts containing complex inventory packaging size options? Set Price Note Pro monitors selection choices and updates descriptive text suffixes in real-time using asynchronous AJAX handlers as users select item options (e.g., shifting dynamically from *"/ Single Unit"* to *"/ Case of 12"*).
* **No-Code Visual Style Dashboard:** Match your layout style without writing manual CSS styling modifications. Tweak exact font weights, text sizing scales, line spacings, outer margins, and specific brand hex colors right from a native administrative control screen.
* **Advanced Modern Block Placements:** Ensure layout consistency by forcing or selectively masking custom pricing text within contemporary interactive side drawers, multi-tier checkout layouts, and full Gutenberg-based WooCommerce Cart & Checkout components.
* **Geographical Tax Compliance Automation:** Intelligently swap regional pricing text suffixes based on customer geolocation coordinates to maintain strict transparency compliance across different international trade zones.

---

### Explore the AF Themes Ecosystem
Accelerate your overall business operations and amplify store revenue by pairing Set Price Note with our additional curated, lightweight e-commerce optimization instruments:
* **[Floating Minicart for WooCommerce:](https://wordpress.org/plugins/woo-floating-minicart/)** Remove traditional checkout funnel friction with a high-performance, slide-out side mini-cart drawer that keeps consumers anchored in your conversion pipeline.
* **[Total Sales Counts for WooCommerce:](https://wordpress.org/plugins/woo-total-sales/)** Leverage immediate, authentic conversion social proof by displaying real-time item velocity numbers across front-page store grids and descriptive single product modules.
* **[Premium Enterprise WordPress Themes:](https://afthemes.com/all-themes-plan/)** Discover our premium catalog of speed-optimized commercial themes built specifically to support corporate publishing, large news outlets, and high-volume WooCommerce installations on the [AF Themes Website](https://afthemes.com).

== Features ==

### Included in the Free Core Edition:
* **Product-Level Price Suffixes:** Append individual distinct text modifiers directly to simple, grouped, external, or variable product types on a per-item basis.
* **Global Character Separators:** Choose your preferred display break token (including `/`, `-`, `|`, or basic line spaces) to create clean visual parsing between numerical values and text descriptors.
* **Transactional Document Synchronization:** Toggle with a single click whether your specific layout messages append into customer invoices and shipment confirmation alerts.
* **Cart & Checkout Visibility Overrides:** Selectively show or hide your custom pricing labels inside standard cart loop tables, multi-step checkout layouts, and order overview modules.
* **Fully Translation & Translation Ready:** Complete integration with standard localized configuration techniques. Packaged with structurally sound, clean `.pot` language templates to support effortless setup with multilingual setups.
* **Developer Extensibility Hooks:** Documented action routines and filter functions allowing technical engineers to safely modify display strings programmatically via external functions.

### Exclusive to the Pro Plan Upgrade:
* Global rules, custom category targeted bulk automation management layouts.
* Full real-time variation switching via AJAX handlers.
* Dedicated User Role conditional display logic matrices.
* Point-and-click typographic style control dashboard (fonts, colors, bounds, scales).
* Native compatibility support for modern Block-based Gutenberg Cart/Checkout interfaces.
* Priority developer engineering support channels and automated site updates.

== Installation ==

### Automated Dashboard Installation
1. Access your internal WordPress administrative control panel and navigate to **Plugins > Add New**.
2. Input **"Set Price Note for WooCommerce"** directly into the upper right keyword search criteria box.
3. Locate our official module, click the **Install Now** button, allow the download process to complete, and select **Activate**.

### Manual Server Deployment
1. Download the complete technical zip archive bundle (`woo-set-price-note.zip`) straight from the official WordPress.org plugin index directory.
2. Connect to your host server utilizing your preferred secure FTP application or control panel manager.
3. Extract and transfer the uncompressed plugin directory path directly into your environment's deployment target folder: `/wp-content/plugins/`.
4. Open your dashboard **Plugins** panel index list, find the module within your local table rows, and click **Activate**.

### Initial Quick-Start Configurations
1. Access the global path **WooCommerce > Price Note Settings** to choose your default separation character string (such as `/` or ` - `).
2. Open and edit any individual product listing within your electronic catalog index.
3. Scroll down into your product parameters and select the newly created **Price Note** panel option tab located on the side list of the Product Data metabox.
4. Fill out your measurement metrics (e.g., *Per Box*, *Per Meter*, *Pack of 12*) or your limited time promotional phrases.
5. Save or update your master product changes, clear your caching layer, and load the public frontend view to verify your display layout live!


== Frequently Asked Questions ==

= Does this plugin modify my actual product prices or break checkout payments? =
No. The core plugin functions strictly as a visual presentation filter by hooking directly into the standard WooCommerce execution layer (`woocommerce_get_price_html`). It dynamically appends text labels to the frontend price string without altering your raw database integers, mathematical cart subtotals, tax calculation rules, shipping formulas, or payment gateway processes.

= Will adding a price note conflict with my Google Shopping feed or SEO Schema? =
No. Because the visual mutations are applied dynamically at the theme rendering stage rather than modifying the underlying catalog metadata tables, your product structured data markup remains perfectly intact. Automated scraping indexers, Google Merchant Center feeds, Facebook Catalog syncs, and Schema Rich Snippets will continue to read your exact numerical pricing figures cleanly.

= Can I use these custom labels on variable products and variations? =
The free core version lets you assign a global price note to the parent variable product container, which displays cleanly across your shop archives. If your store requires individual, distinct price suffixes for each specific variation (e.g., small size sold "per piece" and large size sold "per box") that update automatically via AJAX on the frontend when an attribute changes, you will want to upgrade to Set Price Note Pro.

= Does it support modern WooCommerce Gutenberg Cart and Checkout Blocks? =
Yes. The plugin is built following modern WordPress development paradigms and functions seamlessly across classical template shortcodes as well as contemporary Full Site Editing (FSE) block configurations. For advanced, deeply nested styling overrides within modern Gutenberg blocks, the Pro version offers dedicated integration layouts.

= Can I hide the price suffix on specific store layouts like Cart or Emails? =
Yes. The plugin features a built-in administrative control panel under WooCommerce Settings allowing you to granularly toggle visibility parameters. You can choose whether your custom measurement labels carry over into shopping cart tables, checkout checkout steps, final invoice screens, or automated transactional customer email templates.

= Is the plugin compatible with multi-currency and wholesale role extensions? =
The free core version applies your custom strings universally per product. If you require advanced conditional visibility logic—such as showing one text suffix to standard retail customers and a different wholesale notation (like "Ex. VAT") to verified business user roles, or adapting labels dynamically across active multi-currency switchers—Set Price Note Pro includes built-in conditional rules for role-based and regional marketing.

= Is it translation-ready for multilingual WooCommerce stores? =
Absolutely. The codebase is fully internationalized (i18n compliant) and includes a clean structural template `.pot` file. You can easily translate your pricing labels, separators, and dashboard settings into any language using standard translation tools like Loco Translate, WPML, or Polylang.

= How do I upgrade to the automated Pro features? =
You can explore our advanced bulk automation workflows directly through your local dashboard options tab or visit the official AF Themes website (https://afthemes.com) to instantly secure a premium commercial license key.

== Screenshots ==

1. A standard category grid layout showing an explicit unit label suffix ("/ box") right next to product prices.
2. A single product layout displaying a custom promotional text note ("- Limited Edition Offer").
3. Review screen inside the checkout page demonstrating how pricing notes stay consistent all the way to final payment.
4. An example of a customer invoice email where your custom labels carry cleanly into the order summary tables.
5. The single product metadata tab showing how simple it is to configure your labels, separators, and document display options.

== Changelog ==

== Changelog ==

= 3.0.0 =
* Enhancement: Hardened core administrative security protocols with strict data sanitization layer, explicit input validation, and secure cryptographic nonces across all individual meta rows.
* Fix: Completely redesigned the single product editing experience, introducing an interactive settings panel container nested seamlessly inside the native WooCommerce product data meta box.
* Fix: Added an optimized layout header link to bridge the interface gap between granular single-product parameters and the central global settings management panel.
* Performance: Refactored database option retrieval routines to dynamically fallback onto a standardized production default matrix (`get_default_settings`), mitigating overhead.
* Tested: Declared full technical framework verification and core stability benchmarking up to WordPress 7.0.0 and WooCommerce 10.8.1.

= 2.1.0 =
* Performance: Completely refactored core loop executions to drop database memory usage on archive pages down to zero.
* Fix: Resolved a minor edge case string conflict when running alongside multi-currency exchange extensions.
* Update: Refreshed core localization framework string matrix mappings.

= 2.0.4 =
* Optimization: Added precise internal caching for heavy variable product configuration strings.
* Feature: Introduced custom label override options for checkout document rows inside the administration screen.
* UI: Updated dashboard options styling to perfectly align with native WordPress design languages.

== Upgrade Notice ==

= 3.0.0 =
This version adds full compatibility with WooCommerce 10.8.1, modern block themes, and security hardening for database transactions. Upgrading is highly recommended.