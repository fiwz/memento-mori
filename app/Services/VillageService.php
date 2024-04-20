<?php

namespace App\Services;

use App\Repositories\VillageRepository;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use Indonesia;

class VillageService
{

  protected $villageRepository;

    /**
     * constructor of village service
     *
     * @param  mixed $villageRepository
     * @return void
     */
    public function __construct(VillageRepository $villageRepository)
    {
        $this->villageRepository = $villageRepository;
    }

    /**
     * getList
     *
     * Show list of all villages
     * with pagination
     *
     * @return void
     */
    public function getList()
    {
        $data = \Indonesia::paginateVillages($numRows = 50);
        return $data;
    }

    /**
     * store
     *
     * Create new record of village
     *
     * @param  mixed $request
     * @return void
     */
    public function store($request)
    {
        $form_data = $request->all();

        $validator = \Validator::make($form_data, [
            'name' => 'required|string|max:100',
            'district_code' => 'required|digits:6',
            'lat' => 'required|max:11', // 90.0000000 to -90.0000000
            'long' => 'required|max:12', // 180.0000000 to -180.0000000
            'pos' => 'required|digits:5',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                throw new InvalidArgumentException($validator->errors());
            }

            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // check unique village in the district
        $find_village_in_district = $this->checkUniqueVillage($form_data);

        // check if district exists
        $check_district = $this->checkDistrict($form_data);

        /** Set village code */
        $default_district_number = 2001;
        $new_village_code = $form_data['district_code'] . $default_district_number;
        // check existing villages
        $village_of_district = $this->villageRepository->findByDistrictCode($form_data['district_code']);
        if ($village_of_district->count() > 0) {
            $last_village = $village_of_district->last();
            $new_village_code = ((int)$last_village->code) +1;
        }

        // prepare the data to store
        $form_data = [
            'name' => $form_data['name'],
            'code' => $new_village_code,
            'district_code' => $form_data['district_code'],
            'meta' => json_encode([
                'lat' => $form_data['lat'],
                'long' => $form_data['long'],
                'pos' => $form_data['pos']
            ])
        ];

        $result = $this->villageRepository->store($form_data);

        return $result;
    }

    /**
     * Show detail of a village
     *
     * @param  mixed $id
     * @return void
     */
    public function showDetail($id)
    {
        $data = Indonesia::findVillage($id, $with = ['district', 'city', 'province']);
        return $data;
    }

    /**
     * Delete a village from table
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteById($id)
    {
        $data = $this->villageRepository->deleteById($id);
        if (!$data) {
            $msg = 'Error when deleting data. Data not found.';
            if (request()->wantsJson()) {
                throw new \InvalidArgumentException($msg);
            }

            return [
                'success' => false,
                'errors' => $msg
            ];
        }

        return $data;
    }

    /**
     * Update a village data
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update($request, $id)
    {
        $form_data = $request->all();

        $validator = \Validator::make($form_data, [
            'name' => ['required', 'string', 'max:100'],
            'district_code' => 'required|digits:6',
            'lat' => 'required|max:11', // 90.0000000 to -90.0000000
            'long' => 'required|max:12', // 180.0000000 to -180.0000000
            'pos' => 'required|digits:5',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                throw new InvalidArgumentException($validator->errors());
            }

            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // check unique village in the district
        $find_village_in_district = $this->checkUniqueVillage($form_data, $id);

        // check if district exists
        $check_district = $this->checkDistrict($form_data);

        // prepare the data to store
        $form_data = [
            'name' => $form_data['name'],
            'district_code' => $form_data['district_code'],
            'meta' => json_encode([
                'lat' => $form_data['lat'],
                'long' => $form_data['long'],
                'pos' => $form_data['pos']
            ])
        ];

        $result = $this->villageRepository->update($form_data, $id);

        return $result;
    }

    /**
     * checkUniqueVillage
     *
     * An additional function to check the village name in a district
     * This function is made because the are many duplicate village names in indonesia_villages table,
     * but there may be only single village name in a district.
     *
     * @param  mixed $form_data
     * @param  mixed $id
     * @return void
     */
    public function checkUniqueVillage($form_data, $id = null)
    {
        $find_village_in_district = $this->villageRepository->findByNameAndDisctrict([
            'name' => $form_data['name'],
            'district_code' => $form_data['district_code']
        ], $id);

        if ($find_village_in_district->count() > 0) {
            $msg  = "Failed to save data. Village is already exist.";
            if (request()->wantsJson())
                throw new InvalidArgumentException($msg);

            return [
                'success' => false,
                'errors' => $msg
            ];
        }

        return $find_village_in_district;
    }

    /**
     * checkDistrict
     *
     * An additional function to check if district is exist
     *
     * @param  mixed $form_data
     * @return void
     */
    public function checkDistrict($form_data)
    {
        // check if district exists
        $check_district = \Indonesia::search($form_data['district_code'])->allDistricts();
        if ($check_district->count() <= 0) {
            $msg = "Failed to save data. We can not find the district.";
            if (request()->wantsJson())
                throw new InvalidArgumentException($msg);

            return [
                'success' => false,
                'errors' => $msg
            ];
        }

        return $check_district;
    }
}