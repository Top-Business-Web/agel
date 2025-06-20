<?php

use App\Models\Branch;
use App\Models\Investor;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PlanSubscription;
use App\Models\Vendor;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

if (!function_exists('get_file')) {
    function getFile($image): string
    {
        if ($image != null) {
            return asset($image);
        } else {
            return asset('assets/uploads/empty.png');
        }
    }


    if (!function_exists('getKey')) {
        function getKey(): array
        {
            $arr = [
                'عرض',
                'إنشاء',
                'تحديث',
                'حذف',
            ];
            return $arr;
        }
    }


    if (!function_exists('getCreated')) {

        function getCreated($key)
        {
            $parentId = auth('vendor')->user()->parent_id === null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
            $vendors = Vendor::where('parent_id', $parentId)->get();
            $vendors[] = Vendor::where('id', $parentId)->first();
            $vendorIds = $vendors->pluck('id');


            if ($key == 'Vendor') {
                $created = Vendor::where('parent_id', $parentId)->count();
                return $created;
            } elseif ($key == 'Branch') {
                $created = Branch::whereIn('vendor_id', $vendorIds)->count();
                return $created;
            } elseif ($key == 'Investor') {
                $created = Investor::whereIn('Branch_id', Branch::whereIn('vendor_id', $vendorIds)->pluck('id'))->count();
                return $created;
            } elseif ($key == 'Order') {
                $created = Order::whereIn('vendor_id', $vendorIds)->count();
                return $created;

            }
        }
    }


}


