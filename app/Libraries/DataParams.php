<?php 
namespace App\Libraries;

class DataParams
{
    public $search = '';
    public $sort = 'id';
    public $order = 'asc';
    public $page = 1;
    public $perPage = null;
    public $level = null;
    public $enrolledCourseIds = null;

    public function  __construct(array $params = [])
    {
        $this->search = $params['search'] ?? '';
        $this->sort = $params['sort'] ?? 'id';
        $this->order = $params['order'] ?? 'desc';
        $this->page = $params['page'] ?? 1;
        $this->perPage = $params['perPage'] ?? null;
        $this->level = $params['level'] ?? null;
        $this->enrolledCourseIds = $params['enrolledCourseIds'] ?? null;
    }

    public function getParams()
    {
        return [
            'search' => $this->search,
            'sort' => $this->sort,
            'order' => $this->order,
            'page' => $this->page,
            'perPage' => $this->perPage,
            'level' => $this->level,
            'enrolledCourseIds' => $this->enrolledCourseIds
        ];
    }

    public function getSortUrl($column, $baseUrl)
    {
        $params = $this->getParams();

        $params['sort'] = $column;
        $params['order'] = ($column == $this->sort && $this->order == 'asc') ? 'desc' : 'asc';

        $queryString = http_build_query(array_filter($params));

        return $baseUrl . '?' . $queryString;
    }

    public function getResetUrl($baseUrl)
    {
        return $baseUrl;
    }

    public function isSortedBy($column)
    {
        return $this->sort == $column;
    }

    public function getSortDirection()
    {
        return $this->order;
    }

}
?>