<?php
/**
 * Created by PhpStorm.
 * User: Ideatolife
 * Date: 5/15/2017
 * Time: 3:20 PM
 */

namespace App\Http\Controllers;

use App\Idea\Base\BaseController;
use App\Models\Idea\Page;
use App\Repositories\PageRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    protected $permissions = [
        "index" => ["code" => "pages", "action" => "read"],
        "pages" => ["code" => "pages", "action" => "read"],
        "destroy" => ["code" => "pages", "action" => "write"],
        "store" => ["code" => "pages", "action" => "write"],
        "update" => ["code" => "pages", "action" => "write"],
    ];

    protected $pageRepository;

    /**
     * @param PageRepository $pageRepository
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(PageRepository $pageRepository, Request $request)
    {
        parent::__construct($request);
        $this->pageRepository = $pageRepository;
    }

    /**
     * Validation Rules
     */
    protected static function validationRules()
    {
        return [
            "store" => [
                'code' => 'required|unique:pages',
            ],
        ];
    }

    /**
     * Validation Messages
     */
    protected static function validationMessages()
    {
        return [
            'store' => [
                'code.required' => 'Code is required.',
                'code.unique' => 'Sorry! This code already exists.',
            ]
        ];
    }

    /**
     * Init
     */
    protected function init()
    {
        $this->setModel(new Page());
        $this->with = ['translations', 'user', 'children', 'parent'];
        $this->withImage = true;
        $this->imageName = "image";
        $this->filePath = "uploads/{user_id}/static_page/";
    }

    /**
     * Description: The following method will fetch all Pages.
     * @return JsonResponse
     */
    public function index()
    {
        return $this->successData($this->pageRepository->findAll());
    }

    /**
     * Description: The following method will fetch one Page.
     *
     * @param int    : the page id
     *
     * @return JsonResponse success or failure
     */
    public function one($id)
    {
        return $this->success('idea::general.general_data_fetch_message', $this->pageRepository->findOne($id));
    }

    /**
     * Function to return all child pages by parent
     *
     * @param $id
     * @return JsonResponse
     */
    public function getParentChildPages($id)
    {
        return $this->successData($this->pageRepository->findChildPages($id));
    }

    /**
     * Description: The following method will add new page to the system
     *
     * @return JsonResponse success or failure
     * @throws \Exception
     */
    public function store()
    {
        return $this->success($this->messages['save_success'], $this->pageRepository->savePage());
    }

    /**
     * Description: The following method will update page
     *
     * @param $id
     * @return JsonResponse success or failure
     */
    public function update($id)
    {
        return $this->success($this->messages['save_success'], $this->pageRepository->updatePage($id));
    }

    /**
     * Description: The following method will delete one Page.
     *
     * @param int    : id
     *
     * @return JsonResponse success or failure
     */
    public function destroy($id)
    {
        return $this->pageRepository->deletePage($id) ? $this->success() : $this->failed();
    }
}
