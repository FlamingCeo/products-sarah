<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\Models\QueueProduct;
use DB;
use \App\Models\Products;
use \App\Models\User;



class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $productToQueue = QueueProduct::where('status',0)->pluck("product_id")->toArray();
       // dd($productToQueue);

        $userEmailList = User::select("email")->pluck('email')->toArray();
        //dd($userEmailList);
        foreach ($productToQueue as $key => $value) {
            # code...
            $prod = Products::where("id",$value)->first();
            $name = $prod->name;
            //dd($name);

            echo "1";
            foreach ($userEmailList as $key1 => $value1) {
                echo "2";
                $status = DB::table("subscribe")
                ->where("product_id",$value)
                ->where("user_email",$value1)
                ->get();

                //dd($status->toArray());

                if(!$status->toArray()){
                    echo "email";
                    $details['email'] = $value1;
                    $details['name'] = $name;
                    dispatch(new \App\Jobs\TestEmailJob($details,$name));
                    DB::table("subscribe")
                    ->insert([
                        "product_id" =>$value,
                        "user_email" =>$value1
                    ]);
                

                }

                
            }
            QueueProduct::where("product_id", $value)
                            ->update(["status"=> 1]);

        }

   
        // return response()->json(['message'=>'Mail Send Successfully!!']);
        // return response()->json([
        //     'success' => true,
        //     'data' => [],
        //     'message' => 'The data has been inserted',
        //   ], 200);

        return 0;
    }
}
