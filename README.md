# Hueman 7L WordPress Child Theme
Ein Child Theme für [Hueman Pro](https://presscustomizr.com/hueman-pro/), für die WordPress-Installation des Ökodorfs Sieben Linden (siebenlinden.org).

Ursprünglich von Felix Wolfsteller (2016–2022), 2025 neu aufgebaut von Matthias Drees. Veröffentlicht unter der GPLv3+.

Siehe die [Liste der Mitwirkenden](https://github.com/ecovillage/hueman-7l-child/graphs/contributors).

## Installation

Voraussetzung ist das installierte Parent-Theme Hueman Pro.

Das Child Theme kann mit dem Plugin [Git Updater](https://git-updater.com/) installiert und aktualisiert werden.

## Enthaltene Dateien

### style.css

Theme-Header sowie CSS-Überschreibungen und eigene Regeln.

### functions.php

Lädt die Stylesheets von Parent- und Child-Theme sowie Flexslider, registriert den Shortcode `[featured_news]` und enthält Hilfsfunktionen für das Untermenü in `sidebar.php`.

### fullwidth-template.php

Seitentemplate „Fullwidth-Template“: Layout in voller Breite, Header und Navigation sind direkt eingebunden. Lädt das AOS-Stylesheet aus `/wp-content/uploads/7l-landing/`.

### landingpage-template.php

Seitentemplate „Landingpage-Template“: gibt nur den Inhalt aus dem Editor aus (rohes HTML), ohne Theme-Styling. Gedacht für eigenständig gestaltete Seiten wie die Landingpage.

### sidebar.php

Überschreibt die Sidebar des Parent-Themes. Zeigt ein Untermenü zum aktuellen Zweig des ersten Navigationsmenüs.

### content-featured.php

Markup eines einzelnen Beitrags im News-Slider.

### parts/featured.php

Flexslider für den Shortcode `[featured_news]`.

### parts/page-image.php

Beitragsbild einer Seite, mit dem Seitentitel als Bildunterschrift.

### parts/page-title.php

Überschreibt den Seitentitel-Teil des Parent-Themes.

## Shortcodes

  - `[featured_news]`: Zeigt einen Flexslider mit den neuesten Beiträgen der Kategorie „news“.

## Hueman-Einstellungen auf siebenlinden.org

*Achtung: Diese Angaben stammen noch aus der Zeit vor dem Neuaufbau 2025 und sind möglicherweise veraltet.*

  - Global Settings -> Identity -> Display Logo (X), max-height: 145
  - Global Settings -> Identity -> no tagline
  - Global Settings -> General Design -> Sidebar Padding for Widgets: 20px
  - Global Settings -> General Design -> Primary color #c9d30e
  - Global Settings -> General Design -> Secondary color #f29400
  - Global Settings -> General Design -> Topbar Background : #cad133
  - Global Settings -> General Design -> Header Background : #f5f5f5
  - Comments -> Posts and Pages
  - Header -> Header Menu -> no default
  - Header -> Design -> no tagline
  - Content -> Front page: static
  - Footer -> Credit Text

Blog-Design und -Inhalte sind nicht in allen Versionen verfügbar.

## Ressourcen

### Template-Hierarchie

[wphierarchy](https://wphierarchy.com/) hilft beim Nachschlagen, welches Template WordPress wann verwendet.

### Debugging

In `wp-config.php`:
```
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

- Mit `error_log` in die Standard-Logdatei schreiben (`wp-content/debug.log`).
- Mit `json_encode()` komplexe Objekte als JSON ausgeben.
- Mit `print_r` Datenstrukturen ausgeben.
- Auch `var_dump` hilft bei Variablen und Datenstrukturen.

## Release

  - Version in `style.css` ändern und committen
  - `git tag -a VERSION -m VERSION`
  - `git push && git push --tags`

Releases sind also Git-Tags.

## Lizenz

Copyright 2016–2022 Felix Wolfsteller, 2025 Matthias Drees. Veröffentlicht unter der GPLv3+.
