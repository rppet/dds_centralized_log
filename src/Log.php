<?php
namespace Hanuas\CentralizedLog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request;

class Log
{
    /**
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * @var string[]
     */
    private $filterArray = ['username', 'user_name', 'password', 'pass_word', 'token', 'jwt', 'mobile', 'user_mobile', 'phone'];

    /**
     * @var string[]
     */
    private $statisticsType = ['every_time', 'every_day'];

    /**
     * Log constructor.
     */
    public function __construct()
    {
        $this->request = Request::instance();
    }

    /**
     * Notes:
     * @param $tableName
     * @param $userId
     * @return bool
     * DateTime:2021/11/3 5:03 下午
     */
    public function addLog($tableName, $data = [], $statisticsType = 'every_time')
    {
        if (!$data){
            return true;
        }

        $data['name'] = $data['name'] ?? $this->request->getMethod() . ':' . $this->request->getPathInfo();
        $data['param'] = $data['param'] ?? json_encode(filter_params($this->request->all(), $this->filterArray));

        //判断访问统计类型
        if (in_array($statisticsType, $this->statisticsType) && $statisticsType == 'every_day' && $this->request->getMethod() == 'GET'){
            $condition = [
                ['app', $data['app']],
                ['type', $data['type']],
                ['name', $data['name']],
                ['param', $data['param']],
                ['user_id', $data['user_id']],
                ['time', '>=', strtotime(date('Y-m-d'))],
                ['time', '<', strtotime(date('Y-m-d', strtotime('+1 day')))]
            ];

            if ($this->incrColumnByCondition($tableName, 'count', $condition)){
                return true;
            }
        }

        $data['time'] = $data['time'] ?? time();

        return DB::table($tableName)->insert($data);
    }

    /**
     * Notes:
     * @param string $table
     * @param string $connection
     * @return bool
     * DateTime:2021/11/3 5:06 下午
     */
    public function checkTableIsExists($table = '', $connection = '')
    {
        if (!$connection){
            $connection = 'mysql';
        }
        if (Schema::connection($connection)->hasTable($table)) {
            return true;
        }
        return false;
    }

    /**
     * Notes:
     * @param string $sql
     * @param string $table
     * @return bool
     * DateTime:2021/11/3 5:07 下午
     */
    public function createTable($tableName = '')
    {
        if ($this->checkTableIsExists($tableName)){
            return true;
        }
        if ($tableName){
            return LogModel::createTable($tableName);
        }
        return true;
    }

    /**
     * Notes:
     * @param string $tableName
     * @param string $column
     * @param array $condition
     * @return bool
     */
    public function incrColumnByCondition(string $tableName, string $column, array $condition = [])
    {
        if (DB::table($tableName)->where($condition)->count() > 0){
            try {
                DB::beginTransaction();
                DB::table($tableName)->where($condition)->lockForUpdate()->increment($column);
                DB::commit();
            } catch (\Exception $exception){
                DB::rollBack();
            }

            return true;
        }

        return false;
    }
}