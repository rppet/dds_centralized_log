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

    private $encryptKey;

    /**
     * Log constructor.
     */
    public function __construct()
    {
        $this->request = Request::instance();
        $this->encryptKey = config('centralized_log.encrypt_key');
    }

    /**
     * Notes:
     * @param $tableName
     * @param $userId
     * @return bool
     * DateTime:2021/11/3 5:03 下午
     */
    public function addLog($tableName, $data = [])
    {
        if (!$data){
            return true;
        }

        $data['url'] = $data['url'] ?? $this->request->getMethod() . ':' . $this->request->getPathInfo();
        $data['param'] = $data['param'] ?? $this->encryptData(json_encode($this->request->all()));
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
     * Notes:加密数据
     * @param $data
     * @return false|string
     * DateTime:2021/11/16 4:17 下午
     */
    private function encryptData($data)
    {
        $key = $this->encryptKey;
        return encrypt_data($data, $key);
    }
}