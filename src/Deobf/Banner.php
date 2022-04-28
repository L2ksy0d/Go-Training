<?php
namespace Deobf;


class Banner{
    public function original(){
        echo "     ____  __  ______     ____             __    ____\n";
        echo "    / __ \/ / / / __ \   / __ \___  ____  / /_  / __/\n";
        echo "   / /_/ / /_/ / /_/ /  / / / / _ \/ __ \/ __ \/ /_  \n";
        echo "  / ____/ __  / ____/  / /_/ /  __/ /_/ / /_/ / __/  \n";
        echo " /_/   /_/ /_/_/      /_____/\___/\____/_.___/_/ \n";
    }

    public function help(){
        echo "\n";
        echo "可选参数可以下列参数组合:\n";
        echo "注意 -y参数如果启用请放在参数的最后\n";
        echo "    -h          : 帮助信息\n";
        echo "    -p  path    : 反混淆文件路径\n";
        echo "    -f          : 启用特征提取模块\n";
        echo "    -y  yara    : 启用Yara检测模块，如需使用自定义规则在参数后面写规则路径\n";
    }
}