<?php

namespace App\Repositories;

use App\Models\Village;

class VillageRepository
{

    /**
     * village
     *
     * @var mixed
     */
    protected $village;

    /**
     * VillageRepository constructor
     *
     * @param  mixed $village
     * @return void
     */
    public function __construct(Village $village)
    {
        $this->village = $village;
    }

    public function model()
    {
        return $this->village;
    }

    public function findByDistrictCode($district_code)
    {
        return $this->village->where(['district_code' => $district_code])->get();
    }

    public function findByNameAndDisctrict($condition = [], $id = null)
    {
        $query = $this->village->where($condition);
        $query = $query->when($id, function ($q) use ($id) {
            $q->where('id', '!=', $id);
        });

        return $query->get();
    }


    public function store($data)
    {
        $save = $this->village->create($data);
        return $save;
    }

    public function deleteById($id)
    {
        $data = $this->village->where('id', $id)->first();
        if ($data)
            $data->delete();

        return $data;
    }

    public function update($form_data, $id)
    {
        $data = $this->village->findOrFail($id);
        $data->update($form_data);

        return $data;
    }
}