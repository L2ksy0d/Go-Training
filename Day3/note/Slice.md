# 切片

* 切片是对数组的引用，本身不存储任何数据，只是对数组底层数据中一段的描述
* 切片的索引从0开始
* 切片是引用类型，默认值为nil
* 遍历切片的方式和遍历数组相同

## 声明
一个slice由三个部分构成：指针、长度和容量。指针指向第一个slice元素对应的底层数组元素的地址，要注意的是slice的第一个元素并不一定就是数组的第一个元素。长度对应slice中元素的数目；长度不能超过容量，容量一般是从slice的开始位置到底层数据的结尾位置。内置的len和cap函数分别返回slice的长度和容量。

多个slice之间可以共享底层的数据，并且引用的数组部分区间可能重叠。下图显示了表示一年中每个月份名字的字符串数组，还有重叠引用了该数组的两个slice。数组这样定义
```go
months := [...]string{1: "January", /* ... */, 12: "December"}
```
![image](https://cdn.staticaly.com/gh/L2ksy0d/image-host@master/20230222/image.2fyf6uz07v40.png)

因此一月份是months[1]，十二月份是months[12]。通常，数组的第一个元素从索引0开始，但是月份一般是从1开始的，因此我们声明数组时直接跳过第0个元素，第0个元素会被自动初始化为空字符串。

slice的切片操作s[i:j]，其中0 ≤ i≤ j≤ cap(s)，用于创建一个新的slice，引用s的从第i个元素开始到第j-1个元素的子序列。新的slice将只有j-i个元素。如果i位置的索引被省略的话将使用0代替，如果j位置的索引被省略的话将使用len(s)代替。因此，months[1:13]切片操作将引用全部有效的月份，和months[1:]操作等价；months[:]切片操作则是引用整个数组。让我们分别定义表示第二季度和北方夏天月份的slice，它们有重叠部分

## 长度和容量

![image](https://cdn.staticaly.com/gh/L2ksy0d/image-host@master/image.3egxt8oj3ca0.png)

![image](https://cdn.staticaly.com/gh/L2ksy0d/image-host@master/image.26ng3uqeqhr4.png)