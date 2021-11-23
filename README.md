# centralized_log
Laravel Centralized access log

## 安装

    "dds_centralized_log": {
      "type": "git",
      "url": "https://github.com/rppet/dds_centralized_log.git"
    }
    
    composer require rppet/dds_centralized_log:dev-master

## 示例代码

    $logServer = new Log();
    // 获取分表表名前缀
    $subTablePrefix = config('centralized_log.table_name');
    // logModel
    $logModel = new LogModel($subTablePrefix, config('centralized_log.type'));
    // 获取完整的表名
    $tableName = $logModel->getTable();
    
    // 如果分表不存在则新建表
    if (!$logServer->checkTableIsExists($tableName)){
        $logServer->createTable($tableName);
    }
    
    //添加日志记录
    $logResult = $logServer->addLog($tableName, $data);
    
## 发布配置文件
    
    php artisan vendor:publish --provider="Hanuas\CentralizedLog\ServiceProvider"
