<?php

namespace App\Livewire\Admin\Order;

use App\Exceptions\ErrorHandler;
use CustomerManagement\Models\Customer;
use CustomerManagementContracts\ICustomerManagementService;
use DateTime;
use IdentityAndAccessContracts\IIdentityAndAccessService;
use Illuminate\Support\Facades\DB;
use Jackiedo\Cart\Facades\Cart as CartAlias;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use OrderContracts\IOrderService;
use ProductManagement\Models\Product;
use StockManagement\StockManagementService;
use StockManagementContracts\Exceptions\ErrorCode;
use StockManagementContracts\IStockManagementService;
use Str;
use Throwable;

#[Title('Cart')]
class Cart extends Component
{
    public $prices = [];

    public $quantities = [];

    /**
     * @var array<string, float>
     */
    public array $discounts = [];

    public string $customerType = 'existing';
    public ?string $customerId = null;
    public string $firstName = '';
    public string $lastName = '';
    public string $phone = '';
    public ?string $email = '';
    public string $address = '';
    public string $dob = '';

    public ?string $branch = null;

    public ?string $username = null;
    public ?string $password = null;

    public function mount()
    {
        if (auth()->user()->branch_id) {
            $this->branch = auth()->user()->branch_id;
        } else {
            $this->branch = session()->get('branch');
        }

        $items = CartAlias::getItems();
        foreach ($items as $item) {
            $this->calculateDiscount($item->getHash(), $item->getPrice() * 100);
        }
    }

    private function expireCart(IStockManagementService $stockManagementService): void
    {
        foreach (CartAlias::getItems() as $item) {
            $confirmReservationResult = $stockManagementService->cancelReservation(
                $item->getId(),
                $item->getExtraInfo()['reservation_id'],
                null,
                true,
            );
        }

        CartAlias::destroy();
    }

    /**
     * @throws Throwable
     */
    public function placeOrder(
        ICustomerManagementService $customerManagementService,
        IOrderService $orderService,
        IIdentityAndAccessService $identityAndAccessService,
        IStockManagementService $stockManagementService,
        bool $authorizing = false
    ): void
    {
        if(!$this->customerId){
            $this->validate([
                'firstName' => 'required',
                'lastName' => 'required',
                'phone' => 'required',
                'email' => 'email',
                'address' => 'required',
            ]);
        }

        if(empty(CartAlias::getItems())){
            session()->flash('alert', 'No items added to cart.');
            return;
        }

        if(!$authorizing) $this->reset('username', 'password');

        DB::beginTransaction();
        $customerId = $this->customerId;

        if(!$customerId){
            $customerId = Str::uuid()->toString();
            $customerResult = $customerManagementService->createCustomer(
                $customerId,
                $this->firstName,
                $this->lastName,
                $this->phone,
                $this->address,
                $this->branch,
                $this->email,
                new DateTime($this->dob)
            );

            if ($customerResult->isFailure()) {
                DB::rollBack();
                session()->flash('alert', $customerResult->getError());
                return;
            }
        }

        $items = [];

        foreach (CartAlias::getItems() as $item) {
            $confirmReservationResult = $stockManagementService->confirmReservation(
                $item->getId(),
                $item->getExtraInfo()['reservation_id']
            );

            if ($confirmReservationResult->isFailure()) {
                DB::rollBack();

                if($confirmReservationResult->getError()->getCode() == ErrorCode::RESERVATION_NOT_FOUND->value){
                    $this->expireCart($stockManagementService);
                    session()->flash('alert', 'Your cart has expired');
                    return;
                }
            }

            $items[] = [
                'productId' => $item->getId(),
                'title' => $item->getTitle(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice() * 100,
                'reservationId' => $item->getExtraInfo()['reservation_id']
            ];
        }

        $authorization = null;
        if($this->username && $this->password){
            $authorizationResult = $identityAndAccessService->authorize($this->username, $this->password, serialize($items));
            if($authorizationResult->isFailure()){
                DB::rollBack();
                $this->reset('username', 'password');
                session()->flash('alert-authorization', ErrorHandler::getErrorMessage($authorizationResult->getError()));
                return;
            }

            $authorization = $authorizationResult->getValue();
        }

        $this->reset('username', 'password');

        $orderId = Str::uuid()->toString();
        $orderResult = $orderService->placeOrder(
            $orderId,
            $customerId,
            auth()->user()->id,
            $this->branch,
            $items,
            CartAlias::getExtraInfo('cart_type'),
            $authorization,
        );

        if($orderResult->isFailure()){
            DB::rollBack();

            $error = $orderResult->getError();
            if(in_array($error->getCode(), [1001, 1002])){
                session()->flash('alert-authorization', ErrorHandler::getErrorMessage($error));
                $this->dispatch('authorizationRequired');
                return;
            }

            session()->flash('alert', ErrorHandler::getErrorMessage($error));
            return;
        }

        CartAlias::destroy();
        $this->reset();
        session()->flash('success', 'Order placed successfully');

        DB::commit();
    }

    public function incrementQuantity(IStockManagementService $stockManagementService, $hash)
    {
        $quantity = $this->quantities[$hash];
        $quantity = $quantity + 1;

        $item = CartAlias::getItem($hash);
        $reservationId = $item->getExtraInfo()['reservation_id'];

        DB::beginTransaction();

        $cancelResult = $stockManagementService->cancelReservation(
            $item->getId(),
            $reservationId,
            auth()->user()->id, false
        );

        if ($cancelResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($cancelResult->getError()));
        }

        $cartType = CartAlias::getExtraInfo('cart_type');
        $newReservationId = Str::uuid()->toString();
        $reserveResult = $stockManagementService->reserve(
            $item->getId(),
            $newReservationId,
            $quantity,
            $this->branch,
            auth()->user()->id,
            $cartType != 'regular',
            true
        );

        if ($reserveResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($reserveResult->getError()));
        }

