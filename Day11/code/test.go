package main

import (
	"fmt"
	"math/rand"
	"sync"
	"time"
)

var (
	wg   sync.WaitGroup
	once sync.Once
)

/*
使用 goroutine 和 channel 实现一个计算int64随机数各位数和的程序，例如生成随机数61345，计算其每个位数上的数字之和为19。
1.开启一个 goroutine 循环生成int64类型的随机数，发送到jobChan
2.开启24个 goroutine 从jobChan中取出随机数计算各位数的和，将结果发送到resultChan
3.主 goroutine 从resultChan取出结果并打印到终端输出
*/
func random(jobchan chan int64) {
	for i := 0; i < 100; i++ {
		r := rand.New(rand.NewSource(time.Now().UnixNano()))
		num := r.Int63()
		jobchan <- num
		time.Sleep(time.Millisecond * 10)
	}
	close(jobchan)
	wg.Done()
}

func sum(resultchan chan int64, jobchan chan int64) {
	for {
		num, ok := <-jobchan
		if !ok {
			once.Do(func() {
				close(resultchan)
			})
			break
		}
		fmt.Println(num)
		var total int64
		for num > 0 {
			tmp := num % 10
			total += tmp
			num /= 10
		}
		resultchan <- total
	}
	wg.Done()
}

func main() {
	jobchan := make(chan int64, 4096)
	resultchan := make(chan int64, 4096)
	wg.Add(1)
	go random(jobchan)
	for i := 0; i < 24; i++ {
		wg.Add(1)
		go sum(resultchan, jobchan)
	}
	for num := range resultchan {
		fmt.Println(num)
	}
	wg.Wait()
}