// check if vendor still in plan limit and plan is not expired
if (!function_exists('checkVendorPlanLimit')) {

    function checkVendorPlanLimit($key)
    {
        $parentId = auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;

        $parent = Vendor::where('id', $parentId)->first();

        $plan = Plan::where('id', $parent->plan_id)->first();

        if ($plan) {
            $planDetails = $plan->details;
            if ($key == 'Investor') {
                if ($planDetails->where('key', 'Investor')->first()->value > getCreated('Investor')) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($key == 'Vendor') {
                if ($planDetails->where('key', 'Vendor')->first()->value > getCreated('Vendor')) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($key == 'Branch') {
                if ($planDetails->where('key', 'Branch')->first()->value > getCreated('Branch')) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($key == 'Order') {
                if ($planDetails->where('key', 'Order')->first()->value > getCreated('Order')) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        return false;
    }
}


if (!function_exists('getAuthSetting')) {
    function getAuthSetting($key)
    {
//        $setting = Setting::where('vendor_id', Auth::guard('vendor')->user()->id)->get();
//
//        if ($setting->isEmpty()) {
//            $setting = Setting::where('vendor_id', Auth::guard('vendor')->user()->parent_id)->get();
//        }
        $key = Setting::where('key', $key)->first();
//        dd($key);

        if ($key) {
//            dd( $key->value);
            return $key->value;

        } else {
//            dd( 'not found');

            return 'assets/uploads/empty.png';
        }
    }
}


if (!function_exists('getFileWithName')) {
    function getFileWithName($name): string
    {
        $firstWord = preg_split('/\s+/', trim($name))[0];

        $firstLetter = mb_substr($firstWord, 0, 1, 'UTF-8');

        return 'https://ui-avatars.com/api/?background=009FE3&bold=true&color=ffff&name=' . urlencode($firstLetter);
    }
}
if (!function_exists('routeActive')) {

    function routeActive($routeName, $class = 'active')
    {

        if (Route::currentRouteName() == $routeName) {
            return $class;
        }
    }
}
if (!function_exists('arrRouteActive')) {

    function arrRouteActive($routesName, $class = 'is-expanded')
    {
        if (in_array(Route::currentRouteName(), $routesName)) {
            return $class;
        }
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return auth()->guard('admin');
    }
}
if (!function_exists('setting')) {
    function setting()
    {
        return \App\Models\Setting::first();
    }
}

if (!function_exists('loggedAdmin')) {
    function loggedAdmin($field = null)
    {
        return auth()->guard('admin')->user()->$field;
    }
}

if (!function_exists('user')) {
    function user()
    {
        return auth()->guard('user');
    }
}

if (!function_exists('lang')) {

    function lang()
    {

        return Config::get('app.locale');
    }
}


if (!function_exists('flang')) {

    function flang($en, $ar)
    {
        if (lang() == 'ar')
            return $ar;
        else
            return $en;
    }
}


if (!function_exists('trans_model')) {

    function trans_model($model, $word)
    {

        return $model->{$word . '_' . app()->getlocale()};
    }
}

//if (!function_exists('trns')) {
//    function trns($key)
//    {
//        $path = resource_path("lang/en/file.php");
//
//        // Ensure the language file exists
//        if (!File::exists($path)) {
//            File::put($path, "<?php\n\nreturn [];\n");
//        }
//        $translations = include $path;
//        // Convert key to human-readable format
//        $value = ucwords(str_replace('_', ' ', $key));
//
//        if (!array_key_exists($key, $translations)) {
//            $translations[$key] = $value;
//
//            // Save the translations back to the file
//            $exported = var_export($translations, true);
//            File::put($path, "<?php\n\nreturn {$exported};\n");
//            return trans('file.' . $key);
//        } else {
//            return trans('file.' . $key);
//        }
//    }
//}
if (!function_exists('latAndLong')) {
    function latAndLong($location)
    {
        if (strpos($location, ',') !== false) {
            $coordinates = explode(',', $location);
            $latitude = $coordinates[0];
            $longitude = $coordinates[1];
        } else {
            $latitude = null;
            $longitude = null;
        }

        return (object)[
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
}


function get_font_icons()
{
    $icons = array(
        0 => 'fab fa-500px',
        1 => 'fas fa-address-book',
        2 => 'fab fa-address-book-o',
        3 => 'fas fa-address-card',
        4 => 'fas fa-address-card-o',
        5 => 'fas fa-adjust',
        6 => 'fad fa-adn',
        7 => 'fas fa-align-center',
        8 => 'fas fa-align-justify',
        9 => 'fas fa-align-left',
        10 => 'fas fa-align-right',
        11 => 'fas fa-amazon',
        12 => 'fas fa-ambulance',
        13 => 'fas fa-american-sign-language-interpreting',
        14 => 'fas fa-anchor',
        15 => 'fas fa-android',
        16 => 'fas fa-angellist',
        17 => 'fas fa-angle-double-down',
        18 => 'fas fa-angle-double-left',
        19 => 'fas fa-angle-double-right',
        20 => 'fas fa-angle-double-up',
        21 => 'fas fa-angle-down',
        22 => 'fas fa-angle-left',
        23 => 'fas fa-angle-right',
        24 => 'fas fa-angle-up',
        25 => 'fas fa-apple',
        26 => 'fas fa-archive',
        27 => 'fas fa-area-chart',
        28 => 'fas fa-arrow-circle-down',
        29 => 'fas fa-arrow-circle-left',
        34 => 'fas fa-arrow-circle-right',
        35 => 'fas fa-arrow-circle-up',
        36 => 'fas fa-arrow-down',
        37 => 'fas fa-arrow-left',
        38 => 'fas fa-arrow-right',
        39 => 'fas fa-arrow-up',
        40 => 'fas fa-arrows',
        41 => 'fas fa-arrows-alt',
        42 => 'fas fa-arrows-h',
        43 => 'fas fa-arrows-v',
        44 => 'fas fa-asl-interpreting',
        45 => 'fas fa-assistive-listening-systems',
        46 => 'fas fa-asterisk',
        47 => 'fas fa-at',
        48 => 'fas fa-audio-description',
        49 => 'fas fa-automobile',
        50 => 'fas fa-backward',
        51 => 'fas fa-balance-scale',
        52 => 'fas fa-ban',
        53 => 'fas fa-bandcamp',
        54 => 'fas fa-bank',
        55 => 'fas fa-bar-chart',
        56 => 'fas fa-bar-chart-o',
        57 => 'fas fa-barcode',
        58 => 'fas fa-bars',
        59 => 'fas fa-bath',
        60 => 'fas fa-bathtub',
        61 => 'fas fa-battery',
        62 => 'fas fa-battery-0',
        63 => 'fas fa-battery-1',
        64 => 'fas fa-battery-2',
        65 => 'fas fa-battery-3',
        66 => 'fas fa-battery-4',
        67 => 'fas fa-battery-empty',
        68 => 'fas fa-battery-full',
        69 => 'fas fa-battery-half',
        70 => 'fas fa-battery-quarter',
        71 => 'fas fa-battery-three-quarters',
        72 => 'fas fa-bed',
        73 => 'fas fa-beer',
        74 => 'fas fa-behance',
        75 => 'fas fa-behance-square',
        76 => 'fas fa-bell',
        77 => 'fas fa-bell-o',
        78 => 'fas fa-bell-slash',
        79 => 'fas fa-bell-slash-o',
        80 => 'fas fa-bicycle',
        81 => 'fas fa-binoculars',
        82 => 'fas fa-birthday-cake',
        83 => 'fas fa-bitbucket',
        84 => 'fas fa-bitbucket-square',
        85 => 'fas fa-bitcoin',
        86 => 'fas fa-black-tie',
        87 => 'fas fa-blind',
        88 => 'fas fa-bluetooth',
        89 => 'fas fa-bluetooth-b',
        90 => 'fas fa-bold',
        91 => 'fas fa-bolt',
        92 => 'fas fa-bomb',
        93 => 'fas fa-book',
        94 => 'fas fa-bookmark',
        95 => 'fas fa-bookmark-o',
        96 => 'fas fa-braille',
        97 => 'fas fa-briefcase',
        98 => 'fas fa-btc',
        99 => 'fas fa-bug',
        100 => 'fas fa-building',
        101 => 'fas fa-building-o',
        102 => 'fas fa-bullhorn',
        103 => 'fas fa-bullseye',
        104 => 'fas fa-bus',
        105 => 'fas fa-buysellads',
        106 => 'fas fa-cab',
        107 => 'fas fa-calculator',
        108 => 'fas fa-calendar',
        109 => 'fas fa-calendar-check-o',
        110 => 'fas fa-calendar-minus-o',
        111 => 'fas fa-calendar-o',
        112 => 'fas fa-calendar-plus-o',
        113 => 'fas fa-calendar-times-o',
        114 => 'fas fa-camera',
        115 => 'fas fa-camera-retro',
        116 => 'fas fa-car',
        117 => 'fas fa-caret-down',
        118 => 'fas fa-caret-left',
        119 => 'fas fa-caret-right',
        120 => 'fas fa-caret-square-o-down',
        121 => 'fas fa-caret-square-o-left',
        122 => 'fas fa-caret-square-o-right',
        123 => 'fas fa-caret-square-o-up',
        124 => 'fas fa-caret-up',
        125 => 'fas fa-cart-arrow-down',
        126 => 'fas fa-cart-plus',
        127 => 'fas fa-cc',
        128 => 'fab fa-cc-amex',
        129 => 'fab fa-cc-diners-club',
        130 => 'fab fa-cc-discover',
        131 => 'fab fa-cc-jcb',
        132 => 'fab fa-cc-mastercard',
        133 => 'fab fa-cc-paypal',
        134 => 'fab fa-cc-stripe',
        135 => 'fab fa-cc-visa',
        136 => 'fas fa-certificate',
        137 => 'fas fa-chain',
        138 => 'fas fa-chain-broken',
        139 => 'fas fa-check',
        140 => 'fas fa-check-circle',
        141 => 'fas fa-check-circle-o',
        142 => 'fas fa-check-square',
        143 => 'fas fa-check-square-o',
        144 => 'fas fa-chevron-circle-down',
        145 => 'fas fa-chevron-circle-left',
        146 => 'fas fa-chevron-circle-right',
        147 => 'fas fa-chevron-circle-up',
        148 => 'fas fa-chevron-down',
        149 => 'fas fa-chevron-left',
        150 => 'fas fa-chevron-right',
        151 => 'fas fa-chevron-up',
        152 => 'fas fa-child',
        153 => 'fas fa-chrome',
        154 => 'fas fa-circle',
        155 => 'fas fa-circle-o',
        156 => 'fas fa-circle-o-notch',
        157 => 'fas fa-circle-thin',
        158 => 'fas fa-clipboard',
        159 => 'fas fa-clock-o',
        160 => 'fas fa-clone',
        161 => 'fas fa-close',
        162 => 'fas fa-cloud',
        163 => 'fas fa-cloud-download',
        164 => 'fas fa-cloud-upload',
        165 => 'fas fa-cny',
        166 => 'fas fa-code',
        167 => 'fas fa-code-fork',
        168 => 'fas fa-codepen',
        169 => 'fas fa-codiepie',
        170 => 'fas fa-coffee',
        171 => 'fas fa-cog',
        172 => 'fas fa-cogs',
        173 => 'fas fa-columns',
        174 => 'fas fa-comment',
        175 => 'fas fa-comment-o',
        176 => 'fas fa-commenting',
        177 => 'fas fa-commenting-o',
        178 => 'fas fa-comments',
        179 => 'fas fa-comments-o',
        180 => 'fas fa-compass',
        181 => 'fas fa-compress',
        182 => 'fas fa-connectdevelop',
        183 => 'fas fa-contao',
        184 => 'fas fa-copy',
        185 => 'fas fa-copyright',
        186 => 'fas fa-creative-commons',
        187 => 'fas fa-credit-card',
        188 => 'fas fa-credit-card-alt',
        189 => 'fas fa-crop',
        190 => 'fas fa-crosshairs',
        191 => 'fas fa-css3',
        192 => 'fas fa-cube',
        193 => 'fas fa-cubes',
        194 => 'fas fa-cut',
        195 => 'fas fa-cutlery',
        196 => 'fas fa-Admin',
        197 => 'fas fa-dashcube',
        198 => 'fas fa-database',
        199 => 'fas fa-deaf',
        200 => 'fas fa-deafness',
        201 => 'fas fa-dedent',
        202 => 'fas fa-delicious',
        203 => 'fas fa-desktop',
        204 => 'fas fa-deviantart',
        205 => 'fas fa-diamond',
        206 => 'fas fa-digg',
        207 => 'fas fa-dollar',
        208 => 'fas fa-dot-circle-o',
        209 => 'fas fa-download',
        210 => 'fas fa-dribbble',
        211 => 'fas fa-drivers-license',
        212 => 'fas fa-drivers-license-o',
        213 => 'fas fa-dropbox',
        214 => 'fas fa-drupal',
        215 => 'fas fa-edge',
        216 => 'fas fa-edit',
        217 => 'fas fa-eercast',
        218 => 'fas fa-eject',
        219 => 'fas fa-ellipsis-h',
        220 => 'fas fa-ellipsis-v',
        221 => 'fas fa-empire',
        222 => 'fas fa-envelope',
        223 => 'fas fa-envelope-o',
        224 => 'fas fa-envelope-open',
        225 => 'fas fa-envelope-open-o',
        226 => 'fas fa-envelope-square',
        227 => 'fas fa-envira',
        228 => 'fas fa-eraser',
        229 => 'fas fa-etsy',
        230 => 'fas fa-eur',
        231 => 'fas fa-euro',
        232 => 'fas fa-exchange',
        233 => 'fas fa-exclamation',
        234 => 'fas fa-exclamation-circle',
        235 => 'fas fa-exclamation-triangle',
        236 => 'fas fa-expand',
        237 => 'fas fa-expeditedssl',
        238 => 'fas fa-external-link',
        239 => 'fas fa-external-link-square',
        240 => 'fas fa-eye',
        241 => 'fas fa-eye-slash',
        242 => 'fas fa-eyedropper',
        243 => 'fas fa-fa',
        244 => 'fab fa-facebook',
        245 => 'fab fa-facebook-f',
        246 => 'fab fa-facebook-official',
        247 => 'fab fa-facebook-square',
        248 => 'fas fa-fast-backward',
        249 => 'fas fa-fast-forward',
        250 => 'fas fa-fax',
        251 => 'fas fa-feed',
        252 => 'fas fa-female',
        253 => 'fas fa-fighter-jet',
        254 => 'fas fa-file',
        255 => 'fas fa-file-archive-o',
        256 => 'fas fa-file-audio-o',
        257 => 'fas fa-file-code-o',
        258 => 'fas fa-file-excel-o',
        259 => 'fas fa-file-image-o',
        260 => 'fas fa-file-movie-o',
        261 => 'fas fa-file-o',
        262 => 'fas fa-file-pdf-o',
        263 => 'fas fa-file-photo-o',
        264 => 'fas fa-file-picture-o',
        265 => 'fas fa-file-powerpoint-o',
        266 => 'fas fa-file-sound-o',
        267 => 'fas fa-file-text',
        268 => 'fas fa-file-text-o',
        269 => 'fas fa-file-video-o',
        270 => 'fas fa-file-word-o',
        271 => 'fas fa-file-zip-o',
        272 => 'fas fa-files-o',
        273 => 'fas fa-film',
        274 => 'fas fa-filter',
        275 => 'fas fa-fire',
        276 => 'fas fa-fire-extinguisher',
        277 => 'fas fa-firefox',
        278 => 'fas fa-first-order',
        279 => 'fas fa-flag',
        280 => 'fas fa-flag-checkered',
        281 => 'fas fa-flag-o',
        282 => 'fas fa-flash',
        283 => 'fas fa-flask',
        284 => 'fas fa-flickr',
        285 => 'fas fa-floppy-o',
        286 => 'fas fa-folder',
        287 => 'fas fa-folder-o',
        288 => 'fas fa-folder-open',
        289 => 'fas fa-folder-open-o',
        290 => 'fas fa-font',
        291 => 'fas fa-font-awesome',
        292 => 'fas fa-fonticons',
        293 => 'fas fa-fort-awesome',
        294 => 'fas fa-forumbee',
        295 => 'fas fa-forward',
        296 => 'fas fa-foursquare',
        297 => 'fas fa-free-code-camp',
        298 => 'fas fa-frown-o',
        299 => 'fas fa-futbol-o',
        300 => 'fas fa-gamepad',
        301 => 'fas fa-gavel',
        302 => 'fas fa-gbp',
        303 => 'fas fa-ge',
        304 => 'fas fa-gear',
        305 => 'fas fa-gears',
        306 => 'fas fa-genderless',
        307 => 'fas fa-get-pocket',
        308 => 'fas fa-gg',
        309 => 'fas fa-gg-circle',
        310 => 'fas fa-gift',
        311 => 'fas fa-git',
        312 => 'fas fa-git-square',
        313 => 'fas fa-github',
        314 => 'fas fa-github-alt',
        315 => 'fab fa-github-square',
        316 => 'fab fa-gitlab',
        317 => 'fab fa-gittip',
        318 => 'fas fa-glass',
        319 => 'fas fa-glide',
        320 => 'fas fa-glide-g',
        321 => 'fas fa-globe',
        322 => 'fas fa-google',
        323 => 'fas fa-google-plus',
        324 => 'fas fa-google-plus-circle',
        325 => 'fas fa-google-plus-official',
        326 => 'fas fa-google-plus-square',
        327 => 'fas fa-google-wallet',
        328 => 'fas fa-graduation-cap',
        329 => 'fas fa-gratipay',
        330 => 'fas fa-grav',
        331 => 'fas fa-group',
        332 => 'fas fa-h-square',
        333 => 'fas fa-hacker-news',
        334 => 'fas fa-hand-grab-o',
        335 => 'fas fa-hand-lizard-o',
        336 => 'fas fa-hand-o-down',
        337 => 'fas fa-hand-o-left',
        338 => 'fas fa-hand-o-right',
        339 => 'fas fa-hand-o-up',
        340 => 'fas fa-hand-paper-o',
        341 => 'fas fa-hand-peace-o',
        342 => 'fas fa-hand-pointer-o',
        343 => 'fas fa-hand-rock-o',
        344 => 'fas fa-hand-scissors-o',
        345 => 'fas fa-hand-spock-o',
        346 => 'fas fa-hand-stop-o',
        347 => 'fas fa-handshake-o',
        348 => 'fas fa-hard-of-hearing',
        349 => 'fas fa-hashtag',
        350 => 'fas fa-hdd-o',
        351 => 'fas fa-header',
        352 => 'fas fa-headphones',
        353 => 'fas fa-heart',
        354 => 'fas fa-heart-o',
        355 => 'fas fa-heartbeat',
        356 => 'fas fa-history',
        357 => 'fas fa-home',
        358 => 'fas fa-hospital-o',
        359 => 'fas fa-hotel',
        360 => 'fas fa-hourglass',
        361 => 'fas fa-hourglass-1',
        362 => 'fas fa-hourglass-2',
        363 => 'fas fa-hourglass-3',
        364 => 'fas fa-hourglass-end',
        365 => 'fas fa-hourglass-half',
        366 => 'fas fa-hourglass-o',
        367 => 'fas fa-hourglass-start',
        368 => 'fas fa-houzz',
        369 => 'fas fa-html5',
        370 => 'fas fa-i-cursor',
        371 => 'fas fa-id-badge',
        372 => 'fas fa-id-card',
        373 => 'fas fa-id-card-o',
        374 => 'fas fa-ils',
        375 => 'fas fa-image',
        376 => 'fas fa-imdb',
        377 => 'fas fa-inbox',
        378 => 'fas fa-indent',
        379 => 'fas fa-industry',
        380 => 'fas fa-info',
        381 => 'fas fa-info-circle',
        382 => 'fas fa-inr',
        383 => 'fas fa-instagram',
        384 => 'fas fa-institution',
        385 => 'fas fa-internet-explorer',
        386 => 'fas fa-intersex',
        387 => 'fas fa-ioxhost',
        388 => 'fas fa-italic',
        389 => 'fas fa-joomla',
        390 => 'fas fa-jpy',
        391 => 'fas fa-jsfiddle',
        392 => 'fas fa-key',
        393 => 'fas fa-keyboard-o',
        394 => 'fas fa-krw',
        395 => 'fas fa-language',
        396 => 'fas fa-laptop',
        397 => 'fas fa-lastfm',
        398 => 'fas fa-lastfm-square',
        399 => 'fas fa-leaf',
        400 => 'fas fa-leanpub',
        401 => 'fas fa-legal',
        402 => 'fas fa-lemon-o',
        403 => 'fas fa-level-down',
        404 => 'fas fa-level-up',
        405 => 'fas fa-life-bouy',
        406 => 'fas fa-life-buoy',
        407 => 'fas fa-life-ring',
        408 => 'fas fa-life-saver',
        409 => 'fas fa-lightbulb-o',
        410 => 'fas fa-line-chart',
        411 => 'fas fa-link',
        412 => 'fas fa-linkedin',
        413 => 'fas fa-linkedin-square',
        414 => 'fas fa-linode',
        415 => 'fas fa-linux',
        416 => 'fas fa-list',
        417 => 'fas fa-list-alt',
        418 => 'fas fa-list-ol',
        419 => 'fas fa-list-ul',
        420 => 'fas fa-location-arrow',
        421 => 'fas fa-lock',
        422 => 'fas fa-long-arrow-down',
        423 => 'fas fa-long-arrow-left',
        424 => 'fas fa-long-arrow-right',
        425 => 'fas fa-long-arrow-up',
        426 => 'fas fa-low-vision',
        427 => 'fas fa-magic',
        428 => 'fas fa-magnet',
        429 => 'fas fa-mail-forward',
        430 => 'fas fa-mail-reply',
        431 => 'fas fa-mail-reply-all',
        432 => 'fas fa-male',
        433 => 'fas fa-map',
        434 => 'fas fa-map-marker',
        435 => 'fas fa-map-o',
        436 => 'fas fa-map-pin',
        437 => 'fas fa-map-signs',
        438 => 'fas fa-mars',
        439 => 'fas fa-mars-double',
        440 => 'fas fa-mars-stroke',
        441 => 'fas fa-mars-stroke-h',
        442 => 'fas fa-mars-stroke-v',
        443 => 'fas fa-maxcdn',
        444 => 'fas fa-meanpath',
        445 => 'fas fa-medium',
        446 => 'fas fa-medkit',
        447 => 'fas fa-meetup',
        448 => 'fas fa-meh-o',
        449 => 'fas fa-mercury',
        450 => 'fas fa-microchip',
        451 => 'fas fa-microphone',
        452 => 'fas fa-microphone-slash',
        453 => 'fas fa-minus',
        454 => 'fas fa-minus-circle',
        455 => 'fas fa-minus-square',
        456 => 'fas fa-minus-square-o',
        457 => 'fas fa-mixcloud',
        458 => 'fas fa-mobile',
        459 => 'fas fa-mobile-phone',
        460 => 'fas fa-modx',
        461 => 'fas fa-money',
        462 => 'fas fa-moon-o',
        463 => 'fas fa-mortar-board',
        464 => 'fas fa-motorcycle',
        465 => 'fas fa-mouse-pointer',
        466 => 'fas fa-music',
        467 => 'fas fa-navicon',
        468 => 'fas fa-neuter',
        469 => 'fas fa-newspaper-o',
        470 => 'fas fa-object-group',
        471 => 'fas fa-object-ungroup',
        472 => 'fas fa-odnoklassniki',
        473 => 'fas fa-odnoklassniki-square',
        474 => 'fas fa-opencart',
        475 => 'fas fa-openid',
        476 => 'fas fa-opera',
        477 => 'fas fa-optin-monster',
        478 => 'fas fa-outdent',
        479 => 'fas fa-pagelines',
        480 => 'fas fa-paint-brush',
        481 => 'fas fa-paper-plane',
        482 => 'fas fa-paper-plane-o',
        483 => 'fas fa-paperclip',
        484 => 'fas fa-paragraph',
        485 => 'fas fa-paste',
        486 => 'fas fa-pause',
        487 => 'fas fa-pause-circle',
        488 => 'fas fa-pause-circle-o',
        489 => 'fas fa-paw',
        490 => 'fas fa-paypal',
        491 => 'fas fa-pencil',
        492 => 'fas fa-pencil-square',
        493 => 'fas fa-pencil-square-o',
        494 => 'fas fa-percent',
        495 => 'fas fa-phone',
        496 => 'fas fa-phone-square',
        497 => 'fas fa-photo',
        498 => 'fas fa-picture-o',
        499 => 'fas fa-pie-chart',
        500 => 'fas fa-pied-piper',
        501 => 'fas fa-pied-piper-alt',
        502 => 'fas fa-pied-piper-pp',
        503 => 'fas fa-pinterest',
        504 => 'fas fa-pinterest-p',
        505 => 'fas fa-pinterest-square',
        506 => 'fas fa-plane',
        507 => 'fas fa-play',
        508 => 'fas fa-play-circle',
        509 => 'fas fa-play-circle-o',
        510 => 'fas fa-plug',
        511 => 'fas fa-plus',
        512 => 'fas fa-plus-circle',
        513 => 'fas fa-plus-square',
        514 => 'fas fa-plus-square-o',
        515 => 'fas fa-podcast',
        516 => 'fas fa-power-off',
        517 => 'fas fa-print',
        518 => 'fas fa-product-hunt',
        519 => 'fas fa-puzzle-piece',
        520 => 'fas fa-qq',
        521 => 'fas fa-qrcode',
        522 => 'fas fa-question',
        523 => 'fas fa-question-circle',
        524 => 'fas fa-question-circle-o',
        525 => 'fas fa-quora',
        526 => 'fas fa-quote-left',
        527 => 'fas fa-quote-right',
        528 => 'fas fa-ra',
        529 => 'fas fa-random',
        530 => 'fas fa-ravelry',
        531 => 'fas fa-rebel',
        532 => 'fas fa-recycle',
        533 => 'fas fa-reddit',
        534 => 'fas fa-reddit-alien',
        535 => 'fas fa-reddit-square',
        536 => 'fas fa-refresh',
        537 => 'fas fa-registered',
        538 => 'fas fa-remove',
        539 => 'fas fa-renren',
        540 => 'fas fa-reorder',
        541 => 'fas fa-repeat',
        542 => 'fas fa-reply',
        543 => 'fas fa-reply-all',
        544 => 'fas fa-resistance',
        545 => 'fas fa-retweet',
        546 => 'fas fa-rmb',
        547 => 'fas fa-road',
        548 => 'fas fa-rocket',
        549 => 'fas fa-rotate-left',
        550 => 'fas fa-rotate-right',
        551 => 'fas fa-rouble',
        552 => 'fas fa-rss',
        553 => 'fas fa-rss-square',
        554 => 'fas fa-rub',
        555 => 'fas fa-ruble',
        556 => 'fas fa-rupee',
        557 => 'fas fa-s15',
        558 => 'fas fa-safari',
        559 => 'fas fa-save',
        560 => 'fas fa-scissors',
        561 => 'fas fa-scribd',
        562 => 'fas fa-search',
        563 => 'fas fa-search-minus',
        564 => 'fas fa-search-plus',
        565 => 'fas fa-sellsy',
        566 => 'fas fa-send',
        567 => 'fas fa-send-o',
        568 => 'fas fa-server',
        569 => 'fas fa-share',
        570 => 'fas fa-share-alt',
        571 => 'fas fa-share-alt-square',
        572 => 'fas fa-share-square',
        573 => 'fas fa-share-square-o',
        574 => 'fas fa-shekel',
        575 => 'fas fa-sheqel',
        576 => 'fas fa-shield',
        577 => 'fas fa-ship',
        578 => 'fas fa-shirtsinbulk',
        579 => 'fas fa-shopping-bag',
        580 => 'fas fa-shopping-basket',
        581 => 'fas fa-shopping-cart',
        582 => 'fas fa-shower',
        583 => 'fas fa-sign-in',
        584 => 'fas fa-sign-language',
        585 => 'fas fa-sign-out',
        586 => 'fas fa-signal',
        587 => 'fas fa-signing',
        588 => 'fas fa-simplybuilt',
        589 => 'fas fa-sitemap',
        590 => 'fab fa-skyatlas',
        591 => 'fab fa-skype',
        592 => 'fab fa-slack',
        593 => 'fas fa-sliders',
        594 => 'fab fa-slideshare',
        595 => 'fas fa-smile-o',
        596 => 'fab fa-snapchat',
        597 => 'fab fa-snapchat-ghost',
        598 => 'fab fa-snapchat-square',
        599 => 'far fa-snowflake-o',
        600 => 'fas fa-soccer-ball-o',
        601 => 'fas fa-sort',
        602 => 'fas fa-sort-alpha-asc',
        603 => 'fas fa-sort-alpha-desc',
        604 => 'fas fa-sort-amount-asc',
        605 => 'fas fa-sort-amount-desc',
        606 => 'fas fa-sort-asc',
        607 => 'fas fa-sort-desc',
        608 => 'fas fa-sort-down',
        609 => 'fas fa-sort-numeric-asc',
        610 => 'fas fa-sort-numeric-desc',
        611 => 'fas fa-sort-up',
        612 => 'fab fa-soundcloud',
        613 => 'fas fa-space-shuttle',
        614 => 'fas fa-spinner',
        615 => 'fab fa-spoon',
        616 => 'fab fa-spotify',
        617 => 'fas fa-square',
        619 => 'fab fa-stack-exchange',
        620 => 'fab fa-stack-overflow',
        621 => 'fas fa-star',
        622 => 'fas fa-star-half',
        626 => 'fab fa-star-o',
        627 => 'fab fa-steam',
        628 => 'fab fa-steam-square',
        629 => 'fas fa-step-backward',
        630 => 'fas fa-step-forward',
        631 => 'fas fa-stethoscope',
        632 => 'fas fa-sticky-note',
        634 => 'fas fa-stop',
        635 => 'fas fa-stop-circle',
        637 => 'fas fa-street-view',
        638 => 'fas fa-strikethrough',
        639 => 'fas fa-stumbleupon',
        640 => 'fas fa-stumbleupon-circle',
        641 => 'fas fa-subscript',
        642 => 'fas fa-subway',
        643 => 'fas fa-suitcase',
        644 => 'fas fa-sun-o',
        645 => 'fas fa-superpowers',
        646 => 'fas fa-superscript',
        647 => 'fas fa-support',
        648 => 'fas fa-table',
        649 => 'fas fa-tablet',
        650 => 'fas fa-tachometer',
        651 => 'fas fa-tag',
        652 => 'fas fa-tags',
        653 => 'fas fa-tasks',
        654 => 'fas fa-taxi',
        655 => 'fas fa-telegram',
        656 => 'fas fa-television',
        657 => 'fas fa-tencent-weibo',
        658 => 'fas fa-terminal',
        659 => 'fas fa-text-height',
        660 => 'fas fa-text-width',
        661 => 'fas fa-th',
        662 => 'fas fa-th-large',
        663 => 'fas fa-th-list',
        664 => 'fas fa-themeisle',
        665 => 'fas fa-thermometer',
        666 => 'fas fa-thermometer-0',
        667 => 'fas fa-thermometer-1',
        668 => 'fas fa-thermometer-2',
        669 => 'fas fa-thermometer-3',
        670 => 'fas fa-thermometer-4',
        671 => 'fas fa-thermometer-empty',
        672 => 'fas fa-thermometer-full',
        673 => 'fas fa-thermometer-half',
        674 => 'fas fa-thermometer-quarter',
        675 => 'fas fa-thermometer-three-quarters',
        676 => 'fas fa-thumb-tack',
        677 => 'fas fa-thumbs-down',
        680 => 'fas fa-thumbs-up',
        681 => 'fas fa-ticket-alt',
        682 => 'fas fa-times',
        683 => 'fas fa-times-circle',
        687 => 'fas fa-tint',
        688 => 'fas fa-toggle-down',
        689 => 'fas fa-toggle-left',
        690 => 'fas fa-toggle-off',
        691 => 'fas fa-toggle-on',
        692 => 'fas fa-toggle-right',
        693 => 'fas fa-toggle-up',
        694 => 'fas fa-trademark',
        695 => 'fas fa-train',
        696 => 'fas fa-transgender',
        697 => 'fas fa-transgender-alt',
        698 => 'fas fa-trash',
        700 => 'fas fa-tree',
        701 => 'fab fa-trello',
        702 => 'fab fa-tripadvisor',
        703 => 'fas fa-trophy',
        704 => 'fas fa-truck',
        705 => 'fas fa-try',
        706 => 'fas fa-tty',
        707 => 'fab fa-tumblr',
        708 => 'fab fa-tumblr-square',
        709 => 'fas fa-turkish-lira',
        710 => 'fas fa-tv',
        711 => 'fab fa-twitch',
        712 => 'fab fa-twitter',
        713 => 'fab fa-twitter-square',
        714 => 'fas fa-umbrella',
        715 => 'fas fa-underline',
        716 => 'fas fa-undo',
        717 => 'fas fa-universal-access',
        718 => 'fas fa-university',
        719 => 'fas fa-unlink',
        720 => 'fas fa-unlock',
        721 => 'fas fa-unlock-alt',
        722 => 'fas fa-unsorted',
        723 => 'fas fa-upload',
        724 => 'fab fa-usb',
        725 => 'fas fa-usd',
        726 => 'fas fa-user',
        727 => 'fas fa-user-circle',
        728 => 'fas fa-user-circle-o',
        729 => 'fas fa-user-md',
        731 => 'fas fa-user-plus',
        732 => 'fas fa-user-secret',
        733 => 'fas fa-user-times',
        734 => 'fas fa-users',
        735 => 'fas fa-vcard',
        736 => 'fab fa-vcard-o',
        737 => 'fas fa-venus',
        738 => 'fas fa-venus-double',
        739 => 'fas fa-venus-mars',
        740 => 'fab fa-viacoin',
        741 => 'fab fa-viadeo',
        742 => 'fab fa-viadeo-square',
        743 => 'fas fa-video-camera',
        744 => 'fab fa-vimeo',
        745 => 'fab fa-vimeo-square',
        746 => 'fab fa-vine',
        747 => 'fab fa-vk',
        748 => 'fas fa-volume-control-phone',
        749 => 'fas fa-volume-down',
        750 => 'fas fa-volume-off',
        751 => 'fas fa-volume-up',
        752 => 'fas fa-warning',
        753 => 'fab fa-wechat',
        754 => 'fab fa-weibo',
        755 => 'fab fa-weixin',
        756 => 'fab fa-whatsapp',
        757 => 'fas fa-wheelchair',
        759 => 'fas fa-wifi',
        760 => 'fab fa-wikipedia-w',
        761 => 'fas fa-window-close',
        763 => 'fas fa-window-maximize',
        764 => 'fas fa-window-minimize',
        765 => 'fas fa-window-restore',
        766 => 'fas fa-windows',
        767 => 'fas fa-won',
        768 => 'fab fa-wordpress',
        769 => 'fab fa-wpbeginner',
        770 => 'fab fa-wpexplorer',
        771 => 'fab fa-wpforms',
        772 => 'fas fa-wrench',
        773 => 'fab fa-xing',
        774 => 'fab fa-xing-square',
        775 => 'fab fa-y-combinator',
        776 => 'fab fa-y-combinator-square',
        777 => 'fab fa-yahoo',
        778 => 'fab fa-yc',
        779 => 'fab fa-yc-square',
        780 => 'fab fa-yelp',
        781 => 'fas fa-yen',
        782 => 'fab fa-yoast',
        783 => 'fab fa-youtube',
        784 => 'fab fa-youtube-play',
        785 => 'fab fa-youtube-square fs-1',
    );
    return $icons;
}


if (!function_exists('get_user_file')) {
    function get_user_file($image)
    {
        if ($image != null) {
            if (!file_exists($image)) {
                return asset('assets/uploads/avatar.png');
            } else {
                return asset($image);
            }
        } else {
            return asset('assets/uploads/avatar.png');
        }
    }
}


if (!function_exists('get_file')) {
    function get_file($image)
    {

        if ($image != null) {
            if (!file_exists($image)) {
                return asset('assets/uploads/empty.png');
            } else {
                return asset($image);
            }
        } else {
            return asset('assets/uploads/empty.png');
        }
    }
}

if (!function_exists('api')) {
    function api()
    {
        return auth()->guard('api');
    }
}

if (!function_exists('helperJson')) {
    function helperJson($data = null, $message = '', $code = 200, $status = 200)
    {
        $json = response()->json(['data' => $data, 'message' => $message, 'code' => $code], $status);
        return $json;
    }
}

if (!function_exists('VendorParentAuthData')) {
    function VendorParentAuthData($key)
    {
        $parent_id = auth('vendor')->user()->parent_id == null ? auth('vendor')->user()->id : auth('vendor')->user()->parent_id;
        $parent = Vendor::where('id', $parent_id)->first();
        return $parent->$key;

    }

}

if (!function_exists('ModelData')) {
    function ModelData($model, $id, $key)
    {
        $modelClass = "App\\Models\\" . $model;

        if (!class_exists($modelClass)) {
            return null;
        }

        $obj = $modelClass::find($id);

        return $obj?->$key ?? null;
    }
}

