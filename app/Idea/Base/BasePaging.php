<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Base;

use Illuminate\Database\Eloquent\Builder;
use JsonSerializable;

class BasePaging implements JsonSerializable
{
    /**
     * @var \Illuminate\Database\Query\Builder $query
     */
    protected $query;

    /**
     * @var \Illuminate\Pagination\LengthAwarePaginator $results
     */
    protected $results;

    /**
     * total item per page
     *
     * @var int $perPage
     */
    protected $perPage;

    /**
     * current select page
     *
     * @var int $page
     */
    protected $page;

    /**
     * @var string $columns
     */
    protected $columns = ['*'];

    /**
     * @var string $pageKey
     */
    protected $pageKey = 'page';

    public function __construct(Builder $query, $perPage = null, $page = null, $columns = null, $pageKey = null)
    {
        $this->query = $query;
        if ($columns) {
            $this->columns = $columns;
        }
        if ($pageKey) {
            $this->pageKey = $query;
        }

        //set perPage and $page params based on the params and request
        $this->buildParams($perPage, $page);

        //run the actual Illuminate pagination
        $this->paginate();
    }

    public function buildParams($perPage = null, $page = null)
    {
        $maximumPerPage = env("PAGINATION_MAXIMUM_PER_PAGE", 1000);
        $requestPerPage = request("per_page");

        //if the number of items per page pass the allowed number
        //then set to the maximum
        if ($requestPerPage > $maximumPerPage) {
            $requestPerPage = $maximumPerPage;
        }

        $requestPage = request("page");

        //if per page sent as param then take otherwise check the request object
        //if request empty, then take the default
        $this->perPage = $perPage ? $perPage : $requestPerPage ? $requestPerPage : env("PAGINATION_PER_PAGE", 10);
        $this->page    = $page ? $page : $requestPage ? $requestPage : env("PAGINATION_DEFAULT_PAGE", 0);
    }

    public function paginate()
    {
        $this->results = $this->query->paginate($this->perPage, $this->columns, $this->pageKey, $this->page);
    }

    public function count()
    {
        return $this->results->count();
    }

    public function currentPage()
    {
        return $this->results->currentPage();
    }

    public function firstItem()
    {
        return $this->results->firstItem();
    }

    public function hasMorePages()
    {
        return $this->results->hasMorePages();
    }

    public function lastPage()
    {
        return $this->results->lastPage();
    }

    public function nextPageUrl()
    {
        return $this->results->nextPageUrl();
    }

    public function perPage()
    {
        return $this->results->perPage();
    }

    public function previousPageUrl()
    {
        return $this->results->previousPageUrl();
    }

    public function total()
    {
        return $this->results->total();
    }

    public function url($page)
    {
        return $this->results->url($page);
    }

    public function data()
    {
        return $this->results->items();
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'total'     => $this->total(),
            'per_page'  => $this->perPage(),
            'page'      => $this->currentPage(),
            'items'     => $this->data(),
            'last_page' => $this->lastPage(),
            'has_more'  => $this->hasMorePages(),
        ];
    }
}
