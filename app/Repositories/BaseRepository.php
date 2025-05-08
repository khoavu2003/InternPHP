<?php
namespace App\Repositories;

use App\Repositories\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface{
    protected $model;
    public function __construct(){
        $this->setModel();
    }


    abstract public function getModel();

    public function setModel()
    {
        $modelClass = $this->getModel();
        $this->model = new $modelClass();
    }

    public function find($id){
        $result = $this->model->find($id);
        return $result;
    }

    public function create($attributes = []){
        return $this->model->create($attributes);
    }

    public function delete($id){
        $result =$this->find($id);
        $result->delete();
       
       
    }
}