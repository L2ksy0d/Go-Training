# 数组

## 声明
```go
var a [3]int             // array of 3 integers
fmt.Println(a[0])        // print the first element
fmt.Println(a[len(a)-1]) // print the last element, a[2]
```
默认情况下，数组的每个元素都被初始化为元素类型对应的零值

* 注意长度不能留空，否则会变成切片类型
* 长度是数组数据的一部分，因此数组长度不可变
* 等号右侧的长度可以简写为`[...]`自动判断

## 遍历
数组的每个元素可以通过索引下标来访问，索引下标的范围是从0开始到数组长度减1的位置。内置的len函数将返回数组中元素的个数,数组的遍历可以使用`for...range`语句，键值不需要的部分使用`_`占位