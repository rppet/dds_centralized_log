<?php
namespace Hanuas\CentralizedLog;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogModel extends SubTableAbstract
{
    /**
     * LogModel constructor.
     * @param $tableName
     * @param string $type
     */
    public function __construct($tableName, $type = 'date')
    {
        parent::__construct();

        self::$prefixTable = $tableName;
        self::setTableIdentity($type);
    }

    /**
     * Notes: 创建数据表
     * @param $tableName
     * @return \Illuminate\Database\Schema\Builder
     * DateTime:2021/11/4 11:01 上午
     */
    public static function createTable($tableName)
    {
        $result = Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'MYISAM';
            $table->charset = 'utf8mb4';
            $table->increments('id')->comment('自增id');
            $table->tinyInteger('app')->comment('来源id');
            $table->string('type', 50)->comment('访问类型');
            $table->string('name', 255)->comment('访问名称');
            $table->text('param')->comment('请求参数');
            $table->integer('user_id')->comment('访问者id');
            $table->integer('time')->comment('访问时间');
            $table->integer('count')->comment('访问计数')->default(1);
            $table->index('user_id', 'idx_user_id');
            $table->index('time', 'idx_time');
        });

        return $result ? true : false;
    }
}