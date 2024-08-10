<?php

namespace Payment\Projectors;

use Illuminate\Support\Facades\DB;
use OrderContracts\Events\OrderCancelled;
use PaymentContracts\Events\InstallmentInitialized;
use PaymentContracts\Events\InstallmentPaymentReceived;
use PaymentContracts\Events\PenaltyApplied;
use PaymentContracts\Events\PenaltyRemoved;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class InstallmentBillsProjector extends Projector
{
    public function onInstallmentInitialized(InstallmentInitialized $event): void
    {
        foreach ($event->installments as $key => $installment) {
            DB::table('installment_bills')
                ->insert([
                    'order_id' => $event->orderId,
                    'customer_id' => $event->customerId,
                    'installment_id' => $event->installmentId,
                    'index' => $key,
                    'due' => date('Y-m-d', strtotime($installment['due'])),
                    'penalty' => 0,
                    'balance' => $installment['balance'],
                ]);
        }
    }

    public function onInstallmentPaymentReceived(InstallmentPaymentReceived $event): void
    {
        $total = 0;
        foreach($event->paymentMethods as $dp){
            $total += $dp['amount'];
        }

        $bills = DB::table('installment_bills')
            ->where('customer_id', $event->customerId)
            ->orderBy('due')
            ->get();

        foreach ($bills as $bill) {
            $deduct = $bill->balance;
            if($bill->balance > $total){
                $deduct = $total;
            }

            DB::table('installment_bills')
                ->where('order_id', $bill->order_id)
                ->where('customer_id', $bill->customer_id)
                ->where('installment_id', $bill->installment_id)
                ->where('index', $bill->index)
                ->decrement('balance', $deduct);

            $total -= $deduct;
            if($total <= 0)
                break;
        }
    }

    public function onPenaltyApplied(PenaltyApplied $event): void
    {
        DB::table('installment_bills')
            ->where('order_id', $event->orderId)
            ->where('customer_id', $event->customerId)
            ->where('installment_id', $event->installmentId)
            ->where('index', $event->index)
            ->increment('balance', $event->amount);

        DB::table('installment_bills')
            ->where('order_id', $event->orderId)
            ->where('customer_id', $event->customerId)
            ->where('installment_id', $event->installmentId)
            ->where('index', $event->index)
            ->update([
                'penalty' => $event->amount,
            ]);
    }

    public function onPenaltyRemoved(PenaltyRemoved $event): void
    {
        DB::table('installment_bills')
            ->where('order_id', $event->orderId)
            ->where('customer_id', $event->customerId)
            ->where('installment_id', $event->installmentId)
            ->where('index', $event->index)
            ->decrement('balance', $event->amount);

        DB::table('installment_bills')
            ->where('order_id', $event->orderId)
            ->where('customer_id', $event->customerId)
            ->where('installment_id', $event->installmentId)
            ->where('index', $event->index)
            ->update([
                'penalty' => 0,
            ]);
    }

    public function onOrderCancelled(OrderCancelled $event): void
    {
        DB::table('installment_bills')
            ->where('order_id', $event->orderId)
            ->delete();
    }

    public function onStartingEventReplay(): void
    {
        DB::table('installment_bills')
            ->truncate();
    }
}
