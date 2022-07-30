package main

import (
	"fmt"
	"net"
	"sort"
)

func worker(ports, result chan int) {
	for port := range ports {
		address := fmt.Sprintf("scanme.nmap.org:%d", port)
		conn, err := net.Dial("tcp", address)
		if err != nil {
			result <- 0
			continue
		}
		conn.Close()
		result <- port
	}
}

func main() {
	ports := make(chan int, 100)
	result := make(chan int)
	var openport []int

	for i := 0; i < cap(ports); i++ {
		go worker(ports, result)
	}

	go func() {
		for i := 0; i < 1024; i++ {
			ports <- i
		}
	}()

	for i := 0; i < 1024; i++ {
		port := <-result
		if port != 0 {
			openport = append(openport, port)
		}
	}

	close(ports)
	close(result)
	sort.Ints(openport)

	for _, value := range openport {
		fmt.Printf("%d open\n", value)
	}
}
