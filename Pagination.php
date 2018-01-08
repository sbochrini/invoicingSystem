<?php

class Pagination
{

    public $limit;
    public $next_page=1;
    public $previous_page=1;
    public $total_pages;
    public $total_records;
    public $page;
    public $offset;


    public function __construct($limit,$total_records)
    {
        $this->limit=$limit;
        $this->total_records=$total_records;
        $this->total_pages = ceil($total_records / $limit);
    }

    /**
     * set current page and offset
     */
    public function setPageOffset()
    {
        if( isset($_GET['page'] ) ) {
            $this->page = $_GET['page'] - 1;
            $this->offset = $this->limit * $this->page ;
            if($_GET['page']==$this->total_pages){
                $this->next_page=$_GET['page'];
            }else{
                $this->next_page=$_GET['page']+1;
            }
            if($_GET['page']==1){
                $this->previous_page=$_GET['page'];
            }else{
                $this->previous_page=$_GET['page']-1;
            }
        }else {
            $this->page = 0;
            $this->offset = 0;
        }
    }

}