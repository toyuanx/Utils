<?php
/**
 * pdo批量insert语句，防止sql注入绑定
 *
 * User: 原子酱
 * Date: 2019/3/11
 * Time: 14:45
 */

class batchInsert
{
    /**
     * 处理参数
     * @param $tableKey
     * @param array $data
     * @return array
     */
    public static function opParams($tableKey, array $data)
    {
        $bindArray = [];

        $tableKey = array_keys($tableKey);

        //insert语句的key字段
        $tableKeyStr = '`' . implode('`,`', $tableKey) . '`,`ctime`';

        // value
        $tableValueStr = '';
        $ctime = date("Y-m-d H:i:s");
        $i = 1;
        foreach ($data as $k => $v) {
            $temp = [];
            foreach ($tableKey as $bindValue) {
                $temp[] = ':' . $bindValue . $i;
                if (!isset($v[$bindValue])) {
                    Logger::info("表头字段 $bindValue 对应数据为空");
                    $v[$bindValue] = '';
                }
                $bindArray[$bindValue . $i] = $v[$bindValue]; //bind数组
            }
            $temp[] = ':ctime' . $i;
            $bindArray['ctime' . $i] = $ctime;
            $tableValueStr .= "(" . implode(",", $temp) . "),";
            $i++;
        }
        $tableValueStr = rtrim(trim($tableValueStr), ','); //insert语句value字段

        // update
        $updates = array_map(function ($k) {
            return "{$k} = VALUES({$k})";
        }, $tableKey);
        $updates = implode(',', $updates) . ",mtime = VALUES(ctime)";//insert语句update的字段

        return [
            'tableKeyStr' => $tableKeyStr,
            'tableValueStr' => $tableValueStr,
            'updates' => $updates,
            'bindArray' => $bindArray,
        ];
    }

}


function batchInsert(array $data)
{
    // 表名一定要在函数中定义，不可图方便作为参数传入

    $table = 'table';
    if (empty($data)) {
        Logger::error(__METHOD__ . " : 无数据");
        return false;
    }

    $tableKey = [
        'id',
        'name',
        'XXX'
    ];
    $sqlParams = BatchInsertLogic::opParams($tableKey, $data);

    $tableKeyStr = $sqlParams['tableKeyStr'];
    $tableValueStr = $sqlParams['tableValueStr'];
    $updates = $sqlParams['updates'];
    $bindArray = $sqlParams['bindArray'];

    Logger::info(__METHOD__ . " : 开始向表{$table}的({$tableKeyStr})列插入数据");

    $sql = "INSERT INTO {$table} ({$tableKeyStr}) VALUES $tableValueStr ON DUPLICATE KEY UPDATE $updates";

    try {
        $result = getDI()->get('wDatabase')->execute($sql, $bindArray);
        Logger::info(__METHOD__ . " : 向表{$table}的({$tableKeyStr})列插入数据结束");

        return $result;
    } catch (\Exception $e) {
        Logger::error(__METHOD__ . " : 表{$table}执行操作出错, " . $e->getCode() . ' ' . $e->getMessage());
        throw new \Exception('保存错误');
    }
}
