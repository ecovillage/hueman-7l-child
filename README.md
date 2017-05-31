# Hueman 7L Wordpress Child Theme

A [hueman child theme](http://presscustomizr.com/hueman/) for the wordpress installation of the ecovillage Sieben Linden (siebenlinden.org).

Authored by Felix Wolfsteller, Copyright 2016, 2017, released under the GPLv3+.

i18n work by Götz Hendrik Wiegand.

Works together with custom wordpress plugin [ev7l-events](https://github.com/ecovillage/ev7l-events) .

The original and awesome Presscustomizr hueman theme source can also be [found at github](https://github.com/presscustomizr/hueman).

## Installation

Fit to be used with the [github updater plugin](https://github.com/afragen/github-updater/wiki/Installation).

## Files Included

### single-ev7l-event.php

View of a single event.

### archive-ev7l-event.php

View list of events (this could become the calendar).

### single-ev7l-event-category.php

View of a single event category (listing events).

### single-ev7l-referee.php

View of a single referee (listing events).

### page.php

Standard page, with heading above image.

### page-calendar.php

Calendar page, if needed.  In order for it to work, you'll need a page called ... 'calendar'.

### content-featured-custom.php

For 'featured-news-slider' (used by shortcode), do not display the category.  Derived from content-featured.php (vanilla hueman 3.3.4).

### parts/single-author-date.php

Overwritten from vanilla hueman (3.3.4) - do not display author and date in 'betrieb' single view.

### parts/post-list-author-date.php

Overwritten from vanilla hueman (3.3.4) - do not display author and date in 'betrieb' archive view.

### content.php

Overwritten from vanilla hueman (3.3.4) - do not category if about in a 'betrieb' post (over)view.


### style.css

Main css overrides and new definitions.

### functions.php

Enough emptiness for everybody.  Now, things start to happen here.

### parts/registration.php

Registration form, mailer and logic, integrated into legacy system.

## About registrations

Will be put in pseudo-randomly named files (for legacy database, json import) in the `registrations` subdirectory of your wordpress home base.  You should put a `.htaccess` file there and instruct your webserver to not let anybody come close to that data.

##### [featured_news]

Shows a flexslider running through latest posts of the 'news' category.

##### [upcoming_events]

Shows an 'alx' style list of upcoming events, much like in the sidebar widget of the ev7l-events plugin.

##### [pages_list]

Shows child pages of a certain page as in the two-column post list.
Example: `[pages_list parent_name="My Parent Page Title"]`

### screenshot.png

Backend theme listing image.

## Hueman settings at siebenlinden.org

Global Settings -> Identity -> Display Logo (X), max-height: 145
Global Settings -> Identity -> no tagline
Global Settings -> General Design -> Sidebar Padding for Widgets: 20px
Global Settings -> General Design -> Primary color #c9d30e
Global Settings -> General Design -> Secondary color #f29400
Global Settings -> General Design -> Topbar Background : #cad133
Global Settings -> General Design -> Header Background : #f5f5f5
Comments -> Posts and Pages
Header -> Header Menu -> no default
Header -> Design -> no tagline
Content -> Front page: static

Footer -> Credit Text

Blog design and content not available in all versions.

## Release

  - Change version in style.css and commit
  - git tag -a VERSION -m VERSION
  - git push && git push --tags


