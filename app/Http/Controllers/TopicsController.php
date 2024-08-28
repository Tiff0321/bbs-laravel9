<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * 显示话题列表
     * 用 with() 方法预加载了话题数据的用户数据和分类数据，预加载是为了避免 N+1 问题
     *
     * @param Request $request
     * @param Topic $topic
     * @return Factory|View|Application
     */
	public function index(Request $request, Topic $topic): Factory|View|Application
    {
        $topics = $topic->withOrder($request->order)
            ->with('user', 'category') // 预加载 user 和 category 关联，避免 N+1 问题
            ->paginate(20);
        return view('topics.index', compact('topics'));

	}

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic): Factory|View|Application
    {
		$categories=Category::all();
        return view('topics.create_and_edit', compact('topic','categories'));
	}

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return RedirectResponse
     */
	public function store(TopicRequest $request,Topic $topic): RedirectResponse
    {
        // Topic $topic 是依赖注入，这里的 $topic 是一个空的 Topic 实例
        // $request->all() 获取所有用户的请求数据
        // $topic->fill() 方法将 $request->all() 返回的数据填充到 $topic 实例中
        // $topic->user_id = Auth::id() 为话题的 user_id 字段赋值为当前登录用户的 ID
        // $topic->save() 方法将数据保存到数据库
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();
        return redirect()->to($topic->link())->with('success', '成功创建话题！');
		//return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
        $categories=Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

        return redirect()->to($topic->link())->with('success', '更新成功！');
		//return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '成功删除！');
	}


    /**
     * 上传图片
     *
     * @param Request $request
     * @param ImageUploadHandler $uploader
     * @return array
     */
    public function uploadImage(Request $request, ImageUploadHandler $uploader): array
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success' => false,
            'msg' => '上传失败！',
            'file_path' => ''
        ];

        // 判断是否上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($file, 'topics', Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg'] = '上传成功！';
                $data['success'] = true;
            }
        }
        return $data;
    }
}
