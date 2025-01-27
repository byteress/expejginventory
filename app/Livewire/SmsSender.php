<?php

namespace App\Livewire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SmsSender extends Component
{

    public function send($name, $balance, $contact) {

        $currentDateTime = date('dM H:i'); 
        $balance = @money($balance);

        $message = <<<EOT
        {$currentDateTime}: Hi {$name}! This is a friendly reminder from Jenny Grace Furniture Homestore.

        Your outstanding balance of {$balance} is due today. Please settle your balance at your earliest convenience. Please disregard if already paid.
        EOT;

        
        $data = [
            'sender_id' =>  'PhilSMS',
            'recipient' => '+639568104939',
            'message' => $message,
        ];

        $response = Http::withToken('944|9Szci3KSbDkuxNGOzsL9nRycelhylzLoidyCNf4u')
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post('https://app.philsms.com/api/v3/sms/send', $data);

        if ($response->successful()) {
           return true;
        } else {
           return false;
        }

    }

    public function render()
    {
        return view('livewire.sms-sender');
    }
}
