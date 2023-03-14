package main

import (
	"fmt"
	"sync"
	"time"
)

var (
	x      int64
	a      sync.WaitGroup
	lock   sync.Mutex
	rwlock sync.RWMutex
)

func add() {
	for i := 0; i < 5000; i++ {
		lock.Lock()
		x += 1
		lock.Unlock()
	}
	wg.Done()
}

func write() {
	rwlock.Lock()
	x += 1
	time.Sleep(10 * time.Millisecond)
	rwlock.Unlock()
	wg.Done()
}

func read() {
	rwlock.RLock()
	time.Sleep(time.Millisecond)
	rwlock.RUnlock()
	wg.Done()
}

func main() {
	//wg.Add(2)
	//go add()
	//go add()
	//wg.Wait()
	//fmt.Println(x)
	start := time.Now()
	for i := 0; i < 1000; i++ {
		wg.Add(1)
		go read()
	}

	for i := 0; i < 10; i++ {
		wg.Add(1)
		go write()
	}
	wg.Wait()
	fmt.Println(time.Now().Sub(start))
}
