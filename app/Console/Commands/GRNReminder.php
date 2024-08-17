<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\GRNEnquiryRequest;
use App\Models\ClientPos;
use App\Models\ClientRfqs;
use App\Models\Employers;
use App\Models\Automation;
class GRNReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GRNReminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron Job to remind Buyers to forward GRNs after Delivery';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch the RFQ
        $pos = ClientPos::whereIn('status', ['Delivered', 'Awaiting GRN'])
               ->whereYear('Actual_delivery_date', now()->year)
               ->whereDate('Actual_delivery_date', '<=', Carbon::now()->subDays(2))
               ->get();

        if (!$pos) {
            $this->error('RFQ not found.');
            return;
        }

        foreach($pos as $po){

            $automation_check = Automation::where('po_id',$po->po_id)
                                ->where('type', 'GRN Reminder')
                                ->latest('created_at')
                                ->first();
            if($automation_check && $automation_check->created_at->diffInDays(Carbon::now()) < 14){
                $rfq = ClientRfqs::where('rfq_id',$po->rfq_id)->first();
                $cli_title = clis($po->client_id);
                $resultt = json_decode($cli_title, true);
                $client_name = $resultt[0]['client_name'];
                $assigned_details = empDetails($po->employee_id);
                $assigned = $assigned_details->full_name;
                $rfqcode = "TE-". $resultt[0]['short_code'] . '-RFQ' . preg_replace('/[^0-9]/', '', $rfq->reference_no);

                $rec_mail = $resultt[0]['email'];
                $cc_mail = 'sales@tagenergygroup.net';
                $employee = Employers::where('employee_id', $rfq->employee_id)->first();
                $bcc_mail = $employee->email;

                try {
                    $data = [
                        "po_no" => $po->po_number,
                        "client_shortcode" => $resultt[0]['short_code'],
                        "reference_no" => $rfqcode,
                        "description" => $rfq->description,
                        "assigned" => $assigned,

                    ];

                    Mail::to($rec_mail)
                        ->cc($cc_mail)
                        ->bcc($bcc_mail)
                        ->send(new GRNEnquiryRequest($data));

                    Automation::create([
                        'company_id' => $po->company_id,
                        'rfq_id' => $rfq->rfq_id,
                        'po_id' => $po->po_id,
                        'type' => 'GRN Reminder',
                        'description' => 'Sent a GRN Reminder for NLNG'
                    ]);

                    $this->info('Email Sent Successfully');
                } catch (\Exception $e) {
                    $this->error("Email not sent: " . $e->getMessage());
                }
            }
    }
}
}