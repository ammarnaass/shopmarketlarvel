<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instant_buy_settings', function (Blueprint $table) {
            $table->id();

            // General
            $table->boolean('is_enabled')->default(true);
            $table->string('title', 100)->default('⚡ الشراء الفوري');
            $table->string('subtitle', 255)->default('املأ بياناتك وسنقوم بالتواصل معك لتأكيد الطلب');

            // Form colors
            $table->string('form_bg_color', 7)->default('#ffffff');
            $table->string('form_border_color', 7)->default('#e2e8f0');
            $table->integer('form_border_width')->default(1);
            $table->integer('form_border_radius')->default(16);
            $table->string('form_shadow', 50)->default('0 4px 6px -1px rgba(0,0,0,0.1)');

            // Section titles
            $table->string('section_title_color', 7)->default('#0f172a');
            $table->integer('section_title_size')->default(16);
            $table->string('section_title_weight', 20)->default('bold');
            $table->string('section_icon_color', 7)->default('#2563eb');

            // Input fields
            $table->string('input_bg_color', 7)->default('#ffffff');
            $table->string('input_border_color', 7)->default('#cbd5e1');
            $table->string('input_focus_color', 7)->default('#2563eb');
            $table->string('input_text_color', 7)->default('#0f172a');
            $table->string('input_placeholder_color', 7)->default('#94a3b8');
            $table->integer('input_border_radius')->default(8);
            $table->integer('input_height')->default(44);

            // Button
            $table->string('button_bg_color', 7)->default('#2563eb');
            $table->string('button_hover_color', 7)->default('#1d4ed8');
            $table->string('button_text_color', 7)->default('#ffffff');
            $table->integer('button_text_size')->default(16);
            $table->string('button_weight', 20)->default('bold');
            $table->integer('button_border_radius')->default(12);
            $table->integer('button_height')->default(52);
            $table->string('button_icon', 50)->default('✅');
            $table->string('button_text', 100)->default('تأكيد الطلب');

            // Summary
            $table->string('summary_bg_color', 7)->default('#f8fafc');
            $table->string('summary_border_color', 7)->default('#e2e8f0');
            $table->string('summary_text_color', 7)->default('#334155');
            $table->string('summary_total_color', 7)->default('#2563eb');
            $table->integer('summary_total_size')->default(20);

            // Trust message
            $table->string('trust_message', 255)->default('🔒 بياناتك آمنة | 🚚 توصيل سريع');
            $table->string('trust_message_color', 7)->default('#64748b');
            $table->integer('trust_message_size')->default(12);

            // Field config
            $table->boolean('field_first_name_enabled')->default(true);
            $table->boolean('field_first_name_required')->default(true);
            $table->string('field_first_name_label', 50)->default('الاسم');
            $table->string('field_first_name_placeholder', 100)->default('محمد');

            $table->boolean('field_last_name_enabled')->default(true);
            $table->boolean('field_last_name_required')->default(true);
            $table->string('field_last_name_label', 50)->default('اللقب');
            $table->string('field_last_name_placeholder', 100)->default('الفلاني');

            $table->boolean('field_phone_enabled')->default(true);
            $table->boolean('field_phone_required')->default(true);
            $table->string('field_phone_label', 50)->default('رقم الهاتف');
            $table->string('field_phone_placeholder', 100)->default('5xx xxx xxx');

            $table->boolean('field_country_enabled')->default(true);
            $table->boolean('field_country_required')->default(true);
            $table->string('field_country_label', 50)->default('الدولة');

            $table->boolean('field_state_enabled')->default(true);
            $table->boolean('field_state_required')->default(true);
            $table->string('field_state_label', 50)->default('الولاية / المحافظة');

            $table->boolean('field_city_enabled')->default(true);
            $table->boolean('field_city_required')->default(true);
            $table->string('field_city_label', 50)->default('المدينة');

            $table->boolean('field_address_enabled')->default(true);
            $table->boolean('field_address_required')->default(true);
            $table->string('field_address_label', 50)->default('العنوان التفصيلي');
            $table->string('field_address_placeholder', 100)->default('الحي، الشارع، رقم المنزل');

            $table->boolean('field_notes_enabled')->default(true);
            $table->boolean('field_notes_required')->default(false);
            $table->string('field_notes_label', 50)->default('ملاحظات');
            $table->string('field_notes_placeholder', 100)->default('أي ملاحظات خاصة بالطلب');

            $table->boolean('field_coupon_enabled')->default(true);
            $table->string('field_coupon_label', 50)->default('كوبون الخصم');
            $table->string('field_coupon_placeholder', 100)->default('أدخل كود الخصم');
            $table->string('field_coupon_button_text', 50)->default('تطبيق');

            // Display settings
            $table->boolean('show_product_summary')->default(true);
            $table->boolean('show_quantity_selector')->default(true);
            $table->boolean('show_price_breakdown')->default(true);
            $table->boolean('show_shipping_calculator')->default(true);
            $table->boolean('auto_select_cheapest_shipping')->default(false);

            // Success page
            $table->string('success_title', 100)->default('تم إرسال طلبك بنجاح!');
            $table->string('success_message', 255)->default('سنتواصل معك قريباً لتأكيد الطلب');
            $table->string('success_button_text', 50)->default('متابعة التسوق');
            $table->boolean('success_show_order_number')->default(true);
            $table->boolean('success_show_whatsapp_button')->default(true);
            $table->boolean('success_show_order_details')->default(true);
            $table->string('success_whatsapp_message', 255)->default('مرحباً، لدي استفسار عن طلبي رقم: {order_number}');

            // Success colors
            $table->string('success_icon_color', 7)->default('#10b981');
            $table->integer('success_icon_size')->default(64);
            $table->string('success_title_color', 7)->default('#0f172a');
            $table->string('success_order_number_color', 7)->default('#2563eb');
            $table->integer('success_order_number_size')->default(24);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instant_buy_settings');
    }
};
