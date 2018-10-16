<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/10/10
 * Time: 9:51
 */
namespace common\components\wechat\src;

/**
 * Class Application
 * @package app\extensions
 * @method static \common\components\wechat\src\mini\MiniProgram MiniProgram
 */
class Application{
    /**
     * @param $className
     * @param $arguments
     * @author sugar
     * @date 2018/10/10 11:44
     * @return mixed
     */
    public static function __callStatic($className, $arguments)
    {
        $namespace = __NAMESPACE__;
        switch ($className){
            case 'MiniProgram':
                $namespace .= "\mini\\";
        }
        $className = $namespace.$className;
        return new $className($arguments[0]);
    }
}