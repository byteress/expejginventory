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
    public $receiptNumber = '';
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

    public function getInstallmentBills()
    {
        return DB::table('installment_bills')
            ->join('orders', 'installment_bills.order_id', '=', 'orders.order_id')
            ->where('installment_bills.customer_id', $this->customer->id)
            ->where('balance', '>', 0)
            ->orderBy('due')
            ->get();
    }

    public function getBalance()
    {
        $balance = DB::table('customer_balances')
            ->where('customer_id', $this->customer->id)
            ->first();

        if(!$balance) return 0;

        return $balance->balance;
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

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.installment.installment-details', [
            'installment_bills' => $this->getInstallmentBills(),
            'balance' => $this->getBalance(),
            'transaction_history' => $this->getTransactionHistory(),
        ]);
    }
}
