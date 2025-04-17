<?php 
namespace App\Libraries;

class DataParams
{
    public $search = '';
    public $sort = 'id';
    public $order = 'asc';
    public $page = 1;
    public $perPage = null;

    public function  __construct(array $params = [])
    {
        $this->search = $params['search'] ?? '';
        $this->sort = $params['sort'] ?? 'id';
        $this->order = $params['order'] ?? 'asc';
        $this->page = $params['page'] ?? 1;
        $this->perPage = $params['perPage'] ?? null;
    }

    public function getParams()
    {
        return [
            'search' => $this->search,
            'sort' => $this->sort,
            'order' => $this->order,
            'page' => $this->page,
            'perPage' => $this->perPage
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