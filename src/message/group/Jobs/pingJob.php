<?php

namespace App\Plugins\zero\src\message\group\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class pingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $data;
    private $zhiling;
    public function __construct($data,$zhiling)
    {
        $this->data = $data;
        $this->zhiling = $zhiling;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        exec("ping -c 4 ".$this->zhiling[1], $data);
        $content = "";
            foreach ($data as $value) {
                $content = <<<HTML
{$content}
{$value}
HTML;
            }
            if($content){
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => $content." [CQ:at,qq=".$this->data->user_id."]"
                ], "send_group_msg");
            }else{
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "[CQ:at,qq=".$this->data->user_id."] ping命令执行失败"
                ], "send_group_msg");
            }
    }
}
