<?php

namespace vova07\console;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * ConsoleRunner - a component for running console commands on background.
 *
 * Usage:
 * ```
 * ...
 * $cr = new ConsoleRunner(['file' => '@my/path/to/yii', 'php' => 'c:\path\to\php.exe']);
 * $cr->run('controller/action param1 param2 ...');
 * ...
 * ```
 * or use it like an application component:
 * ```
 * // config.php
 * ...
 * components [
 *     'consoleRunner' => [
 *         'class' => 'vova07\console\ConsoleRunner',
 *         'file' => '@my/path/to/yii' // or an absolute path to console file
 *         'php' => 'c:\path\to\php.exe // needed for Windows
 *     ]
 * ]
 * ...
 *
 * // some-file.php
 * Yii::$app->consoleRunner->run('controller/action param1 param2 ...');
 * ```
 */
class ConsoleRunner extends Component
{
    /**
     * @var string Console application file that will be executed.
     * Usually it can be `yii` file.
     */
    public $file;

    /**
     * @var string PHP executable including full path
     * Needed because PHP_BINDIR and PHP_BINARY do not work properly under Windows
     */
    public $php;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->file === null) {
            throw new InvalidConfigException('The "file" property must be set.');
        }
        if (($this->isWindows() === true) && ($this->php === null))    
            throw new InvalidConfigException('The "php" property must be set when running under Windows.');
        }
    }

    /**
     * Running console command on background
     *
     * @param string $cmd Argument that will be passed to console application
     * @return boolean
     */
    public function run($cmd)
    {
        if ($this->isWindows() === true) {
            $cmd = $this->php . ' ' . Yii::getAlias($this->file) . ' ' . $cmd;
            pclose(popen('start /b ' . $cmd, 'r'));
        } else {
            $cmd = PHP_BINDIR . '/php ' . Yii::getAlias($this->file) . ' ' . $cmd;
            pclose(popen($cmd . ' > /dev/null &', 'r'));
        }
        return true;
    }

    /**
     * Check operating system
     *
     * @return boolean true if it's Windows OS
     */
    protected function isWindows()
    {
        if (PHP_OS == 'WINNT' || PHP_OS == 'WIN32') {
            return true;
        } else {
            return false;
        }
    }
}
