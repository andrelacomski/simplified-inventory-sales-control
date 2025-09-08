<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class Utils {

    static public function validateRequest(Request $request, $arrayValidate) {
        $errors = [];

        $validator = Validator::make($request->all(), $arrayValidate);

        if ($validator->fails()) {
            foreach ($validator->errors()->getMessages() as $item) {
                array_push($errors, $item[0]);
            }
        }

        return $errors;
    }

    static public function createPaginatedResult(Request $request, $model, $wheres, $columnsToFilter, $sortField = '', $sortOrder = 'asc', $with = null) {
        $currentPage = $request->page;

        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $query = $model::whereNotNull('id');

        if (sizeof($wheres) > 0) {
            $query->where($wheres);
        }

        if (isset($with)) {
            $query->with($with);
        }

        if (trim($request->globalFilter) != "") {
            $query->where(function ($q) use ($columnsToFilter, $request) {
                if (isset($columnsToFilter)) {
                    foreach ($columnsToFilter as $c) {
                        $q->orWhere($c, 'LIKE', '%' . $request->globalFilter . '%');
                    }
                }
            });
        }

        if (trim($sortField != "")) {
            $query->orderBy($sortField, $sortOrder);
        }

        $result = $query->paginate($request->rows);

        return $result;
    }
}
