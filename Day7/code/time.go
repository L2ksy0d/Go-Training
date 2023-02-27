package main

import (
	"fmt"
	"strconv"
	"time"
)

func main() {
	timeDemo()
}

//时间类型
func timeDemo() {
	//获取当前时间
	now := time.Now()
	fmt.Printf("current time: %v\n", now)
	//
	year := now.Year()   //年
	month := now.Month() //月
	fmt.Printf("month: %T\n", month)
	day := now.Day() //日
	fmt.Printf("month: %T\n", day)
	hour := now.Hour()     //小时
	minute := now.Minute() //分钟
	second := now.Second() //秒
	fmt.Printf("%d-%02d-%02d %02d:%02d:%02d\n", year, month, day, hour, minute, second)

	////时间戳
	//timestmp1 := now.Unix()     //时间戳
	//timestmp2 := now.UnixNano() //纳秒时间戳
	//fmt.Printf("%10d %08d\n", timestmp1, timestmp2)

	//将时间戳转换为具体的时间格式
	//t := time.Unix(1677507582, 0)
	//fmt.Println(t)

	//时间间隔,用来强制转换
	//time.Sleep(5 * time.Second)
	//n := 5
	//time.Sleep(time.Duration(n) * time.Second)

	//Add：
	//new_now := now.Add(time.Hour)
	//fmt.Println(new_now)
	//
	//Sub：
	//fmt.Println(new_now.Sub(now))

	//Ticker
	//for tmp := range time.Tick(time.Second) {
	//	fmt.Println(tmp)
	//}

	//时间格式化
	RET1 := now.Format("2006-01-02")
	fmt.Println(RET1)

	//解析字符串类型的时间
	//时区
	timestr := "2019/08/07 14:23:14"
	loc, err := time.LoadLocation("Asia/Shanghai")
	if err != nil {
		fmt.Println(err)
		return
	}
	ret, err := time.ParseInLocation("2006/01/02 15:04:05", timestr, loc)
	if err != nil {
		fmt.Println(err)
		return
	}
	fmt.Println(ret)
}

func timeformat() {
	now := time.Now()
	timestr := strconv.Itoa(now.Year()) + "/" + strconv.Itoa(now.Month()) + "/" + strconv.Itoa(now.Day()) + " " + strconv.Itoa(now.Hour()) + ":" + strconv.Itoa(now.Minute()) + ":" + strconv.Itoa(now.Second())
	ret, err := time.Parse("2006/01/02 15:04:05", timestr)
	if err != nil {
		fmt.Println(err)
		return
	}
	fmt.Println(ret)
}
