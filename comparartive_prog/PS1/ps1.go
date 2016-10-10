////////////////////////////////////////////////////////////////////////////////////////////////
//
//Functions and test scripts in ps1.go and ps1_test.go
//
///////////////////////////////////////////////////////////////////////////////////////////////

package ps1

import (
	"errors"
	"io/ioutil"
	"math"
	"strconv"
	"strings"
)

///////////////////////////////////////////////
//											 //
// 				PROBLEM #1					 //
//											 //
///////////////////////////////////////////////

// This function checks if a number is prime

func isPrime(j int) bool {

	y := true

	for denum := 2; float64(denum) <= math.Sqrt(float64(j)+1); denum++ {

		if j%denum == 0 {
			y = false
			break
		}

	}

	return y
}

// This function counts the number of prime numbers less than 'n'
// It returns zero if a number is less than 0 (less than2)

func countPrime(n int) int {

	var j int

	if n >= 2 {

		for i := n; i > 1; i-- {

			if isPrime(i) {
				j++
			}
		}

		return j

	} else {

		return 0

	}

}

///////////////////////////////////////////////
//											 //
// 				PROBLEM #2					 //
//											 //
///////////////////////////////////////////////

// Used the example discussedin class to use the readfile
// This function takes a text file as an input then make a map of words in the file and
// with the number of words repeated as values in the map

func countStrings(fileName string) map[string]int {

	var wordMap map[string]int = make(map[string]int)

	allBytes, err := ioutil.ReadFile(fileName)

	if err != nil {
		panic("Is not able to read a file or file does not exists")
	}

	// type cast the content we red from file to string
	allString := string(allBytes)

	// Split the big string to small string word by word
	words := strings.Split(allString, " ")

	for _, w := range words {

		if w != " " || w != "\n" {

			_, sk := wordMap[w]

			if sk {
				wordMap[w]++
			} else {
				wordMap[w] = 1
			}

		}
	}

	return wordMap
}

///////////////////////////////////////////////
//											 //
// 				PROBLEM #3 AND #4			 //
//											 //
///////////////////////////////////////////////

type Time24 struct {
	hour, minute, second uint8
}

func validTime24(t Time24) bool {
	if 0 <= t.hour && t.hour < 24 &&
		0 <= t.minute && t.minute < 60 &&
		0 <= t.second && t.second < 60 {

		return true

	} else {

		return false
	}
}

func equalsTime24(a, b Time24) bool {
	if a.hour == b.hour &&
		a.minute == b.minute &&
		a.second == b.second {
		return true
	} else {
		return false
	}
}

func lessThanTime24(a, b Time24) bool {
	if a.hour < b.hour {
		return true
	} else if a.hour > b.hour {
		return false
	} else if a.minute < b.minute {
		return true
	} else if a.minute > b.minute {
		return false
	} else if a.second < b.second {
		return true
	} else {
		return false
	}
}

func minTime24(times []Time24) (Time24, error) {

	min := Time24{}
	if len(times) == 0 {

		return min, errors.New("Times slice is empty")

	} else {
		min = times[1]
		for _, moment1 := range times {
			if lessThanTime24(moment1, min) {
				min = moment1
			}
		}

		return min, nil
	}
}

func (t Time24) String() string {

	h := strconv.Itoa(int(t.hour))
	m := strconv.Itoa(int(t.minute))
	s := strconv.Itoa(int(t.second))

	if len(h) == 1 {
		h = "0" + h
	}
	if len(m) == 1 {
		m = "0" + m
	}
	if len(s) == 1 {
		s = "0" + s
	}

	timeString := h + ":" + m + ":" + s

	return timeString
}

func (t *Time24) AddOneHour() {

	if t.hour == 24 {

		t.hour = 0
	} else {
		t.hour++
	}
}

///////////////////////////////////////////////
//											 //
// 				PROBLEM #5					 //
//											 //
///////////////////////////////////////////////

func allBitSeqs(n int) [][]int {

	permutation := int(math.Pow(2, float64(n)))

	bitseq := make([][]int, permutation)

	for i := range bitseq {
		bitseq[i] = make([]int, n)
	}

	for i := 0; i < permutation; i++ {

		z := i

		for j := 0; j < n; j++ {

			if z > 0 {

				bitseq[i][j] = z % 2
				z = z / 2
			} else {
				break
			}

		}
	}

	return bitseq
}

///////////////////////////////////////////////
