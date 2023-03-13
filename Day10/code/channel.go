package main

import "fmt"

func main() {
	//var ch1 chan int
	//ch1 = make(chan int, 1)
	//ch1 <- 20
	//x := <-ch1
	//fmt.Println(x)
	ch1 := make(chan int, 100)
	ch2 := make(chan int, 200)
	go send(ch1)
	go receive(ch1, ch2)

	for x := range ch2 {
		fmt.Println(x)
	}
}

func send(ch chan int) {
	for i := 0; i < 100; i++ {
		ch <- i
	}
	close(ch)

}

func receive(ch1 chan int, ch2 chan int) {
	for {
		tmp, ok := <-ch1
		if !ok {
			break
		}
		ch2 <- tmp * tmp
	}
	close(ch2)
}
