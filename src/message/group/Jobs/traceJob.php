<?php

namespace App\Plugins\zero\src\message\group\Jobs;

use App\Models\UserModels;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class traceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 5;
    /**
     * 在超时之前任务可以运行的秒数
     *
     * @var int
     */
    public $timeout = 600;
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
        exec("traceroute -I " . $this->zhiling[0], $data);
        $content = "";
        foreach ($data as $value) {
            $content = <<<HTML
{$content}
{$value}
HTML;
        }
        if ($content) {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => $content . " [CQ:at,qq=" . $this->data->user_id . "]"
            ], "send_group_msg");
        } else {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:at,qq=" . $this->data->user_id . "] traceroute命令执行失败,积分已退还"
            ], "send_group_msg");
        }
    }
}
