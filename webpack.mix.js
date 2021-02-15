const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/* mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css'); */
mix.styles([
    'public/css/absence.css',
    'public/css/admin.css',
    'public/css/anketa.css',
    'public/css/basic.css',
    'public/css/calendar.css',
    'public/css/calendar2.css',
    'public/css/campaign.css',
    'public/css/dashboard.css',
    'public/css/documents.css',
    'public/css/index.css',
    'public/css/layout.css',
    'public/css/modal.css',
    'public/css/responsive_top_nav.css',
    'public/css/slide_show.css',
    'public/css/travel_show.css',
    'public/css/welcome.css',
    
], 'public/css/all2.css');
mix.scripts([
    'public/js/absence.js',
    'public/js/ads.js',
    'public/js/benefit.js',
    'public/js/calendar.js',
    'public/js/campaign.js',
    'public/js/campaign_sequences.js',
    'public/js/campaign_sequences_edit.js',
    'public/js/campaign_sequences_show.js',
    'public/js/chart.js',
    'public/js/collaps.js',
    'public/js/datatables.js',
    'public/js/datatables_evidention.js',
    'public/js/department.js',
    'public/js/documents.js',
    'public/js/efc_toggle.js',
    'public/js/event.js',
    'public/js/event_click.js',
    'public/js/event_create_show.js',
    'public/js/filter.js',
    'public/js/filter_dropdown.js',
    'public/js/filter_table.js',
    'public/js/go_value.js',
    'public/js/load_calendar.js',
    'public/js/nav.js',
    'public/js/nav_active.js',
    'public/js/nav_button_color.js',
    'public/js/notice_create.js',
    'public/js/notice_edit.js',
    'public/js/open_admin.js',
    'public/js/open_modal.js',
    'public/js/posts.js',
    'public/js/profile.js',
    'public/js/questionnaire.js',
    'public/js/questionnaire_show.js',
    'public/js/sequence_dragDrop.js',
    'public/js/setheight.js',
    'public/js/setheightcampaign.js',
    'public/js/setheightnotice.js',
    'public/js/show_noticeboard.js',
    'public/js/slide_show.js',
    'public/js/template_create.js',
    'public/js/template_edit.js',
    'public/js/tinymce.js',
    'public/js/travel.js',
    'public/js/unlayer.js',
    'public/js/upload_page.js',
    'public/js/user_profile.js',
    'public/js/users.js',
    'public/js/validate.js',
    'public/js/validate_doc.js',
    'public/js/validate_user.js',
    'public/js/validate_user_edit.js',
    'public/js/work_records.js',
], 'public/js/all2.js');

/* mix.js('resources/assets/laravel-echo-setup.js', 'public/js'); */