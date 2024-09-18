<?php

namespace App\Livewire\Admin\Installment;

use App\Exceptions\ErrorHandler;
use CustomerManagement\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use PaymentContracts\IPaymentService;

#[Title('Order History')]
class InstallmentDetails extends Component
{
    public Customer $customer;

    public $paymentMethods = ['Cash'];
    public $referenceNumbers = [''];
    public $amounts = [null];

    public $codReferenceNumbers = [];
    public $codAmounts = [];
    public $receiptNumber = '';

    public array $rate;
    public array $penalty;
    public function mount(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @throws \Throwable
     */
    public function submitPayment(IPaymentService $paymentService): void
    {
        $this->validate([
            'receiptNumber' => 'required',
            'amounts.*' => 'required|numeric',
            'referenceNumbers.*' => 'required',
        ], [
            'amounts.*' => 'Amount is required',
            'referenceNumbers.*' => 'Reference Number is required',
        ]);

        $payment = [];
        for($i = 0; $i < count($this->amounts); $i++){
            if($this->amounts[$i] > 0) {
                $payment[] = [
                    'amount' => $this->amounts[$i] * 100,
                    'reference' => $this->referenceNumbers[$i],
                    'method' => $this->paymentMethods[$i],
                ];
            }
        }

        DB::beginTransaction();

        $result = $paymentService->payInstallment(
            $this->customer->id,
            $payment,
            auth()->user()?->id,
            Str::uuid()->toString(),
            $this->receiptNumber
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash('success', 'Payment received.');
    }

    public function newPaymentMethod(): void
    {
        $this->paymentMethods[] = 'Cash';
        $this->referenceNumbers[] = '';
        $this->amounts[] = null;
    }

    public function removePaymentMethod(int $index): void
    {
        unset($this->paymentMethods[$index]);
        unset($this->referenceNumbers[$index]);
        unset($this->amounts[$index]);

        $this->paymentMethods = array_values($this->paymentMethods);
        $this->referenceNumbers = array_values($this->referenceNumbers);
        $this->amounts = array_values($this->amounts);
    }

    /**
     * @throws \Throwable
     */
    public function submitPenalty(IPaymentService $paymentService, string $installmentId, int $index, string $orderId, int $balance): void
    {
        $this->validate([
            "rate.$installmentId.$index" => 'required'
        ], [
            "rate.$installmentId.$index" => 'Rate is required',
        ]);

        $user = auth()->user();
        if(!$user) return;

        $penalty = round($balance * ($this->rate[$installmentId][$index] /100));

        DB::beginTransaction();

        $result = $paymentService->applyPenalty(
            $this->customer->id,
            $installmentId,
            $index,
            $orderId,
            (int) $penalty,
            $user->id,
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash("alert-$installmentId-$index", ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash("success-$installmentId-$index", 'Penalty applied.');
    }

    /**
     * @throws \Throwable
     */
    public function removePenalty(IPaymentService $paymentService, string $installmentId, int $index, string $orderId): void
    {
        $user = auth()->user();
        if(!$user) return;

        DB::beginTransaction();

        $result = $paymentService->removePenalty(
            $this->customer->id,
            $installmentId,
            $index,
            $orderId,
            $user->id,
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash("alert-$installmentId-$index", ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash("success-$installmentId-$index", 'Penalty waived.');
    }

    public function getInstallmentBills()
    {
        return DB::table('installment_bills')
            ->join('orders', 'installment_bills.order_id', '=', 'orders.order_id')
            ->where('installment_bills.customer_id', $this->customer->id)
            ->where('balance', '>', 0)
            ->whereNotNull('due')
            ->orderBy('due')
            ->orderBy('index')
            ->get();
    }

    public function getBalance()
    {
        $balance = DB::table('customer_balances')
            ->where('customer_id', $this->customer->id)
            ->first();

        if(!$balance) return (object) ['balance' => 0];

        return $balance;
    }

    public function getTransactionHistory()
    {
        return DB::table('transactions')
            ->join('users', 'transactions.cashier', '=', 'users.id')
            ->select('transactions.*', 'users.first_name')
            ->where('transactions.customer_id', $this->customer->id)
            ->orderBy('transactions.created_at', 'desc')
            ->get();
    }

    public function getCodOrders()
    {
        return DB::table('orders')
            ->where('customer_id', $this->customer->id)
            ->where('payment_type', 'cod')
            ->where('status', 1)
            ->get();
    }

    public function getOrderDownPayment(string $orderId)
    {
        $sum = DB::table('payment_methods')
            ->where('order_id', $orderId)
            ->sum('amount');

        return $sum;
    }

    public function getOrderHistory()
    {
        $query = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
            ->where('orders.customer_id', $this->customer->id)
            ->orderByDesc('orders.placed_at');

        return $query->get();
    }

    public function getCodBalance()
    {
        $query = DB::table('cod_balances')
            ->join('orders', 'orders.order_id', '=', 'cod_balances.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->join('users', 'orders.assistant_id', '=', 'users.id')
            ->select(['cod_balances.*', 'orders.*', 'branches.name as branch_name', 'customers.first_name as customer_first_name', 'customers.last_name as customer_last_name', 'users.first_name as assistant_first_name', 'users.last_name as assistant_last_name'])
            ->where('orders..customer_id', $this->customer->id)
            ->where('cod_balances.balance', '>', 0)
            ->orderByDesc('orders.placed_at');

        return $query->get();
    }

    public function getPaymentStatus(string $orderId): string
    {
        $order = DB::table('orders')->where('order_id', $orderId)->first();
        if(!$order) return 'Pending';

        if($order->payment_type == 'full'){
            if($order->status > 0) return 'Fully Paid';
        }

        if($order->payment_type == 'cod'){
            if($order->status == 2) return 'Fully Paid';
            if($order->status == 1) return 'Partially Paid';
        }

        if($order->payment_type == 'installment'){
            $exists = DB::table('installment_bills')
                ->where('order_id', $orderId)
                ->where('balance', '>', 0)
                ->exists();

            if(!$exists) return 'Fully Paid';

            return 'Partially Paid';
        }

        return 'Pending';
    }

    private function getOrder(string $orderId)
    {
        return DB::table('orders')
            ->where('order_id', $orderId)
            ->first();
    }

    public function getDeliveryStatus(string $orderId): string
    {
        $order  = $this->getOrder($orderId);
        if(!$order) return 'Pending';

        if($order->delivery_type == 'pickup') return 'For Pickup';

        $delivered = DB::table('delivery_items')
            ->where('order_id', $orderId)
            ->sum('delivered');

        $outForDelivery = DB::table('delivery_items')
            ->where('order_id', $orderId)
            ->sum('out_for_delivery');

        $toShip = DB::table('delivery_items')
            ->where('order_id', $orderId)
            ->sum('to_ship');

        if($delivered > 0 && $outForDelivery == 0 && $toShip == 0) return 'Delivered';
        if($delivered > 0 && ($outForDelivery > 0 || $toShip > 0)) return 'Partially Delivered';
        if($delivered == 0 && $outForDelivery > 0) return 'Out For Delivery';

        return 'To Ship';
    }

    public function statusDisplay(int $status): string
    {
        return match ($status) {
            0 => 'Pending',
            1, 2 => 'Processed',
            3 => 'Cancelled',
            4 => 'Refunded',
            default => throw new \Exception('Unexpected value'),
        };
    }

    /**
     * @throws \Throwable
     */
    public function collectCod(IPaymentService $paymentService, string $orderId): void
    {
        $this->validate([
            'codReferenceNumbers.' . $orderId => 'required',
            'codAmounts.' . $orderId => 'required',
        ], [
            'codReferenceNumbers.' . $orderId => 'Reference number is required.',
            'codAmounts.' . $orderId => 'Amount is required.',
        ]);

        $payment = [];
        $payment[] = [
            'amount' => $this->codAmounts[$orderId] * 100,
            'reference' => $this->codReferenceNumbers[$orderId],
            'method' => 'Cash',
        ];

        DB::beginTransaction();

        $result = $paymentService->collectCod(
            $this->customer->id,
            $payment,
            auth()->user()?->id,
            Str::uuid()->toString(),
            $this->codReferenceNumbers[$orderId],
            $orderId
        );

        if($result->isFailure()){
            DB::rollBack();
            session()->flash('alert', ErrorHandler::getErrorMessage($result->getError()));
            return;
        }

        DB::commit();
        session()->flash('success', 'Payment received.');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.installment.installment-details', [
            'installment_bills' => $this->getInstallmentBills(),
            'balance' => $this->getBalance(),
            'transaction_history' => $this->getTransactionHistory(),
            'codOrders' => $this->getCodOrders(),
            'orders' => $this->getOrderHistory(),
            'codBalance' => $this->getCodBalance(),
        ]);
    }
}
