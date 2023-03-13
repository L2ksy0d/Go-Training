package main

import "fmt"

func main() {
	jobs := make(chan int, 100)
	result := make(chan int, 100)

	//开启3个go
	for i := 0; i < 3; i++ {
		go worker(i, jobs, result)
	}

	for i := 0; i < 5; i++ {
		jobs <- i
	}
	close(jobs)
	for ret := range result {
		fmt.Println(ret)
	}
}

func worker(id int, jobs <-chan int, result chan<- int) {
	for job := range jobs {
		fmt.Printf("Worker:%d start job:%d\n", id, job)
		result <- job * 2
		fmt.Printf("Worker:%d stop job:%d\n", id, job)
	}
}
