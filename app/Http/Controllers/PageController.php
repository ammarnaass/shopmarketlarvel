<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Static pages rendered from $pages array below.
     * Each entry: slug => [title, icon, sections]
     */
    public function show(string $slug): View
    {
        $page = $this->getPage($slug);

        if (!$page) {
            abort(404, 'الصفحة غير موجودة');
        }

        return view('frontend.page', [
            'page' => $page,
            'slug' => $slug,
        ]);
    }

    /**
     * Track an order by order_number + email/phone.
     */
    public function track(Request $request): View
    {
        $order = null;
        $error = null;

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'order_number' => 'required|string',
                'contact' => 'required|string',
            ], [
                'order_number.required' => 'رقم الطلب مطلوب',
                'contact.required' => 'البريد أو الهاتف مطلوب',
            ]);

            $order = Order::with('items', 'shippingAddress')
                ->where('order_number', $data['order_number'])
                ->where(function ($q) use ($data) {
                    $q->whereHas('user', function ($u) use ($data) {
                        $u->where('email', $data['contact'])->orWhere('phone', $data['contact']);
                    })
                    ->orWhere('guest_email', $data['contact'])
                    ->orWhere('guest_phone', $data['contact']);
                })
                ->first();

            if (!$order) {
                $error = 'لم يتم العثور على طلب بهذه البيانات. تحقق من رقم الطلب وعنوان البريد/الهاتف.';
            }
        }

        return view('frontend.track', [
            'order' => $order,
            'error' => $error,
            'orderNumber' => $request->input('order_number'),
            'contact' => $request->input('contact'),
        ]);
    }

    /**
     * Central page registry. Add new static pages here.
     */
    private function getPage(string $slug): ?array
    {
        $pages = [
            'return-policy' => [
                'title' => 'سياسة الإرجاع',
                'icon' => 'fa-rotate-left',
                'color' => 'blue',
                'intro' => 'نهتم بتجربتك معنا. إذا لم تكن راضياً عن مشترياتك، يمكنك إرجاعها وفق الشروط التالية:',
                'sections' => [
                    ['title' => 'مدة الإرجاع', 'body' => 'يمكنك إرجاع المنتج خلال 14 يوماً من تاريخ الاستلام، بشرط أن يكون بحالته الأصلية وغير مستخدم.'],
                    ['title' => 'المنتجات غير القابلة للإرجاع', 'body' => 'المنتجات الرقمية، المنتجات المفتوحة (مستحضرات التجميل، العطور)، والمنتجات المخصصة لا يمكن إرجاعها حفاظاً على صحة العملاء.'],
                    ['title' => 'طريقة الإرجاع', 'body' => 'تواصل معنا عبر صفحة "اتصل بنا" أو الواتساب لطلب الإرجاع. سيقوم مندوبنا باستلام المنتج من عنوانك.'],
                    ['title' => 'استرداد المبلغ', 'body' => 'يتم استرداد المبلغ خلال 5-7 أيام عمل من استلامنا للمنتج وفحصه، عبر نفس طريقة الدفع الأصلية.'],
                    ['title' => 'المنتجات التالفة', 'body' => 'إذا وصلك المنتج تالفاً أو معيباً، نتحمل تكاليف الإرجاع كاملة ونرسل بديلاً فوراً.'],
                ],
            ],
            'shipping' => [
                'title' => 'الشحن والتوصيل',
                'icon' => 'fa-truck-fast',
                'color' => 'green',
                'intro' => 'نوفر خدمة شحن سريعة وآمنة لجميع دول المنطقة العربية:',
                'sections' => [
                    ['title' => 'مدة التوصيل', 'body' => '2-4 أيام عمل داخل المدن الرئيسية، 3-7 أيام للمدن البعيدة. الطلبات قبل الساعة 2 ظهراً تُشحن في نفس اليوم.'],
                    ['title' => 'تكلفة الشحن', 'body' => 'تختلف حسب الدولة والوزن. احسب تكلفة الشحن في صفحة الدفع بعد اختيار عنوان التوصيل.'],
                    ['title' => 'الشحن المجاني', 'body' => 'متاح للطلبات التي تتجاوز قيمة معينة في كل دولة (تظهر في صفحة السلة والدفع).'],
                    ['title' => 'شركات الشحن', 'body' => 'نتعامل مع أكبر شركات الشحن في المنطقة مثل أرامكس، SMSA، DHL، وشركات محلية معتمدة.'],
                    ['title' => 'تتبع الطلب', 'body' => 'بمجرد شحن طلبك سنرسل لك رقم تتبع عبر رسالة SMS. يمكنك تتبع طلبك من صفحة "تتبع طلبك" في الأعلى.'],
                ],
            ],
            'faq' => [
                'title' => 'الأسئلة الشائعة',
                'icon' => 'fa-circle-question',
                'color' => 'purple',
                'intro' => 'إجابات على أكثر الأسئلة شيوعاً:',
                'sections' => [
                    ['title' => 'كيف أطلب منتجاً؟', 'body' => 'تصفح المنتجات، أضف ما تريد إلى السلة، ثم انتقل للدفع وأدخل بياناتك وعنوان التوصيل. ستتلقى تأكيداً عبر البريد.'],
                    ['title' => 'ما هي طرق الدفع المتاحة؟', 'body' => 'حالياً نقبل الدفع عند الاستلام (COD) في جميع الدول. سنضيف قريباً الدفع الإلكتروني عبر البطاقات والمحافظ.'],
                    ['title' => 'هل المنتجات أصلية؟', 'body' => 'نعم، جميع منتجاتنا أصلية 100% ومستوردة من موزعين معتمدين. نوفر ضماناً حقيقياً.'],
                    ['title' => 'هل يمكنني تعديل طلبي بعد التأكيد؟', 'body' => 'يمكنك تعديل أو إلغاء الطلب قبل شحنه فقط. تواصل معنا فوراً عبر الواتساب.'],
                    ['title' => 'هل تشحنون لدولتي؟', 'body' => 'نشحن حالياً لـ 6 دول: السعودية، الإمارات، مصر، الجزائر، المغرب، تونس، والسودان.'],
                    ['title' => 'كيف أتواصل مع خدمة العملاء؟', 'body' => 'عبر الواتساب على الرقم الموجود في الفوتر، أو عبر البريد الإلكتروني، أو من خلال نموذج الاتصال.'],
                ],
            ],
            'privacy' => [
                'title' => 'سياسة الخصوصية',
                'icon' => 'fa-shield-halved',
                'color' => 'indigo',
                'intro' => 'نلتزم بحماية خصوصيتك وبياناتك الشخصية:',
                'sections' => [
                    ['title' => 'البيانات التي نجمعها', 'body' => 'نجمع البيانات الضرورية فقط لإتمام طلبك: الاسم، البريد، الهاتف، وعنوان التوصيل.'],
                    ['title' => 'كيف نستخدم بياناتك', 'body' => 'نستخدم بياناتك حصراً لمعالجة طلبك، التواصل معك بشأن التوصيل، وتحسين تجربتك. لا نشاركها مع أطراف ثالثة.'],
                    ['title' => 'أمان البيانات', 'body' => 'نستخدم تشفير SSL على جميع صفحات الموقع. بياناتك محفوظة في خوادم آمنة ومحمية بكلمات مرور قوية.'],
                    ['title' => 'حقوقك', 'body' => 'يمكنك في أي وقت طلب تعديل أو حذف بياناتك. تواصل معنا وسنرد خلال 48 ساعة.'],
                    ['title' => 'ملفات تعريف الارتباط (Cookies)', 'body' => 'نستخدم cookies لتحسين تجربتك (مثل حفظ سلة التسوق). يمكنك تعطيلها من إعدادات المتصفح.'],
                ],
            ],
            'terms' => [
                'title' => 'الشروط والأحكام',
                'icon' => 'fa-file-contract',
                'color' => 'red',
                'intro' => 'باستخدامك لموقعنا فإنك توافق على الشروط التالية:',
                'sections' => [
                    ['title' => 'الاستخدام', 'body' => 'يجب أن تكون 18 سنة أو أكبر لإجراء الطلبات. البيانات التي تقدمها يجب أن تكون صحيحة ودقيقة.'],
                    ['title' => 'الأسعار', 'body' => 'جميع الأسعار شاملة للضريبة (إن وجدت). قد نقوم بتعديل الأسعار في أي وقت دون إشعار مسبق.'],
                    ['title' => 'الطلب والتأكيد', 'body' => 'تأكيد الطلب عبر البريد لا يعني قبوله. نحتفظ بالحق في رفض أو إلغاء أي طلب لأي سبب.'],
                    ['title' => 'الملكية الفكرية', 'body' => 'جميع المحتويات (صور، نصوص، شعارات) ملك حصري للمتجر ومحمية بقوانين الملكية الفكرية.'],
                    ['title' => 'حدود المسؤولية', 'body' => 'لا نتحمل مسؤولية أي أضرار غير مباشرة ناتجة عن استخدام الموقع أو المنتجات.'],
                ],
            ],
            'about' => [
                'title' => 'من نحن',
                'icon' => 'fa-circle-info',
                'color' => 'blue',
                'intro' => 'متجرك العربي المفضل للتسوق الإلكتروني:',
                'sections' => [
                    ['title' => 'مهمتنا', 'body' => 'توفير تجربة تسوق فريدة بأسعار منافسة، مع شحن سريع وضمان جودة لجميع المنتجات.'],
                    ['title' => 'رؤيتنا', 'body' => 'أن نكون المنصة الأولى للتسوق الإلكتروني في المنطقة العربية.'],
                    ['title' => 'قيمنا', 'body' => 'الجودة، الأمانة، خدمة العملاء المتميزة، والابتكار المستمر.'],
                ],
            ],
            'contact' => [
                'title' => 'اتصل بنا',
                'icon' => 'fa-headset',
                'color' => 'green',
                'intro' => 'فريق خدمة العملاء جاهز لمساعدتك على مدار الساعة:',
                'sections' => [
                    ['title' => 'الواتساب', 'body' => 'تواصل معنا عبر الواتساب على الرقم: +249 90 000 0000 — أسرع طريقة للحصول على رد.'],
                    ['title' => 'البريد الإلكتروني', 'body' => 'info@amarstore.com — نرد على جميع الاستفسارات خلال 24 ساعة.'],
                    ['title' => 'ساعات العمل', 'body' => 'خدمة العملاء متاحة 24/7. فريقنا يرد على الاستفسارات في أي وقت.'],
                    ['title' => 'المقر الرئيسي', 'body' => 'الخرطوم، جمهورية السودان — مكتب خدمة العملاء.'],
                ],
            ],
        ];

        return $pages[$slug] ?? null;
    }

    /**
     * Return states for a given country code (used by instant-buy form).
     */
    public function states(string $code): JsonResponse
    {
        $code = strtoupper($code);
        $countries = config('ecommerce.countries', []);
        $states = $countries[$code]['states'] ?? [];
        // Normalize to [{code, name}]
        $normalized = [];
        foreach ($states as $key => $val) {
            if (is_array($val)) {
                $normalized[] = ['code' => $val['code'] ?? $key, 'name' => $val['name'] ?? $key];
            } else {
                $normalized[] = ['code' => $key, 'name' => (string) $val];
            }
        }
        return response()->json(['states' => $normalized]);
    }
}
