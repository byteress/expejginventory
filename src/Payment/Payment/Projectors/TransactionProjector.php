<?php

namespace Payment\Projectors;

use Illuminate\Support\Facades\DB;
use PaymentContracts\Events\CodPaymentCollected;
use PaymentContracts\Events\DownPaymentReceived;
use PaymentContracts\Events\FullPaymentReceived;
use PaymentContracts\Events\InstallmentPaymentReceived;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class TransactionProjector extends Projector
{
    public function onDownPaymentReceived(DownPaymentReceived $event): void
    {
        $total = 0;
        foreach ($event->paymentMethods as $method){
            $total += $method['amount'];
        }

        DB::table('transactions')
            ->insert([
                'id' => $event->transactionId,
                'order_id' => $event->orderId,
                'customer_id' => $event->customerId,
                'cashier' => $event->cashier,
                'type' => 'down',
                'amount' => $total,
                'created_at' => $event->createdAt()?->tz(config('app.timezone')),
                'or_number' => $event->orNumber,
            ]);

        for($i = 0; $i < count($event->paymentMethods); $i++){
            DB::table('payment_methods')
                ->insert([
                    'method' => $event->paymentMethods[$i]['method'],
                    'reference' => $event->paymentMethods[$i]['reference'],
                    'amount' => $event->paymentMethods[$i]['amount'],
                    'order_id' => $event->orderId,
                    'transaction_id' => $event->transactionId,
                    'credit' => $event->paymentMethods[$i]['credit'],
                ]);
        }
    }

    public function onInstallmentPaymentReceived(InstallmentPaymentReceived $event): void
    {
        $total = 0;
        foreach ($event->paymentMethods as $method){
            $total += $method['amount'];
        }

        DB::table('transactions')
            ->insert([
                'id' => $event->transactionId,
                'customer_id' => $event->customerId,
                'cashier' => $event->cashier,
                'type' => 'installment',
                'amount' => $total,
                'created_at' => $event->createdAt()?->tz(config('app.timezone')),
                'or_number' => $event->orNumber,
                'order_id' => $event->orderId
            ]);

        for($i = 0; $i < count($event->paymentMethods); $i++){
            DB::table('payment_methods')
                ->insert([
                    'method' => $event->paymentMethods[$i]['method'],
                    'reference' => $event->paymentMethods[$i]['reference'],
                    'amount' => $event->paymentMethods[$i]['amount'],
                    'transaction_id' => $event->transactionId,
                    'order_id' => $event->orderId
                ]);
        }
    }

    public function onCodPaymentCollected(CodPaymentCollected $event): void
    {
        $total = 0;
        foreach ($event->paymentMethods as $method){
            $total += $method['amount'];
        }

        DB::table('transactions')
            ->insert([
                'id' => $event->transactionId,
                'order_id' => $event->orderId,
                'customer_id' => $event->customerId,
                'cashier' => $event->cashier,
                'type' => 'cod',
                'amount' => $total,
                'created_at' => $event->createdAt()?->tz(config('app.timezone')),
                'or_number' => $event->orNumber,
            ]);

        for($i = 0; $i < count($event->paymentMethods); $i++){
            DB::table('payment_methods')
                ->insert([
                    'method' => $event->paymentMethods[$i]['method'],
                    'reference' => $event->paymentMethods[$i]['reference'],
                    'amount' => $event->paymentMethods[$i]['amount'],
                    'order_id' => $event->orderId,
                    'transaction_id' => $event->transactionId
                ]);
        }
    }

    public function onFullPaymentReceived(FullPaymentReceived $event): void
    {
        $total = 0;
        foreach ($event->paymentMethods as $method){
            $total += $method['amount'];
        }

        DB::table('transactions')
            ->insert([
                'id' => $event->transactionId,
                'order_id' => $event->orderId,
                'customer_id' => $event->customerId,
                'cashier' => $event->cashier,
                'type' => 'full',
                'amount' => $total,
                'created_at' => $event->createdAt()?->tz(config('app.timezone')),
                'or_number' => $event->orNumber,
            ]);

        for($i = 0; $i < count($event->paymentMethods); $i++){
            DB::table('payment_methods')
                ->insert([
                    'method' => $event->paymentMethods[$i]['method'],
                    'reference' => $event->paymentMethods[$i]['reference'],
                    'amount' => $event->paymentMethods[$i]['amount'],
                    'order_id' => $event->orderId,
                    'transaction_id' => $event->transactionId,
                    'credit' => $event->paymentMethods[$i]['credit'],
                ]);
        }
    }

    public function onStartingEventReplay(): void
    {
        DB::table('transactions')
            ->truncate();

        DB::table('payment_methods')
            ->truncate();
    }
}
