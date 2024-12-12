<?php


namespace App\Repositories;

use App\Models\Idea\Page;
use App\Idea\Types\ExceptionType;
use App\Services\TranslationService;
use Illuminate\Http\Request;

/**
 * Description: The following repository is used to handle all function related to pages
 * Class UserAccountRepository
 * @package App\Repositories\User
 */
class PageRepository
{
    use ExceptionType;

    protected $page;
    protected $translationService;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Page $page, Request $request, TranslationService $translation)
    {
        $this->request = $request;
        $this->page = $page;
        $this->translationService = $translation;
    }

    /**
     * Description: This function will return all pages
     * @author Hassan Mehmood - I2L
     * @return Page
     */
    public function findAll()
    {
        $pages = $this->page::with('children', 'parent')->where('parent_id', null)->get();
        if (!$pages) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $pages;
    }

    /**
     * Description: This function will return one page
     * @param $id
     * @return Page
     * @author Hassan Mehmood - I2L
     */
    public function findOne($id)
    {
        $pages = $this->page::with('children', 'parent')->where('parent_id', $id)->get();
        if (!$pages) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $pages;
    }

    /**
     * Description: This function will return all child pages by parent
     * @param $id
     * @return Page
     * @author Hassan Mehmood - I2L
     */
    public function findChildPages($id)
    {
        $pages = $this->page::with('children', 'parent')->where('parent_id', $id)->get();
        if (! $pages->count()) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        return $pages;
    }

    /**
     * Function to save a page
     *
     * @string params
     * @return Page
     */
    public function savePage()
    {
        $data = $this->request->all();

        // strip out all whitespace
        $cleanCode = str_replace(' ', '_', $data['translations']['en']['title']);

        // convert the string to all lowercase
        $this->page->code = strtolower($cleanCode);
        $this->page->save();

        $this->translationService->insertTranslations($data, $this->page);
        return $this->page;
    }

    /**
     * Function to update a page
     *
     * @string params
     * @param $id
     * @return Page
     */
    public function updatePage($id)
    {
        if (!$id) {
            $this->raiseHttpResponseException('idea::general.record_does_not_exist');
        }

        $data = $this->request->all();

        // strip out all whitespace
        $cleanCode  = str_replace(' ', '_', $data['translations']['en']['title']);

        $this->page = $this->page::where('id', $id)->firstOrFail();

        // convert the string to all lowercase
        $this->page->code = strtolower($cleanCode);
        $this->page->save();

        $this->translationService->insertTranslations($data, $this->page);
        return $this->page;
    }

    /**
     * Description: This function will delete respected page
     * @param $id
     * @return boolean
     * @author Hassan Mehmood - I2L
     */
    public function deletePage($id)
    {
        $page = $this->page::find($id);
        if (!$page) {
            $this->raiseHttpResponseException('cannot_delete_page');
        }

        return ($page->delete()) ? true : false;
    }
}
