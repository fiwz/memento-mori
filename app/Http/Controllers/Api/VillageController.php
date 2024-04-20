<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VillageService;

class VillageController extends Controller
{
    private $villageService;
    private $result;

    /**
     * __construct
     *
     * @param  mixed $villageService
     * @return void
     */
    public function __construct (VillageService $villageService)
    {
        $this->villageService = $villageService;
        $this->result = [
            'status' => 200,
            'message' => 'Data fetched successfully'
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(
        // 'hasil all',
        // // \Indonesia::search('jakarta')->all()->toArray(),
        // // \Indonesia::search('jakarta')->allProvinces()
        // 'hasil prov',
        // \Indonesia::search('jakarta')->paginateProvinces(3)->toArray(),
        // // \Indonesia::search('jakarta')->allCities()
        // 'hasil city',
        // \Indonesia::search('jakarta')->paginateCities(3)->toArray(),
        // // \Indonesia::search('jakarta')->allDistricts()
        // 'hasil district',
        // \Indonesia::search('jakarta')->paginateDistricts(3)->toArray(),
        // // \Indonesia::search('jakarta')->allVillages()
        // 'hasil desa',
        // \Indonesia::search('jakarta')->paginateVillages(3)->toArray()
        // );

        try {
            $this->result['data'] = $this->villageService->getList();
            if ( isset($this->result['data']['success']) && (!$this->result['data']['success']) )
                throw new \InvalidArgumentException($this->result['data']['errors']);

        } catch (\InvalidArgumentException $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => json_decode($e->getMessage()) ? json_decode($e->getMessage()) : $e->getMessage(),
                'data' => []
            ];
        } catch (Exception $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($this->result, $this->result['status']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $this->result['data'] = $this->villageService->store($request);
            $this->result['message'] = 'Successfully store data';
            if ( isset($this->result['data']['success']) && (!$this->result['data']['success']) )
                throw new \InvalidArgumentException($this->result['data']['errors']);

        } catch (\InvalidArgumentException $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => json_decode($e->getMessage()) ? json_decode($e->getMessage()) : $e->getMessage(),
                'data' => []
            ];
        } catch (Exception $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($this->result, $this->result['status']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $this->result['data'] = $this->villageService->showDetail($id);
            if ( isset($this->result['data']['success']) && (!$this->result['data']['success']) )
                throw new \InvalidArgumentException($this->result['data']['errors']);

        } catch (\InvalidArgumentException $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => json_decode($e->getMessage()) ? json_decode($e->getMessage()) : $e->getMessage(),
                'data' => []
            ];
        } catch (Exception $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($this->result, $this->result['status']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $this->result['data'] = $this->villageService->update($request, $id);
            $this->result['message'] = 'Data has been successfully updated';
            // dd('a');
            if ( isset($this->result['data']['success']) && (!$this->result['data']['success']) )
                throw new \InvalidArgumentException($this->result['data']['errors']);

        } catch (\InvalidArgumentException $e) {
            // dd('b');

            report($e);
            $this->result = [
                'status' => 500,
                'message' => json_decode($e->getMessage()) ? json_decode($e->getMessage()) : $e->getMessage(),
                'data' => []
            ];
        } catch (Exception $e) {
            // dd('c');

            report($e);
            $this->result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($this->result, $this->result['status']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->result['data'] = $this->villageService->deleteById($id);
            $this->result['message'] = 'Successfully delete data';
            if ( isset($this->result['data']['success']) && (!$this->result['data']['success']) )
                throw new \InvalidArgumentException($this->result['data']['errors']);

        } catch (\InvalidArgumentException $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => json_decode($e->getMessage()) ? json_decode($e->getMessage()) : $e->getMessage(),
                'data' => []
            ];
        } catch (Exception $e) {
            report($e);
            $this->result = [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }

        return response()->json($this->result, $this->result['status']);
    }
}
