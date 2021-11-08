<?php
namespace Hanuas\CentralizedLog;

use Illuminate\Database\Eloquent\Model;

abstract class SubTableAbstract extends Model
{
    /**
     * 表名前缀
     */
    public static $prefixTable;

    /**
     * 分表标识
     */
    public static $identity;

    /**
     * Notes:获取表名
     * @return string
     * DateTime:2021/11/3 9:26 上午
     */
    public function getTable()
    {
        return static::$prefixTable . static::$identity;
    }

    /**
     * @inheritdoc
     */
    public static function setTableIdentity(string $identity): void
    {
        static::$identity = self::getTableIdentity($identity);
    }

    /**
     * Notes:获取分表标识
     * DateTime:2021/11/3 9:29 上午
     */
    public static function getTableIdentity($type = '')
    {
        switch ($type){
            case 'date':
            default:
                $identity = '_' . date('Ym');
                break;
        }

        return $identity;
    }
}