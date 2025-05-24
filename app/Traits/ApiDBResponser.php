<?php

namespace App\Traits;
//use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
// use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait ApiDBResponser
{

	private function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}


	protected function showAll($collection, $count, $code = 200)
	{
		$transformer = "";
		if ($collection == "") {
			return $this->successResponse(['data' => []], $code);
		} else {
			$collection = $this->sortData($collection);
			$collection = $this->paginate($collection);
			// dd($collection);
			$perPage = 20;
			$page = 1;
			if (request()->has('per_page')) {
				$perPage = (int) request()->per_page;
			}
			if (request()->has('page')) {
				$page = (int) request()->page;
			}
			$results = new Collection(DB::select($collection));
			$response = new LengthAwarePaginator($results, $count[0]->count, $perPage, $page, [
				// $response = new LengthAwarePaginator($results, $count[0]['count'], $perPage, $page, [
				'path' => LengthAwarePaginator::resolveCurrentPath(),
			]);

			$response->appends(request()->all());
			$response = $this->transformData($response, $transformer);
			$response = $this->cacheResponse($response);
			return $this->successResponse($response, $code);
		}
	}


	protected function showForSelect(Collection $collection, $code = 200)
	{
		if ($collection->isEmpty()) {
			return $this->successResponse(['data' => $collection], $code);
		}
		$transformer = "";
		/* 		$transformer = $collection->first()->transformer;*/
		// $collection = $this->filterData($collection, $transformer);
		$collection = $this->sortData($collection, $transformer);
		$collection = $this->transformData($collection, $transformer);
		$collection = $this->cacheResponse($collection);

		return $this->successResponse($collection, $code);
	}


	protected function showOne(Model $instance, $code = 200)
	{
		//$transformer = $instance->transformer;
		$transformer = "";
		$instance = $this->transformData($instance, $transformer);

		return $this->successResponse(['data' => $instance], $code);
	}
	protected function showMessage($message, $code = 200)
	{
		return $this->successResponse(['data' => $message], $code);
	}

	public function filterData(Builder $collection, $transformer)
	{
		$flag = 0;
		//select * from "subjects" where "subj_code"::text ILIKE 'a' and "subjects"."deleted_at"...
		//$instance=$collection->getModel();
		$instance = $transformer->getAttributes();
		//dd($instance);
		foreach (request()->query() as $query => $value) {
			//$attribute = $transformer::originalAttribute($query);
			//dd($instance->getAttributes());
			//if (property_exists($instance,$query)) {
			if (array_key_exists($query, $instance)) {
				$attribute = $query;
			} else {
				unset($attribute);
			}

			if (isset($attribute, $value)) {
				if ($flag == 0) {
					$flag = 1;
					if (is_numeric($value)) {
						$collection = $collection->where($attribute, '=', $value);
					} else {
						$collection = $collection->where($attribute, 'ILIKE', '%' . $value . '%');
					}
				} else {
					if (is_numeric($value)) {
						$collection = $collection->orWhere($attribute, '=', $value);
					} else {
						$collection = $collection->orWhere($attribute, 'ILIKE', '%' . $value . '%');
					}
				}
				// $collection = $collection->where($attribute,'ILIKE',$value);
			}
		}

		return $collection;
	}

	protected function sortData($collection)
	{
		$order = "";
		if (request()->has('sort_by')) {
			$order = " order by " . request()->sort_by;
			if (request()->order == 'desc') {
				$order = $order . " desc";
			}
		}
		$collection = $collection . $order;
		return $collection;
	}

	protected function paginate($collection)
	{
		$rules = [
			'per_page' => 'integer|min:2|max:100'
		];

		Validator::validate(request()->all(), $rules);
		//
		// $page = LengthAwarePaginator::resolveCurrentPage();
		//
		$perPage = 30;
		$page = 1;
		if (request()->has('per_page')) {
			$perPage = (int) request()->per_page;
		}
		if (request()->has('page')) {
			$page = (int) request()->page;
		}
		$offset = ($perPage * ($page - 1));
		$paginated = $collection . " limit " . $perPage . " offset " . $offset;
		//echo "per_page->".$perPage;
		// $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

		// $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
		// 	'path' => LengthAwarePaginator::resolveCurrentPath(),
		// ]);
		//
		// $paginated->appends(request()->all());

		return $paginated;
	}

	protected function transformData($data, $transformer)
	{
		//$transformation = fractal($data, new $transformer);
		$transformation = $data;
		if (is_array($transformation)) {
			return $transformation;
		} else {
			return $transformation->toArray();
		}
	}

	protected function cacheResponse($data)
	{
		$url = request()->url();
		$queryParams = request()->query();

		ksort($queryParams);

		$queryString = http_build_query($queryParams);

		$fullUrl = "{$url}?{$queryString}";

		return Cache::remember($fullUrl, 30 / 60, function () use ($data) {
			return $data;
		});
	}
}
