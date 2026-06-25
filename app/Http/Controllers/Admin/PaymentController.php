<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with('order')->latest()->paginate(20);
        $stats = [
            'total' => Payment::count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'cod_revenue' => Order::where('status', 'delivered')->where('payment_method', 'cod')->sum('grand_total'),
        ];
        $methods = [
            'cod' => ['name' => 'الدفع عند الاستلام', 'icon' => 'fa-money-bill-wave', 'color' => 'green', 'active' => true, 'description' => 'يدفع العميل عند استلام الطلب'],
            'bank_transfer' => ['name' => 'تحويل بنكي', 'icon' => 'fa-university', 'color' => 'blue', 'active' => false, 'description' => 'تحويل مصرفي مباشر'],
            'card' => ['name' => 'بطاقة ائتمان/خصم', 'icon' => 'fa-credit-card', 'color' => 'purple', 'active' => false, 'description' => 'Visa, Mastercard, mada'],
            'wallet' => ['name' => 'محفظة إلكترونية', 'icon' => 'fa-wallet', 'color' => 'orange', 'active' => false, 'description' => 'محافظ محلية'],
        ];
        return view('admin.payments.index', compact('payments', 'stats', 'methods'));
    }
}
