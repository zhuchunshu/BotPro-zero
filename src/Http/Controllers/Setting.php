<?php
namespace App\Plugins\zero\src\Http\Controllers;

use ZipArchive;
use Faker\Factory;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Illuminate\Http\Request;
use Dcat\Admin\Layout\Content;
use App\Services\PluginManager;
use Madnest\Madzipper\Madzipper;
use App\Admin\Repositories\Plugin;
use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Plugin as ModelsPlugin;
use App\Plugins\zero\src\Http\Repositories\Option as HttpRepositoriesOption;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
class Setting {

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return new Grid(null, function (Grid $grid) {
            $grid->column('id', '标识')->explode()->label();
            $grid->column('name', '名称')->explode('\\')->label();
            $grid->column('setting', '操作')->display(function($setting){
                if(get_options_count($setting)){
                    $id = Option::where('name',$setting)->first()->id;
                }else{
                    $id = Option::insertGetId([
                        "name" => $setting,
                        "value" => 0
                    ]);
                }
                return "<a href='setting/".$id."'>设置</a>";
            });
            $grid->disableRowSelector();
            //$grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableBatchDelete();
            $grid->disablePagination();
            $grid->model()->setData($this->generate());
        });
    }

    /**
     * 获取所有插件
     *
     * @return array
     */
    public function generate()
    {
        $data = include plugin_path("zero/src/lib/setting.php");
        return $data;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Plugin(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('path');
            $show->field('class');
            $show->field('status');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new HttpRepositoriesOption(), function (Form $form) {
            $form->display('name','标识');
            $form->text('value','值');
        });
    }
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title="插件设置";

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
        //        'index'  => 'Index',
        //        'show'   => 'Show',
        //        'edit'   => 'Edit',
        //        'create' => 'Create',
    ];

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title ?: admin_trans_label();
    }

    /**
     * Get description for following 4 action pages.
     *
     * @return array
     */
    protected function description()
    {
        return $this->description;
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['edit'] ?? trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['create'] ?? trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, Form $form)
    {
        return $this->form()->update($id);
    }

}