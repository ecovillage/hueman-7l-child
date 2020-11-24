# Hueman 7L Wordpress Child Theme

A [hueman child theme](http://presscustomizr.com/hueman/) for the wordpress installation of the ecovillage Sieben Linden (siebenlinden.org).

Authored by Felix Wolfsteller, Copyright 2016, 2017, 2018, 2019, 2020 released under the GPLv3+. Original content in `seminardesk-wordpress-custom` is Copyright 2020 SeminarDesk – Danker, Smaluhn & Tammen GbR .

See a [list of contributors here](https://github.com/ecovillage/hueman-7l-child/graphs/contributors).

The ecovillage hosts a range of educational seminars and events, prior to 2021
these were managed using a self built application, where event data was pushed
to wordpress and handled with the with custom wordpress plugin [ev7l-events](https://github.com/ecovillage/ev7l-events) .

In 2020 the decision was done to switch to a proprietary, non-visionary
un-community ( :( ) solution, which
uses a different plugin.

The original and awesome Presscustomizr hueman theme source can also be [found at github](https://github.com/presscustomizr/hueman).

## Installation

Fit to be used with the [github updater plugin](https://github.com/afragen/github-updater/wiki/Installation).

## Files Included

### single-ev7l-event.php

View of a single event.

### archive-ev7l-event.php

View list of events (this could become the calendar).

### sd-facilitators-list.php

A template to be used (create a page with this template) to show alphabetically
sorted referees. Because seminardesk-wordpress plugin overrides archive
generation.

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

## seminardesk-custom/*

Template files overridden from plugin for proprietary event booking solution.

## includes/SDTemplateUtils.php

Helpers for the proprietary solution.

## includes/shortcodes.php

Helpers for the proprietary solution.

## Provided shortcodes

  - featured_news
  - upcoming_events
  - pages_list
  - event_calendar
    use like
    `[event_calendar]`. Optional arguments are `year(="2019")` and `month(="11")`.
    Will render an unsorted list, with elements in `parts/event_list_line`.
  - event_calendar_this_year_past
  - event_calendar_this_year_upcoming
  - event_registration_form (eventuuid)

... to be explained

## About registrations

### Prior to 2021
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

## Update to match upstream stable hueman theme

The original Presscustomizr hueman theme ([source](https://github.com/presscustomizr/hueman)) still receives updates (as of late 2019). To integrate changes and fixes, this Child Theme has to be updated every once in a while.

The process is yet unclear and a WIP, relevant scripts might be placed in a runbook at https://github.com/ecovillage/operations .

### WIP doc

The "current" version of the upstream Hueman (the "parent") theme that this child theme is based on needs to be know.
For now, its placed in `HUEMAN_BASE_VERION`

  * we need to know which files are based of which parent files:
    * get list of files `git ls-files | sort > FILES`
  * add a sorted list of files that are included but independent of parent version
    * (FILES.ignore)
  * also CSS rules need to be checked


## Update translations

Most texts are translatable (for translations, look in the `languages` folder).

### Graphically: Use poedit
  - `poedit`
  - (Initial setup) File > Catalog Manager > New : Project Name = hueman-child-7l; Directories = Browse
  - File > Catalogs Manager > Update all
  - (edit)
  - Save and Update


### For you:
  - to update the compiled catalog: `msgfmt catalog.po -o catalog.mo` (for ubuntu/debian: in gettext package).
  - in the concrete example: `msgfmt de_DE.po -o de_DE.mo`


### Resources

#### Looking for the Hierarchy?

[wphierarchy](https://wphierarchy.com/) comes in handy sometimes.

## Release

  - Change version in style.css and commit
  - git tag -a VERSION -m VERSION
  - git push && git push --tags

That means, the releases are tags in git (typical setup).

## License

Authored by Felix Wolfsteller, Copyright 2016, 2017, 2018, 2019, 2020 released under the GPLv3+.
Original content in `seminardesk-wordpress-custom` is Copyright 2020 SeminarDesk – Danker, Smaluhn & Tammen GbR .

