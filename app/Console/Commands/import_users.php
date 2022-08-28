<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class import_users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import_users';

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
     * @return mixed
     */
    public function handle()
    {
       $this->excel();
    }
    public function excel()
    {
        $string = public_path('cliensts') . '/clients.xlsx';
        Excel::load($string, function($reader) {

            foreach ($reader->all() as $items)
            {
                foreach ($items as $item)
                {
                    if (isset($item['telefon']) && !empty($item['telefon'])){
                        try{
                            DB::table('users')->insert([
                                'name'=>$item['nazvanie'],
                                'phone'=>$item['telefon'],
                                'password'=>bcrypt($item['telefon']),
                            ]);
                        }catch (\Exception $exception)
                        {

                        }

                        echo $item['telefon'] .PHP_EOL;
                    }
                }
            }

        });
        dd('done');
    }
}
