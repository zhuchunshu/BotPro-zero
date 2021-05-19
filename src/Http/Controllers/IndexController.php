<?php
namespace App\Plugins\zero\src\Http\Controllers;

use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Markdown;

class IndexController {
    public function show(Content $content){
        $content->title('Zero');
        $content->header('Zero');
        $content->description('Zero插件信息');
        $content->body(Card::make(
            Markdown::make(read_file(plugin_path("zero/README.md")))
        ));
        return $content;
    }
}