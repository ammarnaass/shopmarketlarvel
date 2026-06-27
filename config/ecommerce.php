<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Store General Settings
    |--------------------------------------------------------------------------
    */
    'store' => [
        'name' => env('APP_NAME', 'Amar Store'),
        'currency' => env('STORE_CURRENCY', 'SDG'),
        'currency_symbol' => env('STORE_CURRENCY_SYMBOL', 'ج.س'),
        'tax_rate' => env('STORE_TAX_RATE', 0),
        'free_shipping_threshold' => env('STORE_FREE_SHIPPING_THRESHOLD', 200),
        'default_country' => env('STORE_DEFAULT_COUNTRY', 'SD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Countries & States (North Africa focus)
    |--------------------------------------------------------------------------
    */
    'countries' => [
        'DZ' => [
            'name' => 'الجزائر',
            'name_en' => 'Algeria',
            'dial_code' => '+213',
            'currency' => 'DZD',
            'currency_symbol' => 'د.ج',
            'rate_to_usd' => 0.0075,
            'states' => [
                '01' => 'أدرار', '02' => 'الشلف', '03' => 'الأغواط', '04' => 'أم البواقي',
                '05' => 'باتنة', '06' => 'بجاية', '07' => 'بسكرة', '08' => 'بشار',
                '09' => 'البليدة', '10' => 'البويرة', '11' => 'تمنراست', '12' => 'تبسة',
                '13' => 'تلمسان', '14' => 'تيارت', '15' => 'تيزي وزو', '16' => 'الجزائر',
                '17' => 'الجلفة', '18' => 'جيجل', '19' => 'سطيف', '20' => 'سعيدة',
                '21' => 'سكيكدة', '22' => 'سيدي بلعباس', '23' => 'عنابة', '24' => 'قالمة',
                '25' => 'قسنطينة', '26' => 'المدية', '27' => 'مستغانم', '28' => 'المسيلة',
                '29' => 'معسكر', '30' => 'ورقلة', '31' => 'وهران', '32' => 'البيض',
                '33' => 'إليزي', '34' => 'برج بوعريريج', '35' => 'بومرداس', '36' => 'الطارف',
                '37' => 'تندوف', '38' => 'تيسمسيلت', '39' => 'الوادي', '40' => 'خنشلة',
                '41' => 'سوق أهراس', '42' => 'تيبازة', '43' => 'ميلة', '44' => 'عين الدفلى',
                '45' => 'النعامة', '46' => 'عين تموشنت', '47' => 'غرداية', '48' => 'غليزان',
            ],
        ],
        'MA' => [
            'name' => 'المغرب',
            'name_en' => 'Morocco',
            'dial_code' => '+212',
            'currency' => 'MAD',
            'currency_symbol' => 'د.م.',
            'rate_to_usd' => 0.10,
            'states' => [
                '01' => 'الدار البيضاء', '02' => 'الرباط', '03' => 'فاس', '04' => 'مراكش',
                '05' => 'طنجة', '06' => 'أكادير', '07' => 'مكناس', '08' => 'وجدة',
                '09' => 'القنيطرة', '10' => 'تطوان', '11' => 'سلا', '12' => 'تمارة',
                '13' => 'آسفي', '14' => 'المحمدية', '15' => 'الجديدة', '16' => 'بني ملال',
                '17' => 'الناظور', '18' => 'الحسيمة', '19' => 'خريبكة', '20' => 'سطات',
                '21' => 'برشيد', '22' => 'تازة', '23' => 'الصويرة', '24' => 'القصر الكبير',
                '25' => 'العرائش', '26' => 'العيون', '27' => 'كلميم', '28' => 'وادي زم',
                '29' => 'بركان', '30' => 'ميدلت', '31' => 'سيدي قاسم', '32' => 'سيدي سليمان',
            ],
        ],
        'TN' => [
            'name' => 'تونس',
            'name_en' => 'Tunisia',
            'dial_code' => '+216',
            'currency' => 'TND',
            'currency_symbol' => 'د.ت',
            'rate_to_usd' => 0.32,
            'states' => [
                '11' => 'تونس', '12' => 'أريانة', '13' => 'بن عروس', '14' => 'منوبة',
                '15' => 'نابل', '16' => 'زغوان', '17' => 'بنزرت', '21' => 'جندوبة',
                '22' => 'الكاف', '23' => 'سليانة', '31' => 'باجة', '32' => 'جندوبة',
                '33' => 'القصرين', '34' => 'سيدي بوزيد', '41' => 'سوسة', '42' => 'المنستير',
                '43' => 'المهدية', '44' => 'صفاقس', '51' => 'توزر', '52' => 'قابس',
                '53' => 'مدنين', '61' => 'قفصة', '71' => 'قبلي', '72' => 'توزر',
                '73' => 'قابس', '81' => 'قابس', '82' => 'مدنين',
            ],
        ],
        'LY' => [
            'name' => 'ليبيا',
            'name_en' => 'Libya',
            'dial_code' => '+218',
            'currency' => 'LYD',
            'currency_symbol' => 'د.ل',
            'rate_to_usd' => 0.21,
            'states' => [
                '01' => 'طرابلس', '02' => 'بنغازي', '03' => 'مصراتة', '04' => 'الزاوية',
                '05' => 'زليتن', '06' => 'البيضاء', '07' => 'أجدابيا', '08' => 'سبها',
                '09' => 'سرت', '10' => 'الخمس', '11' => 'درنة', '12' => 'طبرق',
                '13' => 'غريان', '14' => 'الجميل', '15' => 'المرج', '16' => 'شحات',
                '17' => 'جالو', '18' => 'هون', '19' => 'غدامس', '20' => 'أوباري',
            ],
        ],
        'EG' => [
            'name' => 'مصر',
            'name_en' => 'Egypt',
            'dial_code' => '+20',
            'currency' => 'EGP',
            'currency_symbol' => 'ج.م',
            'rate_to_usd' => 0.020,
            'states' => [
                '01' => 'القاهرة', '02' => 'الجيزة', '03' => 'الإسكندرية', '04' => 'الدقهلية',
                '05' => 'البحر الأحمر', '06' => 'البحيرة', '07' => 'الفيوم', '08' => 'الغربية',
                '09' => 'الإسماعيلية', '10' => 'المنوفية', '11' => 'المنيا', '12' => 'القليوبية',
                '13' => 'الوادي الجديد', '14' => 'السويس', '15' => 'أسوان', '16' => 'أسيوط',
                '17' => 'بني سويف', '18' => 'بورسعيد', '19' => 'دمياط', '20' => 'الشرقية',
                '21' => 'جنوب سيناء', '22' => 'كفر الشيخ', '23' => 'مطروح', '24' => 'الأقصر',
                '25' => 'قنا', '26' => 'شمال سيناء', '27' => 'سوهاج',
            ],
        ],
        'SD' => [
            'name' => 'السودان',
            'name_en' => 'Sudan',
            'dial_code' => '+249',
            'currency' => 'SDG',
            'currency_symbol' => 'ج.س',
            'rate_to_usd' => 0.0017,
            'states' => [
                '01' => 'الخرطوم', '02' => 'الجزيرة', '03' => 'كسلا', '04' => 'القضارف',
                '05' => 'سنار', '06' => 'النيل الأزرق', '07' => 'النيل الأبيض', '08' => 'شمال دارفور',
                '09' => 'جنوب دارفور', '10' => 'غرب دارفور', '11' => 'وسط دارفور', '12' => 'شرق دارفور',
                '13' => 'البحر الأحمر', '14' => 'نهر النيل', '15' => 'الشمالية', '16' => 'مدن: بحري، أم درمان، بورتسودان',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cash on Delivery (COD) Settings
    |--------------------------------------------------------------------------
    */
    'cod' => [
        'enabled' => env('COD_ENABLED', true),
        'display_name' => 'الدفع عند الاستلام',
        'description' => 'ادفع نقدا عند استلام طلبك',
        'min_order' => env('COD_MIN_ORDER', 50),
        'max_order' => env('COD_MAX_ORDER', 5000),
        'extra_fee' => env('COD_EXTRA_FEE', 0),
        'fee_label' => 'رسوم الدفع عند الاستلام',
        'allowed_cities' => ['*'],
        'blocked_cities' => [],
        'excluded_categories' => [],
        'excluded_products' => [],
        'phone_confirmation' => env('COD_PHONE_CONFIRMATION', true),
        'confirmation_method' => 'sms',
        'auto_confirm_after' => 24,
        'deposit_enabled' => false,
        'deposit_percentage' => 20,
        'notify_admin_on_order' => true,
        'notify_customer_on_confirm' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping Settings
    |--------------------------------------------------------------------------
    */
    'shipping' => [
        'default_company' => env('SHIPPING_DEFAULT_COMPANY', 'aramex'),
        'free_threshold' => env('SHIPPING_FREE_THRESHOLD', 200),
        'default_country' => env('STORE_DEFAULT_COUNTRY', 'SD'),

        'zones' => [
            // Sudan - Capital
            [
                'name' => 'الخرطوم (السودان)',
                'countries' => ['SD'],
                'cities' => ['الخرطوم', 'بحري', 'ام درمان'],
                'cost' => 25,
                'express_cost' => 40,
                'free_threshold' => 200,
            ],
            // Sudan - Other
            [
                'name' => 'باقي مدن السودان',
                'countries' => ['SD'],
                'cities' => ['*'],
                'cost' => 35,
                'express_cost' => 55,
                'free_threshold' => 300,
            ],
            // Algeria
            [
                'name' => 'الجزائر - العاصمة',
                'countries' => ['DZ'],
                'cities' => ['الجزائر', 'البليدة', 'بومرداس', 'تيزي وزو', 'البويرة', 'المدية', 'تيبازة'],
                'cost' => 400,
                'express_cost' => 700,
                'free_threshold' => 5000,
            ],
            [
                'name' => 'الجزائر - باقي الولايات',
                'countries' => ['DZ'],
                'cities' => ['*'],
                'cost' => 600,
                'express_cost' => 950,
                'free_threshold' => 7000,
            ],
            // Morocco
            [
                'name' => 'المغرب - المدن الرئيسية',
                'countries' => ['MA'],
                'cities' => ['الدار البيضاء', 'الرباط', 'فاس', 'مراكش', 'طنجة', 'أكادير', 'مكناس', 'وجدة', 'القنيطرة', 'تطوان', 'سلا', 'تمارة', 'المحمدية'],
                'cost' => 35,
                'express_cost' => 60,
                'free_threshold' => 500,
            ],
            [
                'name' => 'المغرب - باقي المدن',
                'countries' => ['MA'],
                'cities' => ['*'],
                'cost' => 50,
                'express_cost' => 85,
                'free_threshold' => 700,
            ],
            // Tunisia
            [
                'name' => 'تونس - العاصمة الكبرى',
                'countries' => ['TN'],
                'cities' => ['تونس', 'أريانة', 'بن عروس', 'منوبة', 'نابل', 'بنزرت', 'زغوان'],
                'cost' => 7,
                'express_cost' => 12,
                'free_threshold' => 100,
            ],
            [
                'name' => 'تونس - باقي المدن',
                'countries' => ['TN'],
                'cities' => ['*'],
                'cost' => 10,
                'express_cost' => 17,
                'free_threshold' => 150,
            ],
            // Libya
            [
                'name' => 'ليبيا - المدن الرئيسية',
                'countries' => ['LY'],
                'cities' => ['طرابلس', 'بنغازي', 'مصراتة', 'الزاوية', 'زليتن', 'البيضاء', 'أجدابيا', 'سرت', 'الخمس', 'غريان'],
                'cost' => 20,
                'express_cost' => 35,
                'free_threshold' => 300,
            ],
            [
                'name' => 'ليبيا - باقي المدن',
                'countries' => ['LY'],
                'cities' => ['*'],
                'cost' => 30,
                'express_cost' => 50,
                'free_threshold' => 400,
            ],
            // Egypt
            [
                'name' => 'مصر - القاهرة الكبرى',
                'countries' => ['EG'],
                'cities' => ['القاهرة', 'الجيزة', 'الإسكندرية', 'القليوبية', 'الإسماعيلية', 'السويس', 'بورسعيد'],
                'cost' => 60,
                'express_cost' => 100,
                'free_threshold' => 800,
            ],
            [
                'name' => 'مصر - باقي المحافظات',
                'countries' => ['EG'],
                'cities' => ['*'],
                'cost' => 90,
                'express_cost' => 150,
                'free_threshold' => 1200,
            ],
        ],

        'companies' => [
            'aramex' => [
                'name' => 'Aramex',
                'tracking_url' => 'https://www.aramex.com/track?tracknumber={TRACKING}',
                'api_enabled' => false,
            ],
            'smsa' => [
                'name' => 'SMSA',
                'tracking_url' => 'https://www.smsaexpress.com/trackingdetails?tracknumber={TRACKING}',
                'api_enabled' => false,
            ],
            'yalidin' => [
                'name' => 'Yalidin',
                'tracking_url' => 'https://www.yalidin.com/tracking?code={TRACKING}',
                'api_enabled' => false,
            ],
            'noest' => [
                'name' => 'Noest Express',
                'tracking_url' => 'https://www.noest-dz.com/tracking/{TRACKING}',
                'api_enabled' => false,
            ],
            'maystro' => [
                'name' => 'Maystro Delivery',
                'tracking_url' => 'https://maystro.com/track/{TRACKING}',
                'api_enabled' => false,
            ],
        ],

        'options' => [
            'standard' => ['name' => 'عادي', 'days' => '3-5'],
            'express' => ['name' => 'سريع', 'days' => '1-2'],
            'same_day' => ['name' => 'فوري', 'days' => '0', 'cities' => ['الخرطوم', 'الجزائر', 'الدار البيضاء', 'تونس', 'طرابلس', 'القاهرة']],
        ],
    ],

    'languages' => [
        'supported' => ['ar', 'en', 'fr'],
        'default' => env('APP_LOCALE', 'ar'),
        'cookie_name' => 'locale',
        'cookie_minutes' => 43200,
        'hide_default_prefix' => true,
    ],

    // Root-level aliases for backward compatibility
    'default_country' => env('STORE_DEFAULT_COUNTRY', 'SD'),
    'default_currency' => env('STORE_CURRENCY', 'SDG'),
    'default_currency_symbol' => env('STORE_CURRENCY_SYMBOL', 'ج.س'),

];
