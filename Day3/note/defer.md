# defer
延迟执行

tips:
* 延迟执行的函数会被压入栈中，return后按照先进后出的顺序调用
* 延迟执行的函数其参数会立即求值

## recover
defer...recover可以用来防止运行错误导致的程序中途退出

如果在defer函数中调用了内置函数recover，并且定义该defer语句的函数发生了panic异常，recover会使程序从panic中恢复，并返回panic value。导致panic异常的函数不会继续运行，但能正常返回。在未发生panic时调用recover，recover会返回nil

