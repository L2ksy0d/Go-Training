package main

import (
	"fmt"
	"sync"
)

//var (
//	wg sync.WaitGroup
//)
var m = make(map[int]int)
var m1 = sync.Map{}

//func get(key int) int {
//	return m1.Load(key)
//}

func set(key, value int) {
	m[key] = value
}

func main() {
	for i := 0; i < 20; i++ {
		wg.Add(1)
		go func(i int) {
			//set(i, i)
			//fmt.Printf("key: %d, value: %d\n", i, get(i))
			//wg.Done()
			m1.Store(i, i+100)
			value, _ := m1.Load(i)
			fmt.Printf("key: %d, value: %d\n", i, value)
			wg.Done()
		}(i)
	}
	wg.Wait()
}
