<?php

namespace App\Livewire\Admin;

use BranchManagement\Models\Branch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Livewire\SmsSender;
use Illuminate\Support\Str;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public ?string $customerBranch = null;
    public ?string $itemBranch = null;
    public ?string $birthdayBranch = null;

    /**
     * @return Collection<int, object>
     */
    public function getItems(): Collection
    {
        $date = now()->format('Y-m-d');
        $query = DB::table('stock_history')
            ->join('products', 'products.id', '=', 'stock_history.product_id')
            ->whereDate('stock_history.date', $date)
            ->where('stock_history.action', 'Sold')
            ->groupBy('stock_history.product_id')
            ->orderBy('products.model', 'asc')
            ->orderBy('products.description', 'asc');

        if($this->itemBranch) $query->where('stock_history.branch_id', $this->itemBranch);

        return $query->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getCustomers(): Collection
    {
        $query = DB::table('customers')
            ->join('customer_balances', 'customers.id', '=', 'customer_balances.customer_id')
            ->whereExists(function ($query) {
                $date = now()->format('Y-m-d');

                $query->select(DB::raw(1))
                    ->from('installment_bills')
                    ->whereColumn('installment_bills.customer_id', 'customers.id')
                    ->where('installment_bills.balance', '>', 0)
                    ->whereDate('installment_bills.due', $date);
            });

        if($this->customerBranch) $query->where('branch_id', $this->customerBranch);

        return $query->get();
    }
    
    public function getCustomersWithTodayBirthday(): Collection
    {
        $currentMonth = now()->month; // Get the current month (1-12)
        $currentDay = now()->day; // Get the current day (1-31)
    
        // Use Query Builder to select first_name and last_name for customers with today's birthday
        $query = DB::table('customers')
            ->select('first_name', 'last_name','phone','address','dob','id')  // Select the necessary columns
            ->whereMonth('dob', $currentMonth)  // Filter by month
            ->whereDay('dob', $currentDay);      // Filter by day
             // Get the results
            
     if($this->birthdayBranch) $query->where('branch_id', $this->birthdayBranch);
        return $query->get();  // Return the collection of customers
    }
    

    public function getIncome(string $branchId): int
    {
        $totalAmount = 0;
        foreach ($this->getTransactions($branchId) as $transaction) {
            if($transaction->status == 1 || (!$this->isSameDayCancelled($transaction->order_id) && !$this->isSameDayRefunded($transaction->order_id))){
                $totalAmount = $totalAmount + $transaction->total + $transaction->delivery_fee;
            }
        }

        return $totalAmount - $this->getExpensesTotal($branchId);
    }

    /**
     * @return Collection<int, object>
     */
    public function getTransactions(string $branchId): Collection
    {
        $date = now()->format('Y-m-d');

        $query = DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(['transactions.id as transaction_id', 'orders.id as order_number', 'transactions.*', 'orders.*', 'customers.*'])
            ->whereDate('transactions.created_at', $date)
            ->whereIn('transactions.type', ['full', 'down'])
            ->where('orders.previous', 0)
            ->where('orders.branch_id', $branchId);

        return $query->get();
    }

    public function getExpensesTotal(string $branchId): int
    {
        $date = now()->format('Y-m-d');

        $query = DB::table('expenses')
            ->whereDate('date', $date)
            ->where('branch_id', $branchId);

        return $query->sum('amount');
    }

    public function isSameDayCancelled(string $orderId): bool
    {
        $order = DB::table('orders')
            ->where('order_id', $orderId)
            ->first();

        if(!$order || !$order->completed_at) return false;

        $newOrder = DB::table('orders')
            ->where('cancelled_order_id', $orderId)
            ->first();

        if(!$newOrder || !$newOrder->completed_at) return false;

        $orderDate = date('Y-m-d', strtotime($order->completed_at));
        $newOrderDate = date('Y-m-d', strtotime($newOrder->completed_at));

        if($orderDate != $newOrderDate) return false;

        return true;
    }

    public function isSameDayRefunded(string $orderId): bool
    {
        $order = DB::table('orders')
            ->where('order_id', $orderId)
            ->first();

        if(!$order || !$order->completed_at) return false;

        $orderNumber = str_pad((string) $order->id, 12, '0', STR_PAD_LEFT);
        $expense = DB::table('expenses')
            ->where('description', "Refunded Order# $orderNumber")
            ->first();

        if(!$expense || !$expense->created_at) return false;

        $orderDate = date('Y-m-d', strtotime($order->completed_at));
        $newOrderDate = date('Y-m-d', strtotime($expense->created_at));

        if($orderDate != $newOrderDate) return false;

        return true;
    }

    public function hasNotified($customer_id) {

        $date = now()->format('Y-m-d');
        $query = DB::table('sms_logs')
            ->where('customer_id', $customer_id)
            ->whereDate('sent_at', $date);

        return $query->exists();
    }

    public function birthdayCelebrants() {

        $customers = $this->getCustomersWithTodayBirthday();

        foreach ($customers as $customer) {
            $phoneNumbers = $customer->phone;
    
            if (strpos($phoneNumbers, '/') !== false) {
                $phoneArray = explode('/', $phoneNumbers);
                $phoneArray = array_map('trim', $phoneArray);
            } else {
                $phoneArray = [$phoneNumbers]; 
            }
    
            foreach ($phoneArray as $phone) {
                try {
                    $sms = new SmsSender();
                    $sms->sendBirthday($customer->first_name . ' ' . $customer->last_name, $this->formatPhone($phone));
                } catch (\Exception $e) {
                    \Log::error("Failed to send SMS to {$phone} for customer {$customer->id}: {$e->getMessage()}");
                }
            }
        }

    }

    public function notifyDueToday()
    {
        $customers = $this->getCustomers(); 
        $sms = new SmsSender(); 
    
        foreach ($customers as $customer) {

            if($this->hasNotified($customer->id)) continue;

            $phoneNumbers = $customer->phone;
    
            if (strpos($phoneNumbers, '/') !== false) {
                $phoneArray = explode('/', $phoneNumbers);
                $phoneArray = array_map('trim', $phoneArray);
            } else {
                $phoneArray = [$phoneNumbers]; 
            }
    
            foreach ($phoneArray as $phone) {
                try {
                    $sms->send($customer->first_name . ' ' . $customer->last_name, $customer->balance, $this->formatPhone($phone));
                    DB::table('sms_logs')->insert([
                        'customer_id' => $customer->id,
                        'sent_at' => now()
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Failed to send SMS to {$phone} for customer {$customer->id}: {$e->getMessage()}");
                }
            }
        }
    
        // Flash success message
        session()->flash('message', 'All notifications sent successfully!');
    }

    private function formatPhone($phoneNumber)
    {
        if (substr($phoneNumber, 0, 1) === '0') {
            return '+63' . substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }

    

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'branches' => Branch::all(),
            'customers' => $this->getCustomers(),
            'items' => $this->getItems(),
            'birthdays'=>$this->getCustomersWithTodayBirthday()
        ]);
    }
}
