<?php 


namespace App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DBHelpers
{


    public static function count($dataModel, $data = null)
    {
        try {
            if ($data == null) {
                return $dataModel::count();
            }
            return $dataModel
                ::query()
                ->where($data)
                ->count();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }

    
  
   
    //////  query where filter first data
    public static function query_filter_first($dataModel, $filter)
    {
        try {
            return $dataModel
                ::where($filter)
                ->get()
                ->first();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }

  
    public static function exists($dataModel, $data)
    {
        try {
            return $dataModel
                ::query()
                ->where($data)
                ->exists();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }


    public static function with_query($dataModel, $with_clause = [])
    {
        try {
            return $dataModel
                ::query()
                ->with($with_clause)
                ->orderBy('id', 'DESC')
                ->get();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }


 
    public static function where_query($dataModel, $where_clause = [])
    {
        try {
            return $dataModel
                ::query()
                ->where($where_clause)
                ->get();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }

    public static function with_where_query_filter_first(
        $dataModel,
        $with_clause = [],
        $where = null
    ) {
        try {
            if ($where == null) {
                return $dataModel
                    ::query()
                    ->with($with_clause)
                    ->get()
                    ->first();
            } else {
                return $dataModel
                    ::query()
                    ->with($with_clause)
                    ->where($where)
                    ->get()
                    ->first();
            }
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }

     

    /////// where with query ////
    public static function with_where_query_filter(
        $dataModel,
        $with_clause = [],
        $where = []
    ) {
        try {
            if ($where == null) {
                return $dataModel
                    ::query()
                    ->with($with_clause)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                return $dataModel
                    ::query()
                    ->with($with_clause)
                    ->where($where)
                    ->orderBy('id', 'DESC')
                    ->get();
            }
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }



    //////  query where filter data
    public static function query_filter($dataModel, $filter)
    {
        try {
            return $dataModel
                ::where($filter)
                ->orderBy('id', 'DESC')
                ->get();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }

  

 

    ////// get all query data
    public static function all_data($dataModel)
    {
        try {
            return $dataModel::all();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }

   


   

  
    ////// Update flexible /////
    public static function update_query_v3($dataModel, $data, $filter = null)
    {
        DB::beginTransaction();
        $status = null;

        try {
            if ($filter != null) {
                $status = $dataModel::where($filter)->update($data);
            } else {
                $status = $dataModel::query()->update($data);
            }
            DB::commit(); // execute the operations above and commit transaction
            return $status;
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }

    

    //////// Insert query data /////////
    public static function create_query($dataModel, $data)
    {
        try {
            return $dataModel::create($data);
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }
    }
}
