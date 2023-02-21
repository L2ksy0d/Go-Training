package main

import "fmt"

func main(){
	LabelAndGoto()
}

func LabelAndGoto(){
outside:
	for i := 0; i < 10; i++ {
		for j := 0; j < 10; j++ {
			fmt.Print("+ ")
			if i == 9 && j == 4{
				break outside
			}
		}
		fmt.Println()
	}
	fmt.Println()
	/*
	output:
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + + + + + + + 
	+ + + + +
	*/

	if i := 1; i ==1 {
		goto four
	}
	fmt.Println("jump here")
	four:
	fmt.Println("goto here")
}