        CartAlias::updateItem($hash, [
            'quantity' => $quantity,
            'extra_info' => [
                'reservation_id' => $newReservationId
            ]
        ]);

        DB::commit();
    }

    public function decrementQuantity(StockManagementService $stockManagementService, $hash)
    {
        $quantity = $this->quantities[$hash];

        if ($quantity == 1) return;

        $quantity = $quantity - 1;

        $item = CartAlias::getItem($hash);
        $reservationId = $item->getExtraInfo()['reservation_id'];

        DB::beginTransaction();

        $cancelResult = $stockManagementService->cancelReservation(
            $item->getId(),
            $reservationId,
            auth()->user()->id, false
        );

        if ($cancelResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($cancelResult->getError()));
        }

        $cartType = CartAlias::getExtraInfo('cart_type');
        $newReservationId = Str::uuid()->toString();
        $reserveResult = $stockManagementService->reserve(
            $item->getId(),
            $newReservationId,
            $quantity,
            $this->branch,
            auth()->user()->id,
            $cartType != 'regular',
            true
        );

        if ($reserveResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($reserveResult->getError()));
        }

        CartAlias::updateItem($hash, [
            'quantity' => $quantity,
            'extra_info' => [
                'reservation_id' => $newReservationId
            ]
        ]);

        DB::commit();
    }

    public function removeItem(IStockManagementService $stockManagementService, $hash)
    {
        $item = CartAlias::getItem($hash);
        $reservationId = $item->getExtraInfo()['reservation_id'];

        $cancelResult = $stockManagementService->cancelReservation(
            $item->getId(),
            $reservationId,
            auth()->user()->id, false
        );

        if ($cancelResult->isFailure()) {
            DB::rollBack();
            return session()->flash('alert', ErrorHandler::getErrorMessage($cancelResult->getError()));
        }

        CartAlias::removeItem($hash);
        if(CartAlias::hasNoItems()){
            CartAlias::destroy();
        }
    }

    public function calculateDiscount(string $hash, int $price)
    {

        $product = $this->getProduct($hash);

        $discount = 0;
        if($product->sale_price > $price)
            $discount = ($product->sale_price - $price) / $product->sale_price;

        $this->discounts[$hash] = round($discount * 100, 2);
    }

    public function updatedPrices($price, $hash)
    {
        $this->updateCartItemPrice($price, $hash, true);
    }

    public function updatedDiscounts(float $percentage, string $hash): void
    {
        $product = $this->getProduct($hash);

        $rate = $percentage / 100;
        $price = $product->sale_price / 100;
        $discount = $price * $rate;
        $discountedPrice = $price - $discount;

        $this->updateCartItemPrice(round($discountedPrice, 2), $hash, false);
    }

    private function updateCartItemPrice(float $price, string $hash, bool $calculateDiscount): void
    {
        $discount = $this->discounts[$hash];
        $item = CartAlias::updateItem($hash, [
            'price' => $price
        ]);

        if(!$calculateDiscount) {
            $this->discounts[$item->getHash()] = $discount;
            return;
        }

        $this->calculateDiscount($item->getHash(), $price * 100);
    }

    public function getProduct($hash)
    {
        $item = CartAlias::getItem($hash);
        $product = Product::find($item->getId());

        return $product;
    }

    public function getItems()
    {
        $items = CartAlias::getItems();

        $this->reset('prices', 'quantities');
        foreach ($items as $hash => $item) {
            $this->prices[$hash] = $item->getPrice();
            $this->quantities[$hash] = $item->getQuantity();
        }

        usort($items, function($a, $b)
        {
            return strcmp($a->getTitle(), $b->getTitle());
        });

        return $items;
    }

    public function searchCustomer(string $search)
    {
        $result = Customer::where(function($q) use ($search) {
            $q->where('first_name', 'LIKE', '%'.$search.'%')
                ->orWhere('last_name', 'LIKE', '%'.$search.'%')
                ->orWhere('email', 'LIKE', '%'.$search.'%');
        })->where('branch_id', $this->branch)
            ->limit(10)->get();

        return array_map(function($item) {
            return [
                'label' => $item['first_name'] . ' ' . $item['last_name'],
                'value' => $item['id']
            ];
        }, $result->toArray());
    }

    public function updatedCustomerId(string $customerId): void
    {
        $customer = Customer::find($customerId);
        if(!$customer) return;

        $this->customerId = $customerId;
        $this->firstName = $customer->first_name;
        $this->lastName = $customer->last_name;
        $this->phone = $customer->phone;
        $this->email = $customer->email;
        $this->address = $customer->address;

        $this->dispatch('dobSelected', dob: $customer->dob);
    }

    public function updatedCustomerType(): void
    {
        $this->reset(
            'customerId',
            'firstName',
            'lastName',
            'email',
            'phone',
            'address',
            'dob'
        );

        $this->dispatch('customerTypeUpdated');
    }

    #[Layout('livewire.admin.base_layout')]
    public function render()
    {
        return view('livewire.admin.order.cart', [
            'items' => $this->getItems()
        ]);
    }
}
